<?php

namespace App\Http\Livewire\Reservas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\Usuario;
use App\Models\ReservaDetalle;
use App\Models\Caja;
use App\Models\MovimientoCaja;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReservasController extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    // Filtros y búsqueda
    public $searchTerm = '';
    public $filterEstado = 'TODOS';
    public $filterTipo = 'TODOS';
    public $filterSucursal = 'TODAS';

    // Modal states

    public $showNewReservaModal = false;
    public $showViewReservaModal = false;
    public $showConfirmReservaModal = false;
    public $showConvertToAlquilerModal = false;
    // Form data para nueva reserva
    public $cliente_id = '';
    public $tipo_reserva = 'ALQUILER';
    public $fecha_reserva;
    public $fecha_vencimiento;
    public $anticipo = 0;
    public $observaciones = '';
    public $sucursal_id = '';
    public $caja_id = '';
    public $metodo_pago = 'EFECTIVO';

    // Productos seleccionados
    public $selectedProducts = [];
    public $currentProductId = '';
    public $currentQuantity = 1;

    // Reserva seleccionada para ver/confirmar
    public $selectedReserva = null;

    // Confirmación
    public $montoAdicional = 0;
    public $observacionesConfirmacion = '';
    public $caja_confirmacion = '';
    public $metodo_pago_confirmacion = 'EFECTIVO';
    
    // Conversión a alquiler
    public $fechaAlquiler;
    public $fechaDevolucion;
    public $diasAlquiler = 3;
    public $anticipoAdicional = 0;
    public $requiereDeposito = false;
    public $depositoGarantia = 0;
    public $observacionesAlquiler = '';
    public $garantia_id = '';

    protected $rules = [
        'cliente_id' => 'required|exists:clientes,id',
        'tipo_reserva' => 'required|in:ALQUILER,VENTA',
        'fecha_reserva' => 'required|date',
        'fecha_vencimiento' => 'required|date|after:fecha_reserva',
        'anticipo' => 'required|numeric|min:0',
        'sucursal_id' => 'required|exists:sucursals,id',
        'caja_id' => 'required_if:anticipo,>,0|exists:cajas,id',
        'metodo_pago' => 'required|string',
        'selectedProducts' => 'required|array|min:1',
    ];

    public function mount()
    {
        $this->fecha_reserva = Carbon::now()->format('Y-m-d');
        $this->fecha_vencimiento = Carbon::now()->addDays(7)->format('Y-m-d');
        $this->sucursal_id = Auth::user()->sucursal_id ?? '';
    }

    public function render()
    {
        $reservas = $this->getFilteredReservas();
        $clientes = Cliente::orderBy('nombres')->get();
        $productos =  Producto::all();
        $sucursales = Sucursal::orderBy('nombre')->get();
        $estadisticas = $this->getEstadisticas();
        
        // Garantías disponibles para conversión a alquiler
        $garantiasDisponibles = collect();
        if ($this->selectedReserva) {
            $garantiasDisponibles = \App\Models\Garantia::with('tipoGarantia', 'cliente')
                ->leftJoin('alquileres', 'garantias.id', '=', 'alquileres.garantia_id')
                ->where('garantias.estado', \App\Models\Garantia::ESTADO_RECIBIDA)
                ->where('garantias.cliente_id', $this->selectedReserva->cliente_id)
                ->whereNull('alquileres.id') // Sin asignar a ningún alquiler
                ->select('garantias.*')
                ->orderBy('garantias.fecha_recepcion', 'desc')
                ->get();
        }

        $cajas = Caja::where('estado', 'ABIERTA')->orderBy('nombre')->get();

        return view('livewire.reservas.reservas', [
            'reservas' => $reservas,
            'clientes' => $clientes,
            'productos' => $productos,
            'sucursales' => $sucursales,
            'estadisticas' => $estadisticas,
            'garantiasDisponibles' => $garantiasDisponibles,
            'cajas' => $cajas,
        ])->extends('layouts.theme.modern-app')->section('content');
    }

    private function getFilteredReservas()
    {
        $query = Reserva::with(['cliente', 'sucursal', 'usuarioCreacion', 'detalles.producto']);

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('numero_reserva', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('cliente', function ($clienteQuery) {
                        $clienteQuery->where('nombres', 'like', '%' . $this->searchTerm . '%')
                            ->orWhere('apellidos', 'like', '%' . $this->searchTerm . '%');
                    });
            });
        }

        if ($this->filterEstado !== 'TODOS') {
            $hoy = Carbon::today();
            $dosDiasDespues = $hoy->copy()->addDays(2);

            if ($this->filterEstado === 'PROXIMA_VENCER') {
                $query->whereBetween('fecha_vencimiento', [$hoy, $dosDiasDespues])
                    ->where('fecha_vencimiento', '>=', $hoy);
            } elseif ($this->filterEstado === 'VENCIDA') {
                $query->where('fecha_vencimiento', '<', $hoy);
            } else {
                $query->where('estado', $this->filterEstado);
            }
        }


        if ($this->filterTipo !== 'TODOS') {
            $query->where('tipo_reserva', $this->filterTipo);
        }

        if ($this->filterSucursal !== 'TODAS') {
            $query->where('sucursal_id', $this->filterSucursal);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    private function getEstadisticas()
    {
        $hoy = Carbon::today();
        $dosDiasDespues = $hoy->copy()->addDays(2);

        return [
            'total' => Reserva::count(),
            'activas' => Reserva::where('estado', 'ACTIVA')->count(),

            // Próximas a vencer: fecha dentro de los próximos 2 días, sin incluir vencidas
            'proximasVencer' => Reserva::whereBetween('fecha_vencimiento', [$hoy, $dosDiasDespues])
                ->where('fecha_vencimiento', '>=', $hoy)
                ->count(),

            // Vencidas: fecha anterior a hoy
            'vencidas' => Reserva::where('fecha_vencimiento', '<', $hoy)->count(),

            'confirmadas' => Reserva::where('estado', 'CONFIRMADA')->count(),
            'montoTotalEfectivo' => Reserva::where('estado', 'ACTIVA')->sum('anticipo'),
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

    public function updatedFilterTipo()
    {
        $this->resetPage();
    }

    public function updatedFilterSucursal()
    {
        $this->resetPage();
    }

    public function openNewReservaModal()
    {
        $this->resetForm();
        $this->showNewReservaModal = true;
        $this->emit('showModal');
    }

    public function closeNewReservaModal()
    {
        $this->showNewReservaModal = false;
        $this->resetForm();
    }

    public function addProductToReserva()
    {
        if (!$this->currentProductId || $this->currentQuantity <= 0) {
            return;
        }

        $producto = Producto::query()
            ->select('productos.*', 'sps.stock_actual', 'sps.precio_venta_sucursal', 'sps.precio_alquiler_sucursal')
            ->join('stock_por_sucursals as sps', 'productos.id', '=', 'sps.producto_id')
            ->where('productos.id', $this->currentProductId)
            ->where('sps.sucursal_id', $this->sucursal_id)
            ->first();

        if (!$producto) {
            session()->flash('errorModal', 'Producto no encontrado.');
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

        $precio = $this->tipo_reserva === 'ALQUILER' ? $producto->precio_alquiler_sucursal : $producto->precio_venta_sucursal;

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




    public function removeProductFromReserva($index)
    {
        unset($this->selectedProducts[$index]);
        $this->selectedProducts = array_values($this->selectedProducts);
    }

    public function calculateTotal()
    {
        return collect($this->selectedProducts)->sum('subtotal');
    }
    public $reservaToPrint;

    public function printReserva($reservaId)
    {
        // Cargar reserva con relaciones necesarias
        $this->reservaToPrint = Reserva::with([
            'cliente',                // Para cliente_nombre, cliente_telefono
            'sucursal',               // Para sucursal_nombre
            'usuarioCreacion',        // Para usuario_creacion_nombre
            'detalles.producto'       // Para productos reservados con info
        ])->find($reservaId);

        if (!$this->reservaToPrint) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Reserva no encontrada']);
            return;
        }

        // Preparar array para enviar a JS con estructura plana y solo datos necesarios
        $reservaData = [
            'numero_reserva' => $this->reservaToPrint->numero_reserva,
            'tipo_reserva' => $this->reservaToPrint->tipo_reserva,
            'estado' => $this->reservaToPrint->estado,
            'fecha_reserva' => $this->reservaToPrint->fecha_reserva->format('d/m/Y'),
            'fecha_vencimiento' => $this->reservaToPrint->fecha_vencimiento->format('d/m/Y'),
            'sucursal_nombre' => $this->reservaToPrint->sucursal->nombre ?? '',
            'usuario_creacion_nombre' => $this->reservaToPrint->usuarioCreacion->nombres ?? '',
            'cliente_nombre' => $this->reservaToPrint->cliente->nombres ?? '',
            'cliente_telefono' => $this->reservaToPrint->cliente->telefono ?? '',
            'productos' => $this->reservaToPrint->detalles->map(function ($detalle) {
                return [
                    'nombre' => $detalle->producto->nombre ?? 'Producto',
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->precio_unitario,
                    'subtotal' => $detalle->subtotal,
                ];
            }),
            'total_estimado' => $this->reservaToPrint->total,
            'monto_efectivo' => $this->reservaToPrint->anticipo,
            'observaciones' => $this->reservaToPrint->observaciones,
        ];
        // dd($reservaData);
        $this->emit('printReservaEvent', json_encode($reservaData));
    }

    public function saveNewReserva()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Generar número de reserva
            $numeroReserva = 'RES-' . date('Y') . '-' . str_pad(Reserva::count() + 1, 3, '0', STR_PAD_LEFT);

            // Crear reserva
            $reserva = Reserva::create([
                'numero_reserva' => $numeroReserva,
                'cliente_id' => $this->cliente_id,
                'tipo_reserva' => $this->tipo_reserva,
                'fecha_reserva' => $this->fecha_reserva,
                'fecha_vencimiento' => $this->fecha_vencimiento,
                'anticipo' => $this->anticipo,
                'subtotal' => $this->calculateTotal(),
                'total' => $this->calculateTotal(),
                'observaciones' => $this->observaciones,
                'sucursal_id' => $this->sucursal_id,
                'usuario_creacion_id' => Auth::id(),
                'estado' => 'ACTIVA',
            ]);

            // Crear detalles de reserva y actualizar stock en stock_por_sucursals
            foreach ($this->selectedProducts as $producto) {
                ReservaDetalle::create([
                    'reserva_id' => $reserva->id,
                    'producto_id' => $producto['id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio_unitario'],
                    'subtotal' => $producto['subtotal'],
                ]);

                // Actualizar stock reservado
                DB::table('stock_por_sucursals')
                    ->where('producto_id', $producto['id'])
                    ->where('sucursal_id', $this->sucursal_id)
                    ->increment('stock_reservado', $producto['cantidad']);

                // Actualizar stock disponible (stock_actual)
                DB::table('stock_por_sucursals')
                    ->where('producto_id', $producto['id'])
                    ->where('sucursal_id', $this->sucursal_id)
                    ->decrement('stock_actual', $producto['cantidad']);
            }

            // Registrar movimiento en caja si hay anticipo
            if ($this->anticipo > 0 && $this->caja_id) {
                $caja = Caja::find($this->caja_id);
                if ($caja && $caja->estado === 'ABIERTA') {
                    $caja->registrarMovimiento(
                        MovimientoCaja::TIPO_INGRESO,
                        $this->anticipo,
                        "Anticipo reserva {$numeroReserva}",
                        MovimientoCaja::CATEGORIA_VARIOS,
                        Auth::id(),
                        "Cliente: {$reserva->cliente->nombres} - Tipo: {$this->tipo_reserva}"
                    );
                }
            }

            DB::commit();

            session()->flash('success', 'Reserva creada exitosamente y registrada en caja.');
            $this->closeNewReservaModal();
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('errorModal', 'Error al crear la reserva: ' . $e->getMessage());
        }
    }


    public function viewReserva($reservaId)
    {
        $this->selectedReserva = Reserva::with(['cliente', 'sucursal', 'usuarioCreacion', 'detalles.producto'])
            ->find($reservaId);
        $this->showViewReservaModal = true;
    }

    public function closeViewReservaModal()
    {
        $this->showViewReservaModal = false;
        $this->selectedReserva = null;
    }

    public function confirmReserva($reservaId)
    {
        $this->selectedReserva = Reserva::with(['cliente', 'detalles.producto'])->find($reservaId);
        $this->montoAdicional = 0;
        $this->observacionesConfirmacion = '';
        $this->showConfirmReservaModal = true;
    }

    public function closeConfirmReservaModal()
    {
        $this->showConfirmReservaModal = false;
        $this->selectedReserva = null;
        $this->montoAdicional = 0;
        $this->observacionesConfirmacion = '';
        $this->caja_confirmacion = '';
        $this->metodo_pago_confirmacion = 'EFECTIVO';
    }

    public function saveConfirmReserva()
    {
        $rules = [
            'montoAdicional' => 'required|numeric|min:0',
        ];

        // Si hay monto adicional, validar caja
        if ($this->montoAdicional > 0) {
            $rules['caja_confirmacion'] = 'required|exists:cajas,id';
        }

        $this->validate($rules);

        try {
            DB::beginTransaction();

            $this->selectedReserva->update([
                'estado' => 'CONFIRMADA',
                'anticipo' => $this->selectedReserva->anticipo + $this->montoAdicional,
                'observaciones' => $this->selectedReserva->observaciones . "\n" . $this->observacionesConfirmacion,
            ]);

            // Registrar pago adicional en caja seleccionada
            if ($this->montoAdicional > 0 && $this->caja_confirmacion) {
                \Log::info('Intentando registrar pago adicional de reserva', [
                    'monto' => $this->montoAdicional,
                    'caja_id' => $this->caja_confirmacion,
                    'reserva' => $this->selectedReserva->numero_reserva
                ]);

                $caja = Caja::find($this->caja_confirmacion);
                if ($caja && $caja->estado === 'ABIERTA') {
                    $caja->registrarMovimiento(
                        MovimientoCaja::TIPO_INGRESO,
                        $this->montoAdicional,
                        "Pago adicional reserva {$this->selectedReserva->numero_reserva}",
                        MovimientoCaja::CATEGORIA_VARIOS,
                        Auth::id(),
                        "Confirmación - Cliente: {$this->selectedReserva->cliente->nombres} - {$this->metodo_pago_confirmacion}"
                    );
                    \Log::info('Pago registrado exitosamente en caja');
                } else {
                    \Log::warning('Caja no encontrada o no está abierta', [
                        'caja_id' => $this->caja_confirmacion,
                        'caja_estado' => $caja ? $caja->estado : 'no_encontrada'
                    ]);
                }
            }

            // Si es alquiler, crear el alquiler automáticamente
            if ($this->selectedReserva->tipo_reserva === 'ALQUILER') {
                $this->createAlquilerFromReserva($this->selectedReserva);
            }

            DB::commit();

            session()->flash('success', 'Reserva confirmada exitosamente.');
            $this->closeConfirmReservaModal();
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al confirmar la reserva: ' . $e->getMessage());
        }
    }

    public function cancelReserva($reservaId)
    {
        try {
            DB::beginTransaction();

            $reserva = Reserva::with('detalles')->find($reservaId);
            $reserva->update(['estado' => 'CANCELADA']);

            // Liberar stock reservado y aumentar stock actual en stock_por_sucursals
            foreach ($reserva->detalles as $detalle) {
                DB::table('stock_por_sucursals')
                    ->where('producto_id', $detalle->producto_id)
                    ->where('sucursal_id', $reserva->sucursal_id)
                    ->decrement('stock_reservado', $detalle->cantidad);

                DB::table('stock_por_sucursals')
                    ->where('producto_id', $detalle->producto_id)
                    ->where('sucursal_id', $reserva->sucursal_id)
                    ->increment('stock_actual', $detalle->cantidad);
            }

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Reserva Cancelada!',
                'text' => 'La reserva ha sido cancelada exitosamente y el stock ha sido liberado.',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al cancelar la reserva: ' . $e->getMessage());
        }
    }


    private function createAlquilerFromReserva($reserva)
    {
        // Usar el método del modelo para convertir reserva a alquiler
        $alquiler = $reserva->convertirAAlquiler([
            'fecha_alquiler' => now()->toDateString(),
            'fecha_devolucion_programada' => now()->addDays(3)->toDateString(),
            'dias_alquiler' => 3,
            'usuario_creacion' => Auth::id(),
        ]);

        return $alquiler;
    }

    private function resetForm()
    {
        $this->cliente_id = '';
        $this->tipo_reserva = 'ALQUILER';
        $this->fecha_reserva = Carbon::now()->format('Y-m-d');
        $this->fecha_vencimiento = Carbon::now()->addDays(7)->format('Y-m-d');
        $this->anticipo = 0;
        $this->observaciones = '';
        $this->caja_id = '';
        $this->metodo_pago = 'EFECTIVO';
        $this->selectedProducts = [];
        $this->currentProductId = '';
        $this->currentQuantity = 1;
    }

    public function getSaldoPendienteProperty()
    {
        if (!$this->selectedReserva) return 0;
        return $this->selectedReserva->total - $this->selectedReserva->anticipo;
    }

    public function getSaldoFinalProperty()
    {
        return $this->getSaldoPendienteProperty() - $this->montoAdicional;
    }

    // Métodos para conversión a alquiler
    public function convertToAlquiler($reservaId)
    {
        $this->selectedReserva = Reserva::with(['cliente', 'detalles.producto'])->find($reservaId);
        
        if (!$this->selectedReserva || !$this->selectedReserva->puedeConvertirseAAlquiler()) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Esta reserva no puede convertirse a alquiler.',
                'icon' => 'error'
            ]);
            return;
        }

        // Inicializar valores por defecto
        $this->fechaAlquiler = now()->format('Y-m-d');
        $this->fechaDevolucion = now()->addDays(3)->format('Y-m-d');
        $this->diasAlquiler = 3;
        $this->anticipoAdicional = 0;
        $this->requiereDeposito = false;
        $this->depositoGarantia = 0;
        $this->garantia_id = '';
        
        $this->showConvertToAlquilerModal = true;
    }

    public function closeConvertToAlquilerModal()
    {
        $this->showConvertToAlquilerModal = false;
        $this->selectedReserva = null;
        $this->resetConversionForm();
    }

    public function saveConvertToAlquiler()
    {
        $this->validate([
            'fechaAlquiler' => 'required|date|after_or_equal:today',
            'fechaDevolucion' => 'required|date|after:fechaAlquiler',
            'diasAlquiler' => 'required|integer|min:1',
            'anticipoAdicional' => 'required|numeric|min:0',
            'depositoGarantia' => 'required_if:requiereDeposito,true|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Validar garantía si se seleccionó
            if ($this->garantia_id) {
                $garantia = \App\Models\Garantia::find($this->garantia_id);
                if (!$garantia || !$garantia->puede_usarse) {
                    throw new \Exception('La garantía seleccionada no está disponible para su uso.');
                }
            }

            // Calcular días de alquiler automáticamente
            $fechaInicio = \Carbon\Carbon::parse($this->fechaAlquiler);
            $fechaFin = \Carbon\Carbon::parse($this->fechaDevolucion);
            $diasCalculados = $fechaInicio->diffInDays($fechaFin) + 1; // +1 para incluir el día de entrega

            // Crear el alquiler usando el método del modelo
            $alquiler = $this->selectedReserva->convertirAAlquiler([
                'fecha_alquiler' => $this->fechaAlquiler,
                'fecha_devolucion_programada' => $this->fechaDevolucion,
                'dias_alquiler' => $diasCalculados,
                'anticipo' => $this->anticipoAdicional, // Pago adicional
                'requiere_deposito' => $this->requiereDeposito,
                'deposito_garantia' => $this->depositoGarantia,
                'garantia_id' => $this->garantia_id ?: null,
                'observaciones' => $this->observacionesAlquiler ?? '',
                'usuario_creacion' => Auth::id(),
            ]);

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Alquiler Creado!',
                'text' => "El alquiler {$alquiler->numero_contrato} ha sido creado exitosamente desde la reserva {$this->selectedReserva->numero_reserva}.",
                'icon' => 'success'
            ]);

            $this->closeConvertToAlquilerModal();

        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Error al convertir reserva: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    private function resetConversionForm()
    {
        $this->fechaAlquiler = '';
        $this->fechaDevolucion = '';
        $this->diasAlquiler = 3;
        $this->anticipoAdicional = 0;
        $this->requiereDeposito = false;
        $this->depositoGarantia = 0;
        $this->observacionesAlquiler = '';
        $this->garantia_id = '';
    }

    public function updatedDiasAlquiler()
    {
        if ($this->fechaAlquiler && $this->diasAlquiler > 0) {
            $fechaAlquiler = \Carbon\Carbon::parse($this->fechaAlquiler);
            $this->fechaDevolucion = $fechaAlquiler->addDays($this->diasAlquiler)->format('Y-m-d');
        }
    }

    public function updatedFechaAlquiler()
    {
        if ($this->fechaAlquiler && $this->diasAlquiler > 0) {
            $fechaAlquiler = \Carbon\Carbon::parse($this->fechaAlquiler);
            $this->fechaDevolucion = $fechaAlquiler->addDays($this->diasAlquiler)->format('Y-m-d');
        }
    }
}
