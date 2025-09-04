<?php

namespace App\Http\Livewire\Alquiler;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Alquiler;
use App\Models\Cliente;
use App\Models\Reserva;
use App\Models\Sucursal;
use App\Models\UnidadEducativa;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AlquilerController extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filtros y búsqueda
    public $searchTerm = '';
    public $filterEstado = 'TODOS';
    public $filterEstadoPago = 'TODOS';
    public $filterSucursal = 'TODAS';

    // Modal states
    public $showNewAlquilerModal = false;
    public $showViewAlquilerModal = false;
    public $showDevolucionModal = false;
    public $showPagoModal = false;

    // Form data para nuevo alquiler
    public $cliente_id = '';
    public $reserva_id = '';
    public $unidad_educativa_id = '';
    public $fecha_alquiler;
    public $hora_entrega = '09:00';
    public $fecha_devolucion_programada;
    public $hora_devolucion_programada = '18:00';
    public $dias_alquiler = 1;
    public $lugar_entrega = '';
    public $lugar_devolucion = '';
    public $observaciones = '';
    public $condiciones_especiales = '';
    public $sucursal_id = '';
    public $anticipo = 0;

    // Productos seleccionados
    public $selectedProducts = [];
    public $currentProductId = '';
    public $currentQuantity = 1;

    // Alquiler seleccionado
    public $selectedAlquiler = null;

    // Devolución
    public $fecha_devolucion_real;
    public $penalizacion = 0;
    public $observaciones_devolucion = '';

    // Pago
    public $monto_pago = 0;
    public $referencia_pago = '';
    public $observaciones_pago = '';

    protected $rules = [
        'cliente_id' => 'required|exists:clientes,id',
        'fecha_alquiler' => 'required|date',
        'fecha_devolucion_programada' => 'required|date|after:fecha_alquiler',
        'dias_alquiler' => 'required|integer|min:1',
        'sucursal_id' => 'required|exists:sucursals,id',
        'anticipo' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->fecha_alquiler = Carbon::now()->format('Y-m-d');
        $this->fecha_devolucion_programada = Carbon::now()->addDays(1)->format('Y-m-d');
        $this->sucursal_id = Auth::user()->sucursal_id ?? '';
    }

    public function render()
    {
        $alquileres = $this->getFilteredAlquileres();
        $clientes = Cliente::orderBy('nombres')->get();
        $reservas = Reserva::where('estado', 'CONFIRMADA')->orderBy('numero_reserva', 'desc')->get();
        $sucursales = Sucursal::orderBy('nombre')->get();
        $unidadesEducativas = UnidadEducativa::orderBy('nombre')->get();
        $productos = Producto::all();
        $estadisticas = $this->getEstadisticas();
        
        // Garantías disponibles para asignar
        $garantiasDisponibles = \App\Models\Garantia::with('tipoGarantia', 'cliente')
            ->leftJoin('alquileres', 'garantias.id', '=', 'alquileres.garantia_id')
            ->where('garantias.estado', \App\Models\Garantia::ESTADO_RECIBIDA)
            ->whereNull('alquileres.id') // Sin asignar a ningún alquiler
            ->select('garantias.*')
            ->orderBy('garantias.fecha_recepcion', 'desc')
            ->get();
        
        $tiposGarantia = \App\Models\TipoGarantia::activos()->orderBy('nombre')->get();

        return view('livewire.alquiler.alquiler', [
            'alquileres' => $alquileres,
            'clientes' => $clientes,
            'reservas' => $reservas,
            'sucursales' => $sucursales,
            'unidadesEducativas' => $unidadesEducativas,
            'productos' => $productos,
            'estadisticas' => $estadisticas,
            'garantiasDisponibles' => $garantiasDisponibles,
            'tiposGarantia' => $tiposGarantia,
        ])->extends('layouts.theme.app')->section('content');
    }

    private function getFilteredAlquileres()
    {
        $query = Alquiler::with(['cliente', 'sucursal', 'reserva', 'usuarioCreacion', 'unidadEducativa', 'garantia.tipoGarantia']);

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('numero_contrato', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('cliente', function ($clienteQuery) {
                        $clienteQuery->where('nombres', 'like', '%' . $this->searchTerm . '%')
                            ->orWhere('apellidos', 'like', '%' . $this->searchTerm . '%');
                    })
                    ->orWhereHas('reserva', function ($reservaQuery) {
                        $reservaQuery->where('numero_reserva', 'like', '%' . $this->searchTerm . '%');
                    });
            });
        }

        if ($this->filterEstado !== 'TODOS') {
            if ($this->filterEstado === 'VENCIDO') {
                $query->where(function ($q) {
                    $q->where('estado', 'VENCIDO')
                        ->orWhere(function ($subQuery) {
                            $subQuery->where('estado', 'ACTIVO')
                                    ->where('fecha_devolucion_programada', '<', now());
                        });
                });
            } else {
                $query->where('estado', $this->filterEstado);
            }
        }

        if ($this->filterEstadoPago !== 'TODOS') {
            $query->where('estado_pago', $this->filterEstadoPago);
        }

        if ($this->filterSucursal !== 'TODAS') {
            $query->where('sucursal_id', $this->filterSucursal);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    private function getEstadisticas()
    {
        $hoy = Carbon::today();

        return [
            'total' => Alquiler::count(),
            'activos' => Alquiler::where('estado', 'ACTIVO')->count(),
            'vencidos' => Alquiler::where('estado', 'VENCIDO')
                ->orWhere(function ($q) use ($hoy) {
                    $q->where('estado', 'ACTIVO')
                      ->where('fecha_devolucion_programada', '<', $hoy);
                })->count(),
            'venceHoy' => Alquiler::where('estado', 'ACTIVO')
                ->whereDate('fecha_devolucion_programada', $hoy)
                ->count(),
            'devueltos' => Alquiler::where('estado', 'DEVUELTO')->count(),
            'totalIngresos' => Alquiler::sum('total'),
            'saldosPendientes' => Alquiler::where('estado_pago', '!=', 'PAGADO')->sum('saldo_pendiente'),
        ];
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedFilterEstado()
    {
        $this->resetPage();
    }

    public function updatedFilterEstadoPago()
    {
        $this->resetPage();
    }

    public function updatedFilterSucursal()
    {
        $this->resetPage();
    }

    public function updatedDiasAlquiler()
    {
        if ($this->dias_alquiler > 0 && $this->fecha_alquiler) {
            $this->fecha_devolucion_programada = Carbon::parse($this->fecha_alquiler)
                ->addDays($this->dias_alquiler)
                ->format('Y-m-d');
        }
    }

    public function updatedFechaAlquiler()
    {
        if ($this->dias_alquiler > 0 && $this->fecha_alquiler) {
            $this->fecha_devolucion_programada = Carbon::parse($this->fecha_alquiler)
                ->addDays($this->dias_alquiler)
                ->format('Y-m-d');
        }
    }

    public function openNewAlquilerModal()
    {
        $this->resetForm();
        $this->showNewAlquilerModal = true;
    }

    public function closeNewAlquilerModal()
    {
        $this->showNewAlquilerModal = false;
        $this->resetForm();
    }

    public function saveNewAlquiler()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $numeroContrato = 'ALQ-' . date('Y') . '-' . str_pad(Alquiler::count() + 1, 4, '0', STR_PAD_LEFT);

            $subtotal = $this->calculateSubtotal();
            $total = $subtotal;
            $saldoPendiente = $total - $this->anticipo;

            $alquiler = Alquiler::create([
                'sucursal_id' => $this->sucursal_id,
                'numero_contrato' => $numeroContrato,
                'reserva_id' => $this->reserva_id ?: null,
                'cliente_id' => $this->cliente_id,
                'unidad_educativa_id' => $this->unidad_educativa_id ?: null,
                'tipo_pago_id' => 1, // Asumiendo tipo de pago por defecto
                'fecha_alquiler' => $this->fecha_alquiler,
                'hora_entrega' => $this->hora_entrega,
                'fecha_devolucion_programada' => $this->fecha_devolucion_programada,
                'hora_devolucion_programada' => $this->hora_devolucion_programada,
                'dias_alquiler' => $this->dias_alquiler,
                'subtotal' => $subtotal,
                'total' => $total,
                'anticipo' => $this->anticipo,
                'saldo_pendiente' => $saldoPendiente,
                'estado' => 'ACTIVO',
                'estado_pago' => $this->anticipo >= $total ? 'PAGADO' : ($this->anticipo > 0 ? 'PARCIAL' : 'PENDIENTE'),
                'lugar_entrega' => $this->lugar_entrega,
                'lugar_devolucion' => $this->lugar_devolucion,
                'observaciones' => $this->observaciones,
                'condiciones_especiales' => $this->condiciones_especiales,
                'usuario_creacion' => Auth::id(),
            ]);

            // Si viene de una reserva, actualizar el estado a CONFIRMADA y transferir anticipo
            if ($this->reserva_id) {
                $reserva = Reserva::find($this->reserva_id);
                $reserva->update(['estado' => 'CONFIRMADA']);
                
                // Transferir anticipo de reserva al alquiler
                if ($reserva->anticipo > 0) {
                    $alquiler->update([
                        'anticipo_reserva' => $reserva->anticipo,
                        'anticipo' => $this->anticipo + $reserva->anticipo, // Sumar anticipo de reserva + adicional
                        'saldo_pendiente' => $alquiler->total - ($this->anticipo + $reserva->anticipo),
                    ]);
                    
                    // Actualizar estado de pago
                    $alquiler->actualizarEstadoPago();
                }
            }

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Alquiler Creado!',
                'text' => 'El alquiler ha sido creado exitosamente.',
                'icon' => 'success'
            ]);

            $this->closeNewAlquilerModal();
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('errorModal', 'Error al crear el alquiler: ' . $e->getMessage());
        }
    }

    public function viewAlquiler($alquilerId)
    {
        $this->selectedAlquiler = Alquiler::with(['cliente', 'sucursal', 'reserva', 'usuarioCreacion', 'unidadEducativa'])
            ->find($alquilerId);
        $this->showViewAlquilerModal = true;
    }

    public function closeViewAlquilerModal()
    {
        $this->showViewAlquilerModal = false;
        $this->selectedAlquiler = null;
    }

    public function openDevolucionModal($alquilerId)
    {
        $this->selectedAlquiler = Alquiler::find($alquilerId);
        $this->fecha_devolucion_real = now()->format('Y-m-d\TH:i');
        $this->penalizacion = 0;
        $this->observaciones_devolucion = '';
        $this->showDevolucionModal = true;
    }

    public function closeDevolucionModal()
    {
        $this->showDevolucionModal = false;
        $this->selectedAlquiler = null;
    }

    public function procesarDevolucion()
    {
        $this->validate([
            'fecha_devolucion_real' => 'required|date',
            'penalizacion' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $this->selectedAlquiler->update([
                'fecha_devolucion_real' => $this->fecha_devolucion_real,
                'penalizacion' => $this->penalizacion,
                'estado' => 'DEVUELTO',
                'observaciones' => $this->selectedAlquiler->observaciones . "\nDevolución: " . $this->observaciones_devolucion,
                'usuario_devolucion' => Auth::id(),
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Devolución Procesada!',
                'text' => 'La devolución ha sido registrada exitosamente.',
                'icon' => 'success'
            ]);

            $this->closeDevolucionModal();
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al procesar la devolución: ' . $e->getMessage());
        }
    }

    public function openPagoModal($alquilerId)
    {
        $this->selectedAlquiler = Alquiler::find($alquilerId);
        $this->monto_pago = $this->selectedAlquiler->saldo_pendiente;
        $this->referencia_pago = '';
        $this->observaciones_pago = '';
        $this->showPagoModal = true;
    }

    public function closePagoModal()
    {
        $this->showPagoModal = false;
        $this->selectedAlquiler = null;
    }

    public function procesarPago()
    {
        $this->validate([
            'monto_pago' => 'required|numeric|min:0|max:' . $this->selectedAlquiler->saldo_pendiente,
        ]);

        try {
            DB::beginTransaction();

            $nuevoSaldo = $this->selectedAlquiler->saldo_pendiente - $this->monto_pago;
            $estadoPago = $nuevoSaldo <= 0 ? 'PAGADO' : 'PARCIAL';

            $this->selectedAlquiler->update([
                'saldo_pendiente' => $nuevoSaldo,
                'estado_pago' => $estadoPago,
                'referencia_pago' => $this->referencia_pago,
                'observaciones' => $this->selectedAlquiler->observaciones . "\nPago: Bs. " . $this->monto_pago . " - " . $this->observaciones_pago,
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Pago Registrado!',
                'text' => 'El pago ha sido registrado exitosamente.',
                'icon' => 'success'
            ]);

            $this->closePagoModal();
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al registrar el pago: ' . $e->getMessage());
        }
    }

    public function addProductToAlquiler()
    {
        if (!$this->currentProductId || $this->currentQuantity <= 0) {
            return;
        }

        $producto = Producto::query()
            ->select('productos.*', 'sps.stock_actual', 'sps.precio_alquiler_sucursal')
            ->join('stock_por_sucursals as sps', 'productos.id', '=', 'sps.producto_id')
            ->where('productos.id', $this->currentProductId)
            ->where('sps.sucursal_id', $this->sucursal_id)
            ->first();

        if (!$producto) {
            session()->flash('errorModal', 'Producto no encontrado en esta sucursal.');
            return;
        }

        $existingIndex = collect($this->selectedProducts)->search(function ($item) {
            return $item['id'] == $this->currentProductId;
        });

        $cantidadExistente = $existingIndex !== false ? $this->selectedProducts[$existingIndex]['cantidad'] : 0;
        $cantidadTotal = $cantidadExistente + $this->currentQuantity;

        if ($cantidadTotal > $producto->stock_actual) {
            session()->flash('errorModal', 'Stock insuficiente para esta cantidad.');
            return;
        }

        $precio = $producto->precio_alquiler_sucursal ?? 0;

        if ($existingIndex !== false) {
            $this->selectedProducts[$existingIndex]['cantidad'] = $cantidadTotal;
            $this->selectedProducts[$existingIndex]['subtotal'] = $cantidadTotal * $precio;
        } else {
            $this->selectedProducts[] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'cantidad' => $this->currentQuantity,
                'precio_unitario' => $precio,
                'subtotal' => $this->currentQuantity * $precio,
            ];
        }

        $this->currentProductId = '';
        $this->currentQuantity = 1;
    }

    public function removeProductFromAlquiler($index)
    {
        unset($this->selectedProducts[$index]);
        $this->selectedProducts = array_values($this->selectedProducts);
    }

    private function calculateSubtotal()
    {
        return collect($this->selectedProducts)->sum('subtotal');
    }

    private function resetForm()
    {
        $this->cliente_id = '';
        $this->reserva_id = '';
        $this->unidad_educativa_id = '';
        $this->fecha_alquiler = Carbon::now()->format('Y-m-d');
        $this->hora_entrega = '09:00';
        $this->fecha_devolucion_programada = Carbon::now()->addDays(1)->format('Y-m-d');
        $this->hora_devolucion_programada = '18:00';
        $this->dias_alquiler = 1;
        $this->lugar_entrega = '';
        $this->lugar_devolucion = '';
        $this->observaciones = '';
        $this->condiciones_especiales = '';
        $this->anticipo = 0;
        $this->selectedProducts = [];
        $this->currentProductId = '';
        $this->currentQuantity = 1;
    }
}
