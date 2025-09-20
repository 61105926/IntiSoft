<?php

namespace App\Http\Livewire\EntradaFolclorica;

use Livewire\Component;
use App\Models\EntradaFolclorica;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Sucursal;
use Carbon\Carbon;

class EntradaFolcloricaController extends Component
{
    public $entradas;
    public $entradaSeleccionada = null;
    public $showModal = false;
    public $showDetalleModal = false;
    public $showGarantiaModal = false;
    
    // Propiedades del formulario principal
    public $numero_entrada;
    public $sucursal_id;
    public $nombre_evento;
    public $descripcion_evento;
    public $fecha_evento;
    public $hora_evento;
    public $lugar_evento;
    public $cliente_responsable_id;
    public $contacto_nombre;
    public $contacto_telefono;
    public $contacto_email;
    public $fecha_entrega;
    public $hora_entrega;
    public $fecha_devolucion_programada;
    public $hora_devolucion_programada;
    public $cantidad_participantes = 0;
    public $monto_garantia_individual = 0;
    public $condiciones_especiales;
    public $observaciones;
    
    // Propiedades para manejo de detalles (participantes)
    public $participantes = [];
    public $productos;
    public $sucursales;
    public $clientes;
    
    // Filtros
    public $filtroEstado = '';
    public $filtroFecha = '';
    public $busqueda = '';
    
    protected function rules()
    {
        return [
            'sucursal_id' => 'required',
            'nombre_evento' => 'required|max:200',
            'fecha_evento' => 'required|date',
            'hora_evento' => 'required',
            'lugar_evento' => 'required|max:200',
            'cliente_responsable_id' => 'required',
            'contacto_nombre' => 'required|max:150',
            'fecha_entrega' => 'required|date',
            'hora_entrega' => 'required',
            'fecha_devolucion_programada' => 'required|date|after:fecha_entrega',
            'hora_devolucion_programada' => 'required',
            'cantidad_participantes' => 'required|integer|min:1',
            'monto_garantia_individual' => 'required|numeric|min:0',
        ];
    }
    
    public function mount()
    {
        $this->cargarDatos();
        $this->cargarEntradas();
        $this->inicializarFecha();
    }
    
    public function cargarDatos()
    {
        $this->sucursales = Sucursal::all();
        $this->clientes = Cliente::all();
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
    
    public function cargarEntradas()
    {
        $query = EntradaFolclorica::with(['sucursal', 'clienteResponsable', 'detalles', 'garantias']);
        
        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }
        
        if ($this->filtroFecha) {
            $query->whereDate('fecha_evento', $this->filtroFecha);
        }
        
        if ($this->busqueda) {
            $query->where(function($q) {
                $q->where('numero_entrada', 'like', '%' . $this->busqueda . '%')
                  ->orWhere('nombre_evento', 'like', '%' . $this->busqueda . '%')
                  ->orWhere('contacto_nombre', 'like', '%' . $this->busqueda . '%');
            });
        }
        
        $this->entradas = $query->orderBy('created_at', 'desc')->get();
    }
    
    public function inicializarFecha()
    {
        $this->fecha_evento = Carbon::today()->format('Y-m-d');
        $this->fecha_entrega = Carbon::today()->format('Y-m-d');
        $this->fecha_devolucion_programada = Carbon::today()->addDays(7)->format('Y-m-d');
        $this->hora_evento = '10:00';
        $this->hora_entrega = '08:00';
        $this->hora_devolucion_programada = '20:00';
    }
    
    public function abrirModal()
    {
        $this->resetForm();
        $this->inicializarFecha();
        $this->showModal = true;
    }
    
