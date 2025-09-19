<?php

namespace App\Http\Livewire\Garantia;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Garantia;
use App\Models\TipoGarantia;
use App\Models\Cliente;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GarantiaController extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    // Filtros y búsqueda
    public $searchTerm = '';
    public $filterEstado = 'TODOS';
    public $filterTipoGarantia = 'TODOS';
    public $filterSucursal = 'TODAS';
    public $filterFecha = '';

    // Modal states
    public $showNewGarantiaModal = false;
    public $showViewGarantiaModal = false;
    public $showDevolucionModal = false;

    // Form data para nueva garantía
    public $cliente_id = '';
    public $tipo_garantia_id = '';
    public $descripcion = '';
    public $monto = 0;
    public $documento_respaldo = '';
    public $fecha_recepcion;
    public $fecha_vencimiento = '';
    public $sucursal_id = '';
    public $observaciones = '';

    // Garantía seleccionada
    public $selectedGarantia = null;

    // Devolución
    public $monto_devuelto = 0;
    public $observaciones_devolucion = '';

    protected $rules = [
        'cliente_id' => 'required|exists:clientes,id',
        'tipo_garantia_id' => 'required|exists:tipos_garantia,id',
        'descripcion' => 'required|string|max:255',
        'monto' => 'required|numeric|min:0',
        'documento_respaldo' => 'nullable|string|max:255',
        'fecha_recepcion' => 'required|date',
        'sucursal_id' => 'required|exists:sucursals,id',
        'observaciones' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->fecha_recepcion = Carbon::now()->format('Y-m-d');
        $this->sucursal_id = Auth::user()->sucursal_id ?? '';
    }

    public function render()
    {
        $garantias = $this->getFilteredGarantias();
        $clientes = Cliente::orderBy('nombres')->get();
        $tiposGarantia = TipoGarantia::activos()->orderBy('nombre')->get();
        $sucursales = Sucursal::orderBy('nombre')->get();
        $estadisticas = $this->getEstadisticas();

        return view('livewire.garantia.garantia', [
            'garantias' => $garantias,
            'clientes' => $clientes,
            'tiposGarantia' => $tiposGarantia,
            'sucursales' => $sucursales,
            'estadisticas' => $estadisticas,
        ])->extends('layouts.theme.app')->section('content');
    }

    private function getFilteredGarantias()
    {
        $query = Garantia::with(['cliente', 'tipoGarantia', 'sucursal', 'usuarioRecepcion']);

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('numero_ticket', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('descripcion', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('cliente', function ($clienteQuery) {
                        $clienteQuery->where('nombres', 'like', '%' . $this->searchTerm . '%')
                            ->orWhere('apellidos', 'like', '%' . $this->searchTerm . '%');
                    })
                    ->orWhereHas('tipoGarantia', function ($tipoQuery) {
                        $tipoQuery->where('nombre', 'like', '%' . $this->searchTerm . '%');
                    });
            });
        }

        if ($this->filterEstado !== 'TODOS') {
            $query->where('estado', $this->filterEstado);
        }

        if ($this->filterTipoGarantia !== 'TODOS') {
            $query->where('tipo_garantia_id', $this->filterTipoGarantia);
        }

        if ($this->filterSucursal !== 'TODAS') {
            $query->where('sucursal_id', $this->filterSucursal);
        }

        if ($this->filterFecha) {
            $query->whereDate('fecha_recepcion', $this->filterFecha);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    private function getEstadisticas()
    {
        $hoy = Carbon::today();

        return [
            'total' => Garantia::count(),
            'recibidas' => Garantia::where('estado', Garantia::ESTADO_RECIBIDA)->count(),
            'devueltas' => Garantia::where('estado', Garantia::ESTADO_DEVUELTA)->count(),
            'vencidas' => Garantia::vencidas()->count(),
            'aplicadas' => Garantia::where('estado', Garantia::ESTADO_APLICADA)->count(),
            'perdidas' => Garantia::where('estado', Garantia::ESTADO_PERDIDA)->count(),
            'venceHoy' => Garantia::where('estado', Garantia::ESTADO_RECIBIDA)
                ->whereDate('fecha_vencimiento', $hoy)
                ->count(),
            'montoTotal' => Garantia::where('estado', Garantia::ESTADO_RECIBIDA)->sum('monto'),
            'montoDisponible' => Garantia::where('estado', Garantia::ESTADO_RECIBIDA)
                ->get()
                ->sum('monto_disponible'),
        ];
    }

    // Filtros
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedFilterEstado()
    {
        $this->resetPage();
    }

    public function updatedFilterTipoGarantia()
    {
        $this->resetPage();
    }

    public function updatedFilterSucursal()
    {
        $this->resetPage();
    }

    public function updatedFilterFecha()
    {
        $this->resetPage();
    }

    // Actualizar fecha de vencimiento cuando cambia el tipo de garantía
    public function updatedTipoGarantiaId()
    {
        if ($this->tipo_garantia_id && $this->fecha_recepcion) {
            $tipoGarantia = TipoGarantia::find($this->tipo_garantia_id);
            if ($tipoGarantia) {
                $this->fecha_vencimiento = $tipoGarantia->calcularFechaVencimiento($this->fecha_recepcion)->format('Y-m-d');
            }
        }
    }

    public function updatedFechaRecepcion()
    {
        if ($this->tipo_garantia_id && $this->fecha_recepcion) {
            $tipoGarantia = TipoGarantia::find($this->tipo_garantia_id);
            if ($tipoGarantia) {
                $this->fecha_vencimiento = $tipoGarantia->calcularFechaVencimiento($this->fecha_recepcion)->format('Y-m-d');
            }
        }
    }

    // Modal nueva garantía
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

    public function guardar()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Validar el monto según el tipo de garantía
            $tipoGarantia = TipoGarantia::find($this->tipo_garantia_id);
            if ($tipoGarantia->requiere_monto && !$tipoGarantia->validarMonto($this->monto)) {
                throw new \Exception("El monto no cumple con los rangos establecidos para este tipo de garantía: {$tipoGarantia->rango_monto}");
            }

            $garantia = Garantia::create([
                'tipo_garantia_id' => $this->tipo_garantia_id,
                'cliente_id' => $this->cliente_id,
                'descripcion' => $this->descripcion,
                'monto' => $this->monto,
                'documento_respaldo' => $this->documento_respaldo,
                'estado' => Garantia::ESTADO_RECIBIDA,
                'fecha_recepcion' => $this->fecha_recepcion,
                'fecha_vencimiento' => $this->fecha_vencimiento,
                'usuario_recepcion' => Auth::id(),
                'sucursal_id' => $this->sucursal_id,
                'observaciones' => $this->observaciones,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Garantía Registrada!',
                'text' => "Garantía registrada con número de ticket: {$garantia->numero_ticket}",
                'icon' => 'success'
            ]);

            $this->closeNewGarantiaModal();
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('errorModal', 'Error al registrar la garantía: ' . $e->getMessage());
        }
    }

    // Ver garantía
    public function viewGarantia($garantiaId)
    {
        $this->selectedGarantia = Garantia::with(['cliente', 'tipoGarantia', 'sucursal', 'usuarioRecepcion', 'usuarioDevolucion', 'alquileres'])
            ->find($garantiaId);
        $this->showViewGarantiaModal = true;
    }

    public function closeViewGarantiaModal()
    {
        $this->showViewGarantiaModal = false;
        $this->selectedGarantia = null;
    }

    // Devolución
    public function openDevolucionModal($garantiaId)
    {
        $this->selectedGarantia = Garantia::find($garantiaId);
        
        if ($this->selectedGarantia->estado !== Garantia::ESTADO_RECIBIDA) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Esta garantía no puede ser devuelta en su estado actual.',
                'icon' => 'error'
            ]);
            return;
        }

        $this->monto_devuelto = $this->selectedGarantia->monto_disponible;
        $this->observaciones_devolucion = '';
        $this->showDevolucionModal = true;
    }

    public function closeDevolucionModal()
    {
        $this->showDevolucionModal = false;
        $this->selectedGarantia = null;
        $this->monto_devuelto = 0;
        $this->observaciones_devolucion = '';
    }

    public function procesarDevolucion()
    {
        $this->validate([
            'monto_devuelto' => 'required|numeric|min:0|max:' . $this->selectedGarantia->monto_disponible,
        ]);

        try {
            DB::beginTransaction();

            $this->selectedGarantia->marcarComoDevuelta($this->monto_devuelto, $this->observaciones_devolucion);

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Devolución Procesada!',
                'text' => 'La garantía ha sido devuelta exitosamente.',
                'icon' => 'success'
            ]);

            $this->closeDevolucionModal();
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al procesar la devolución: ' . $e->getMessage());
        }
    }

    // Marcar como perdida
    public function marcarComoPerdida($garantiaId, $observaciones = null)
    {
        try {
            $garantia = Garantia::find($garantiaId);
            
            if ($garantia->estado !== Garantia::ESTADO_RECIBIDA) {
                $this->dispatchBrowserEvent('swal', [
                    'title' => 'Error',
                    'text' => 'Esta garantía no puede marcarse como perdida en su estado actual.',
                    'icon' => 'error'
                ]);
                return;
            }

            $garantia->marcarComoPerdida($observaciones);

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Garantía Marcada como Perdida!',
                'text' => 'La garantía ha sido marcada como perdida.',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Error al marcar como perdida: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    // Actualizar estados vencidos
    public function actualizarEstadosVencidos()
    {
        try {
            $garantiasVencidas = Garantia::where('estado', Garantia::ESTADO_RECIBIDA)
                ->where('fecha_vencimiento', '<', now()->toDateString())
                ->get();

            foreach ($garantiasVencidas as $garantia) {
                $garantia->actualizarEstado();
            }

            $count = $garantiasVencidas->count();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Estados Actualizados!',
                'text' => "Se actualizaron {$count} garantías vencidas.",
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Error al actualizar estados: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    private function resetForm()
    {
        $this->cliente_id = '';
        $this->tipo_garantia_id = '';
        $this->descripcion = '';
        $this->monto = 0;
        $this->documento_respaldo = '';
        $this->fecha_recepcion = Carbon::now()->format('Y-m-d');
        $this->fecha_vencimiento = '';
        $this->observaciones = '';
        $this->sucursal_id = Auth::user()->sucursal_id ?? '';
    }
}
