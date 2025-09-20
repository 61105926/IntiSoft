<?php

namespace App\Http\Livewire\EntradaFolclorica;

use Livewire\Component;
use App\Models\EntradaFolclorica;
use App\Models\EntradaFolcloricaDetalle;
use App\Models\EntradaFolcloricaGarantia;
use App\Models\Producto;
use Carbon\Carbon;

class ParticipanteController extends Component
{
    public $entradaId;
    public $entrada;
    public $participantes;
    public $productos;
    
    public $showModal = false;
    public $participanteId = null;
    public $editMode = false;
    
    // Propiedades del formulario
    public $nombre_participante;
    public $telefono_participante;
    public $documento_identidad;
    public $producto_id;
    public $talla_solicitada;
    public $observaciones_participante;
    public $precio_unitario = 0;
    public $crear_garantia = true;
    public $monto_garantia;
    public $metodo_pago_garantia = 'EFECTIVO';
    
    protected function rules()
    {
        return [
            'nombre_participante' => 'required|max:150',
            'documento_identidad' => 'nullable|max:20',
            'producto_id' => 'required',
            'precio_unitario' => 'required|numeric|min:0',
            'monto_garantia' => 'required_if:crear_garantia,true|numeric|min:0',
        ];
    }
    
    public function mount($id)
    {
        $this->entradaId = $id;
        $this->entrada = EntradaFolclorica::find($id);
        $this->monto_garantia = $this->entrada->monto_garantia_individual ?? 0;
        $this->cargarDatos();
    }
    
    public function cargarDatos()
    {
        $this->participantes = EntradaFolcloricaDetalle::where('entrada_folclorica_id', $this->entradaId)
            ->with(['producto', 'garantia'])
            ->get();
            
        $this->productos = Producto::whereHas('categoria', function($query) {
                $query->whereIn('nombre', [
                    'TRAJES FOLKLORICOS', 
                    'POLLERAS', 
                    'ACCESSORIOS DANZA',
                    'Trajes Femeninos',
                    'Trajes Masculinos',
                    'Accesorios'
                ]);
            })
            ->get();
    }
    
    public function abrirModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }
    
    public function editarParticipante($id)
    {
        $participante = EntradaFolcloricaDetalle::find($id);
        if ($participante) {
            $this->participanteId = $id;
            $this->nombre_participante = $participante->nombre_participante;
            $this->telefono_participante = $participante->telefono_participante;
            $this->documento_identidad = $participante->documento_identidad;
            $this->producto_id = $participante->producto_id;
            $this->talla_solicitada = $participante->talla_solicitada;
            $this->observaciones_participante = $participante->observaciones_participante;
            $this->precio_unitario = $participante->precio_unitario;
            
            // Check if guarantee exists
            $garantia = $participante->garantia;
            $this->crear_garantia = $garantia ? true : false;
            if ($garantia) {
                $this->monto_garantia = $garantia->monto_garantia;
                $this->metodo_pago_garantia = $garantia->metodo_pago;
            }
            
            $this->editMode = true;
            $this->showModal = true;
        }
    }
    
    public function eliminarParticipante($id)
    {
        $participante = EntradaFolcloricaDetalle::find($id);
        if ($participante) {
            // Delete guarantee if exists
            if ($participante->garantia) {
                $participante->garantia->delete();
            }
            
            $participante->delete();
            
            // Recalculate totals
            $this->entrada->calcularTotales();
            $this->entrada->save();
            
            session()->flash('message', 'Participante eliminado correctamente.');
            $this->cargarDatos();
        }
    }
    
    public function cerrarModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->participanteId = null;
        $this->nombre_participante = '';
        $this->telefono_participante = '';
        $this->documento_identidad = '';
        $this->producto_id = '';
        $this->talla_solicitada = '';
        $this->observaciones_participante = '';
        $this->precio_unitario = 0;
        $this->crear_garantia = true;
        $this->monto_garantia = $this->entrada->monto_garantia_individual ?? 0;
        $this->metodo_pago_garantia = 'EFECTIVO';
    }
    
    public function actualizarPrecio()
    {
        if ($this->producto_id) {
            $producto = Producto::find($this->producto_id);
            if ($producto && $producto->precio_venta) {
                $this->precio_unitario = $producto->precio_venta;
            }
        }
    }
    
    public function guardar()
    {
        $this->validate();
        
        if ($this->editMode) {
            $participante = EntradaFolcloricaDetalle::find($this->participanteId);
        } else {
            $participante = new EntradaFolcloricaDetalle();
            $participante->entrada_folclorica_id = $this->entradaId;
        }
        
        $producto = Producto::find($this->producto_id);
        
        $participante->fill([
            'producto_id' => $this->producto_id,
            'codigo_producto' => $producto->codigo ?? '',
            'nombre_producto' => $producto->nombre ?? '',
            'descripcion_producto' => $producto->descripcion ?? '',
            'talla' => $producto->talla ?? '',
            'color' => $producto->color ?? '',
            'nombre_participante' => $this->nombre_participante,
            'telefono_participante' => $this->telefono_participante,
            'talla_solicitada' => $this->talla_solicitada,
            'observaciones_participante' => $this->observaciones_participante,
            'cantidad' => 1,
            'precio_unitario' => $this->precio_unitario,
            'descuento_unitario' => 0,
            'estado' => EntradaFolcloricaDetalle::ESTADO_PENDIENTE,
        ]);
        
        $participante->calcularSubtotal();
        $participante->save();
        
        // Create or update guarantee if requested
        if ($this->crear_garantia) {
            $garantia = $participante->garantia ?? new EntradaFolcloricaGarantia();
            
            if (!$participante->garantia) {
                $garantia->entrada_folclorica_id = $this->entradaId;
                $garantia->entrada_detalle_id = $participante->id;
            }
            
            $garantia->fill([
                'nombre_participante' => $this->nombre_participante,
                'telefono_participante' => $this->telefono_participante,
                'documento_identidad' => $this->documento_identidad,
                'monto_garantia' => $this->monto_garantia,
                'monto_disponible' => $this->monto_garantia,
                'estado' => EntradaFolcloricaGarantia::ESTADO_ACTIVA,
                'fecha_creacion_garantia' => Carbon::now(),
                'metodo_pago' => $this->metodo_pago_garantia,
                'usuario_creacion' => auth()->id(),
            ]);
            
            $garantia->save();
            
            // Generate guarantee number if it's new
            if (!$garantia->numero_garantia) {
                $garantia->numero_garantia = $garantia->generarNumeroGarantia();
                $garantia->save();
            }
        }
        
        // Recalculate totals
        $this->entrada->calcularTotales();
        $this->entrada->save();
        
        session()->flash('message', $this->editMode ? 'Participante actualizado correctamente.' : 'Participante agregado correctamente.');
        $this->cerrarModal();
        $this->cargarDatos();
    }
    
    public function marcarComoEntregado($id)
    {
        $participante = EntradaFolcloricaDetalle::find($id);
        if ($participante) {
            $participante->marcarComoEntregado('Entregado desde módulo de participantes');
            session()->flash('message', 'Producto marcado como entregado.');
            $this->cargarDatos();
        }
    }
    
    public function render()
    {
        return view('livewire.entrada-folclorica.participante')->extends('layouts.theme.modern-app')->section('content');
    }
}