    public function cerrarModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->numero_entrada = '';
        $this->sucursal_id = '';
        $this->nombre_evento = '';
        $this->descripcion_evento = '';
        $this->cliente_responsable_id = '';
        $this->contacto_nombre = '';
        $this->contacto_telefono = '';
        $this->contacto_email = '';
        $this->lugar_evento = '';
        $this->cantidad_participantes = 0;
        $this->monto_garantia_individual = 0;
        $this->condiciones_especiales = '';
        $this->observaciones = '';
        $this->participantes = [];
        $this->entradaSeleccionada = null;
    }
    
    public function guardar()
    {
        $this->validate();
        
        $entrada = new EntradaFolclorica();
        $entrada->fill([
            'sucursal_id' => $this->sucursal_id,
            'nombre_evento' => $this->nombre_evento,
            'descripcion_evento' => $this->descripcion_evento,
            'fecha_evento' => $this->fecha_evento,
            'hora_evento' => $this->hora_evento,
            'lugar_evento' => $this->lugar_evento,
            'cliente_responsable_id' => $this->cliente_responsable_id,
            'contacto_nombre' => $this->contacto_nombre,
            'contacto_telefono' => $this->contacto_telefono,
            'contacto_email' => $this->contacto_email,
            'fecha_entrega' => $this->fecha_entrega,
            'hora_entrega' => $this->hora_entrega,
            'fecha_devolucion_programada' => $this->fecha_devolucion_programada,
            'hora_devolucion_programada' => $this->hora_devolucion_programada,
            'cantidad_participantes' => $this->cantidad_participantes,
            'monto_garantia_individual' => $this->monto_garantia_individual,
            'condiciones_especiales' => $this->condiciones_especiales,
            'observaciones' => $this->observaciones,
            'usuario_creacion' => auth()->id(),
            'estado' => EntradaFolclorica::ESTADO_ACTIVO,
            'estado_pago' => EntradaFolclorica::ESTADO_PAGO_PENDIENTE,
            'estado_garantias' => EntradaFolclorica::ESTADO_GARANTIAS_PENDIENTE,
            // Campos financieros iniciales
            'subtotal_general' => 0,
            'descuento_general' => 0,
            'total_general' => 0,
            'anticipo_total' => 0,
            'saldo_pendiente' => 0,
            'total_garantias' => $this->cantidad_participantes * $this->monto_garantia_individual,
            'garantias_devueltas' => 0,
            'garantias_pendientes' => $this->cantidad_participantes * $this->monto_garantia_individual,
        ]);
        
        // Generar número de entrada antes de guardar
        $entrada->numero_entrada = $this->generarNumeroEntrada($entrada);
        $entrada->save();
        
        session()->flash('message', 'Entrada folclórica creada exitosamente.');
        $this->cerrarModal();
        $this->cargarEntradas();
    }
    
    public function verDetalle($entradaId)
    {
        $this->entradaSeleccionada = EntradaFolclorica::with(['detalles', 'garantias', 'sucursal', 'clienteResponsable'])->find($entradaId);
        $this->showDetalleModal = true;
    }
    
    public function gestionarParticipantes($entradaId)
    {
        return redirect()->route('entrada-folklorica.participantes', $entradaId);
    }
    
    public function gestionarDevoluciones($entradaId)
    {
        return redirect()->route('entrada-folklorica.devoluciones', $entradaId);
    }
    
    public function cerrarDetalleModal()
    {
        $this->showDetalleModal = false;
        $this->entradaSeleccionada = null;
    }
    
    public function agregarParticipante()
    {
        $this->participantes[] = [
            'nombre_participante' => '',
            'telefono_participante' => '',
            'documento_identidad' => '',
            'producto_id' => '',
            'talla_solicitada' => '',
            'observaciones_participante' => '',
            'precio_unitario' => 0,
            'garantia_individual' => $this->monto_garantia_individual,
        ];
    }
    
    public function quitarParticipante($index)
    {
        unset($this->participantes[$index]);
        $this->participantes = array_values($this->participantes);
    }
    
    public function actualizar()
    {
        $this->cargarEntradas();
    }
    
    public function aplicarFiltros()
    {
        $this->cargarEntradas();
    }
    
    public function limpiarFiltros()
    {
        $this->filtroEstado = '';
        $this->filtroFecha = '';
        $this->busqueda = '';
        $this->cargarEntradas();
    }
    
    public function cambiarEstado($entradaId, $nuevoEstado)
    {
        $entrada = EntradaFolclorica::find($entradaId);
        if ($entrada) {
            $entrada->estado = $nuevoEstado;
            $entrada->save();
            
            session()->flash('message', 'Estado actualizado correctamente.');
            $this->cargarEntradas();
        }
    }
    
    private function generarNumeroEntrada($entrada)
    {
        $sucursalId = str_pad($entrada->sucursal_id, 2, '0', STR_PAD_LEFT);
        $fechaEvento = Carbon::parse($entrada->fecha_evento)->format('Ymd');
        
        // Obtener el último número del día
        $ultimaEntrada = EntradaFolclorica::where('sucursal_id', $entrada->sucursal_id)
            ->whereDate('fecha_evento', Carbon::parse($entrada->fecha_evento))
            ->orderBy('id', 'desc')
            ->first();
        
        $siguiente = 1;
        if ($ultimaEntrada && $ultimaEntrada->numero_entrada) {
            $partes = explode('-', $ultimaEntrada->numero_entrada);
            if (count($partes) >= 3) {
                $siguiente = intval($partes[2]) + 1;
            }
        }
        
        return "FOLK-{$sucursalId}{$fechaEvento}-" . str_pad($siguiente, 4, '0', STR_PAD_LEFT);
    }
    
    public function render()
    {
        return view('livewire.entrada-folclorica.entrada-folclorica')->extends('layouts.theme.modern-app')->section('content');
    }
}