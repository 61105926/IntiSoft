<?php

namespace App\Http\Livewire\EntradaFolclorica;

use Livewire\Component;
use App\Models\EntradaFolclorica;
use App\Models\EntradaFolcloricaDetalle;
use App\Models\EntradaFolcloricaGarantia;
use Carbon\Carbon;

class DevolucionController extends Component
{
    public $entradaId;
    public $entrada;
    public $participantes;
    
    public $showModal = false;
    public $showGarantiaModal = false;
    public $participanteId = null;
    public $garantiaId = null;
    
    // Propiedades para devolución de productos
    public $observaciones_devolucion = '';
    public $penalizacion = 0;
    public $motivo_penalizacion = '';
    
    // Propiedades para devolución de garantía
    public $monto_devolver = 0;
    public $observaciones_garantia = '';
    
    protected function rules()
    {
        return [
            'penalizacion' => 'nullable|numeric|min:0',
            'monto_devolver' => 'required|numeric|min:0.01|max:' . ($this->garantiaSeleccionada->monto_disponible ?? 1000),
        ];
    }
    
    public function getGarantiaSeleccionadaProperty()
    {
        return $this->garantiaId ? EntradaFolcloricaGarantia::find($this->garantiaId) : null;
    }
    
    public function mount($id)
    {
        $this->entradaId = $id;
        $this->entrada = EntradaFolclorica::find($id);
        $this->cargarDatos();
    }
    
    public function cargarDatos()
    {
        $this->participantes = EntradaFolcloricaDetalle::where('entrada_folclorica_id', $this->entradaId)
            ->with(['producto', 'garantia'])
            ->get();
    }
    
    public function abrirModalDevolucion($participanteId)
    {
        $this->participanteId = $participanteId;
        $this->observaciones_devolucion = '';
        $this->penalizacion = 0;
        $this->motivo_penalizacion = '';
        $this->showModal = true;
    }
    
    public function abrirModalGarantia($garantiaId)
    {
        $this->garantiaId = $garantiaId;
        $garantia = $this->garantiaSeleccionada;
        $this->monto_devolver = $garantia->monto_disponible;
        $this->observaciones_garantia = '';
        $this->showGarantiaModal = true;
    }
    
    public function cerrarModal()
    {
        $this->showModal = false;
        $this->participanteId = null;
        $this->resetForm();
    }
    
    public function cerrarGarantiaModal()
    {
        $this->showGarantiaModal = false;
        $this->garantiaId = null;
        $this->resetGarantiaForm();
    }
    
    public function resetForm()
    {
        $this->observaciones_devolucion = '';
        $this->penalizacion = 0;
        $this->motivo_penalizacion = '';
    }
    
    public function resetGarantiaForm()
    {
        $this->monto_devolver = 0;
        $this->observaciones_garantia = '';
    }
    
    public function procesarDevolucion()
    {
        $this->validate([
            'penalizacion' => 'nullable|numeric|min:0',
        ]);
        
        $participante = EntradaFolcloricaDetalle::find($this->participanteId);
        if ($participante) {
            $participante->marcarComoDevuelto(
                $this->observaciones_devolucion,
                $this->penalizacion,
                $this->motivo_penalizacion
            );
            
            // Update main entry status
            $this->entrada->procesarDevolucion();
            
            session()->flash('message', 'Producto devuelto correctamente.');
            $this->cerrarModal();
            $this->cargarDatos();
        }
    }
    
    public function procesarDevolucionGarantia()
    {
        $this->validate([
            'monto_devolver' => 'required|numeric|min:0.01|max:' . ($this->garantiaSeleccionada->monto_disponible ?? 1000),
        ]);
        
        $garantia = $this->garantiaSeleccionada;
        if ($garantia) {
            $resultado = $garantia->procesarDevolucion($this->monto_devolver, $this->observaciones_garantia);
            
            if ($resultado) {
                session()->flash('message', 'Garantía devuelta correctamente.');
                $this->cerrarGarantiaModal();
                $this->cargarDatos();
            } else {
                session()->flash('error', 'No se pudo procesar la devolución de la garantía.');
            }
        }
    }
    
    public function aplicarPenalizacionGarantia($garantiaId, $monto, $motivo = null)
    {
        $garantia = EntradaFolcloricaGarantia::find($garantiaId);
        if ($garantia) {
            $resultado = $garantia->aplicarPenalizacion($monto, $motivo);
            
            if ($resultado) {
                session()->flash('message', 'Penalización aplicada correctamente.');
                $this->cargarDatos();
            } else {
                session()->flash('error', 'No se pudo aplicar la penalización.');
            }
        }
    }
    
    public function getParticipantesPendientesProperty()
    {
        return $this->participantes->where('estado', 'ENTREGADO');
    }
    
    public function getParticipantesDevueltosProperty()
    {
        return $this->participantes->whereIn('estado', ['DEVUELTO', 'DEVUELTO_PARCIAL']);
    }
    
    public function getGarantiasActivasProperty()
    {
        return $this->participantes->map(function($p) {
            return $p->garantia;
        })->filter(function($g) {
            return $g && $g->puede_ser_devuelta;
        });
    }
    
    public function render()
    {
        return view('livewire.entrada-folclorica.devolucion')->extends('layouts.theme.modern-app')->section('content');
    }
}