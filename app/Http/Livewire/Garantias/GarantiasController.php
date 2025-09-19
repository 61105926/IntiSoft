<?php

namespace App\Http\Livewire\Garantias;

use App\Models\Cliente;
use App\Models\Garantia;
use App\Models\Sucursal;
use App\Models\TipoGarantia;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class GarantiasController extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    // Filtros
    public $searchTerm = '';
    public $filterEstado = 'TODAS';
    public $filterTipo = 'TODOS';
    public $filterSucursal = 'TODAS';

    // Modales
    public $showNewGarantiaModal = false;
    public $showViewGarantiaModal = false;
    public $showDevolucionModal = false;

    // Form data para nueva garantía
    public $tipo_garantia_id = '';
    public $cliente_id = '';
    public $descripcion = '';
    public $monto = 0;
    public $documento_respaldo = '';
    public $observaciones = '';
    public $sucursal_id = '';

    // Garantía seleccionada
    public $selectedGarantia = null;

    // Devolución
    public $montoDevolucion = 0;
    public $observacionesDevolucion = '';

    protected $rules = [
        'tipo_garantia_id' => 'required|exists:tipos_garantia,id',
        'cliente_id' => 'required|exists:clientes,id',
        'descripcion' => 'required|string|max:500',
        'monto' => 'required|numeric|min:0',
        'documento_respaldo' => 'nullable|string|max:200',
        'observaciones' => 'nullable|string|max:1000',
        'sucursal_id' => 'required|exists:sucursals,id',
    ];

    public function mount()
    {
        $this->sucursal_id = Auth::user()->sucursal_id ?? Sucursal::first()->id;
    }

    public function render()
    {
        $garantias = $this->getFilteredGarantias();
        $clientes = Cliente::orderBy('nombres')->get();
        $tiposGarantia = TipoGarantia::activos()->orderBy('nombre')->get();
        $sucursales = Sucursal::orderBy('nombre')->get();
        $estadisticas = $this->getEstadisticas();

        return view('livewire.garantias.garantias-controller', [
            'garantias' => $garantias,
            'clientes' => $clientes,
            'tiposGarantia' => $tiposGarantia,
            'sucursales' => $sucursales,
            'estadisticas' => $estadisticas,
        ])->extends('layouts.theme.app')->section('content');
    }

    private function getFilteredGarantias()
    {
        $query = Garantia::with(['tipoGarantia', 'cliente', 'sucursal', 'usuarioRecepcion', 'usuarioDevolucion']);

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('numero_ticket', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('descripcion', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('documento_respaldo', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereHas('cliente', function ($clienteQuery) {
                      $clienteQuery->where('nombres', 'like', '%' . $this->searchTerm . '%')
                                   ->orWhere('apellidos', 'like', '%' . $this->searchTerm . '%')
                                   ->orWhere('carnet_identidad', 'like', '%' . $this->searchTerm . '%');
                  });
            });
        }

        if ($this->filterEstado !== 'TODAS') {
            if ($this->filterEstado === 'VENCIDAS') {
                $query->vencidas();
            } else {
                $query->where('estado', $this->filterEstado);
            }
        }

        if ($this->filterTipo !== 'TODOS') {
            $query->where('tipo_garantia_id', $this->filterTipo);
        }

        if ($this->filterSucursal !== 'TODAS') {
            $query->where('sucursal_id', $this->filterSucursal);
        }

        return $query->orderBy('fecha_recepcion', 'desc')->paginate(15);
    }

    private function getEstadisticas()
    {
        $sucursalId = Auth::user()->sucursal_id;
        
        $base = Garantia::when($sucursalId, function ($q) use ($sucursalId) {
            return $q->where('sucursal_id', $sucursalId);
        });

        return [
            'total' => (clone $base)->count(),
            'recibidas' => (clone $base)->where('estado', Garantia::ESTADO_RECIBIDA)->count(),
            'vencidas' => (clone $base)->vencidas()->count(),
            'devueltas' => (clone $base)->where('estado', Garantia::ESTADO_DEVUELTA)->count(),
            'aplicadas' => (clone $base)->where('estado', Garantia::ESTADO_APLICADA)->count(),
        ];
    }

    // Métodos para nueva garantía
    public function openNewGarantiaModal()
    {
        $this->resetForm();
        $this->showNewGarantiaModal = true;
    }

    public function closeNewGarantiaModal()
    {
        $this->showNewGarantiaModal = false;
        $this->resetForm();
    }

    public function updatedTipoGarantiaId()
    {
        $tipo = TipoGarantia::find($this->tipo_garantia_id);
        if ($tipo && !$tipo->requiere_monto) {
            $this->monto = 0;
        }
    }

    public function saveGarantia()
    {
        $this->validate();

        // Validar monto según tipo de garantía
        $tipoGarantia = TipoGarantia::find($this->tipo_garantia_id);
        if (!$tipoGarantia->validarMonto($this->monto)) {
            $this->addError('monto', 'El monto no está dentro del rango permitido para este tipo de garantía.');
            return;
        }

        try {
            DB::beginTransaction();

            $garantia = Garantia::create([
                'tipo_garantia_id' => $this->tipo_garantia_id,
                'cliente_id' => $this->cliente_id,
                'descripcion' => $this->descripcion,
                'monto' => $this->monto,
                'documento_respaldo' => $this->documento_respaldo,
                'observaciones' => $this->observaciones,
                'usuario_recepcion' => Auth::id(),
                'sucursal_id' => $this->sucursal_id,
                'fecha_recepcion' => now(),
                'fecha_vencimiento' => $tipoGarantia->calcularFechaVencimiento(),
                'estado' => Garantia::ESTADO_RECIBIDA,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Garantía Recibida!',
                'text' => "La garantía {$garantia->numero_ticket} ha sido registrada exitosamente.",
                'icon' => 'success'
            ]);

            $this->closeNewGarantiaModal();

        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Error al registrar la garantía: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    // Métodos para ver garantía
    public function viewGarantia($garantiaId)
    {
        $this->selectedGarantia = Garantia::with(['tipoGarantia', 'cliente', 'sucursal', 'usuarioRecepcion', 'usuarioDevolucion', 'alquileres.cliente'])
                                          ->find($garantiaId);
        $this->showViewGarantiaModal = true;
    }

    public function closeViewGarantiaModal()
    {
        $this->showViewGarantiaModal = false;
        $this->selectedGarantia = null;
    }

    // Métodos para devolución
    public function openDevolucionModal($garantiaId)
    {
        $this->selectedGarantia = Garantia::find($garantiaId);
        
        if (!$this->selectedGarantia || $this->selectedGarantia->estado !== Garantia::ESTADO_RECIBIDA) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Esta garantía no puede devolverse.',
                'icon' => 'error'
            ]);
            return;
        }

        $this->montoDevolucion = $this->selectedGarantia->monto_disponible;
        $this->observacionesDevolucion = '';
        $this->showDevolucionModal = true;
    }

    public function closeDevolucionModal()
    {
        $this->showDevolucionModal = false;
        $this->selectedGarantia = null;
        $this->montoDevolucion = 0;
        $this->observacionesDevolucion = '';
    }

    public function procesarDevolucion()
    {
        $this->validate([
            'montoDevolucion' => 'required|numeric|min:0|max:' . $this->selectedGarantia->monto_disponible,
            'observacionesDevolucion' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $this->selectedGarantia->marcarComoDevuelta($this->montoDevolucion, $this->observacionesDevolucion);

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Garantía Devuelta!',
                'text' => "La garantía {$this->selectedGarantia->numero_ticket} ha sido devuelta exitosamente.",
                'icon' => 'success'
            ]);

            $this->closeDevolucionModal();

        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Error al procesar la devolución: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    // Método para marcar como perdida
    public function marcarComoPerdida($garantiaId)
    {
        $garantia = Garantia::find($garantiaId);
        
        if (!$garantia || $garantia->estado !== Garantia::ESTADO_RECIBIDA) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Esta garantía no puede marcarse como perdida.',
                'icon' => 'error'
            ]);
            return;
        }

        $this->dispatchBrowserEvent('swal-confirm', [
            'title' => '¿Marcar como Perdida?',
            'text' => '¿Está seguro de marcar la garantía ' . $garantia->numero_ticket . ' como perdida?',
            'method' => 'confirmMarcarPerdida',
            'params' => $garantiaId
        ]);
    }

    public function confirmMarcarPerdida($garantiaId)
    {
        try {
            $garantia = Garantia::find($garantiaId);
            $garantia->marcarComoPerdida('Marcada como perdida por el usuario');

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Marcada como Perdida!',
                'text' => "La garantía {$garantia->numero_ticket} ha sido marcada como perdida.",
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Error al marcar la garantía: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    private function resetForm()
    {
        $this->tipo_garantia_id = '';
        $this->cliente_id = '';
        $this->descripcion = '';
        $this->monto = 0;
        $this->documento_respaldo = '';
        $this->observaciones = '';
        $this->sucursal_id = Auth::user()->sucursal_id ?? Sucursal::first()->id;
    }

    // Listeners para eventos de SweetAlert
    protected $listeners = [
        'confirmMarcarPerdida' => 'confirmMarcarPerdida',
    ];
}