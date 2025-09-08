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
use App\Models\Caja;
use App\Models\MovimientoCaja;
use App\Models\StockPorSucursal;
use App\Models\MovimientoStockSucursal;
use App\Models\AlquilerDetalle;
use App\Models\ReservaDetalle;
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
    public $garantia_id = '';
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
    public $devolucionDetalles = [];

    // Pago
    public $monto_pago = 0;
    public $referencia_pago = '';
    public $observaciones_pago = '';
    public $caja_id = '';
    public $metodo_pago = 'EFECTIVO';

    // Gestión de garantías
    public $showGarantiaModal = false;
    public $monto_aplicar_garantia = 0;
    public $motivo_aplicacion = '';

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
        $cajas = Caja::where('estado', 'ABIERTA')->orderBy('nombre')->get();
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
            'cajas' => $cajas,
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

            // Validar garantía si se seleccionó
            if ($this->garantia_id) {
                $garantia = \App\Models\Garantia::find($this->garantia_id);
                if (!$garantia || !$garantia->puede_usarse) {
                    throw new \Exception('La garantía seleccionada no está disponible para su uso.');
                }
            }

            $alquiler = Alquiler::create([
                'sucursal_id' => $this->sucursal_id,
                'numero_contrato' => $numeroContrato,
                'reserva_id' => $this->reserva_id ?: null,
                'cliente_id' => $this->cliente_id,
                'unidad_educativa_id' => $this->unidad_educativa_id ?: null,
                'garantia_id' => $this->garantia_id ?: null,
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

            // Crear detalles del alquiler y ajustar stock
            foreach ($this->selectedProducts as $producto) {
                AlquilerDetalle::create([
                    'alquiler_id' => $alquiler->id,
                    'producto_id' => $producto['id'],
                    'cantidad' => (int) $producto['cantidad'],
                    'precio_unitario' => $producto['precio_unitario'] ?? 0,
                    'subtotal' => $producto['subtotal'] ?? (($producto['precio_unitario'] ?? 0) * (int) $producto['cantidad']),
                    'estado_devolucion' => 'PENDIENTE',
                ]);

                $stockSucursal = StockPorSucursal::where('producto_id', $producto['id'])
                    ->where('sucursal_id', $this->sucursal_id)
                    ->lockForUpdate()
                    ->first();

                if ($stockSucursal) {
                    $cantidad = (int) $producto['cantidad'];
                    $stockAnterior = (int) $stockSucursal->stock_actual;
                    $stockSucursal->stock_actual = max(0, (int) $stockSucursal->stock_actual - $cantidad);
                    if (isset($stockSucursal->stock_alquilado)) {
                        $stockSucursal->stock_alquilado = (int) $stockSucursal->stock_alquilado + $cantidad;
                    }
                    $stockSucursal->save();

                    MovimientoStockSucursal::create([
                        'producto_id' => $producto['id'],
                        'sucursal_id' => $this->sucursal_id,
                        'tipo_movimiento' => 'SALIDA',
                        'cantidad' => $cantidad,
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $stockSucursal->stock_actual,
                        'referencia' => $numeroContrato,
                        'motivo' => 'Salida por alquiler',
                        'usuario_id' => Auth::id(),
                        'fecha_movimiento' => now(),
                    ]);
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
        $this->selectedAlquiler = Alquiler::with(['detalles.producto'])->find($alquilerId);
        $this->fecha_devolucion_real = now()->format('Y-m-d\TH:i');
        $this->penalizacion = 0;
        $this->observaciones_devolucion = '';
        // Si no hay detalles, reconstruir desde reserva asociada (contratos antiguos)
        if ($this->selectedAlquiler && $this->selectedAlquiler->detalles()->count() === 0 && $this->selectedAlquiler->reserva_id) {
            $detallesReserva = ReservaDetalle::where('reserva_id', $this->selectedAlquiler->reserva_id)->get();
            foreach ($detallesReserva as $dr) {
                AlquilerDetalle::create([
                    'alquiler_id' => $this->selectedAlquiler->id,
                    'producto_id' => $dr->producto_id,
                    'cantidad' => (int) $dr->cantidad,
                    'precio_unitario' => $dr->precio_unitario,
                    'subtotal' => $dr->subtotal,
                    'estado_devolucion' => 'PENDIENTE',
                ]);
            }
            $this->selectedAlquiler->load('detalles.producto');
        }
        // Precargar detalles para marcar estado por ítem (por defecto DEVUELTO)
        $this->devolucionDetalles = $this->selectedAlquiler->detalles->map(function ($d) {
            return [
                'detalle_id' => $d->id,
                'producto' => $d->producto->nombre ?? 'Producto',
                'cantidad' => (int) $d->cantidad,
                'estado' => $d->estado_devolucion ?? 'PENDIENTE',
                'observaciones' => $d->observaciones_devolucion ?? '',
            ];
        })->toArray();
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

            // Actualizar estados por detalle según selección de UI
            foreach ($this->devolucionDetalles as $item) {
                $detalle = $this->selectedAlquiler->detalles->firstWhere('id', $item['detalle_id']);
                if ($detalle) {
                    $estado = $item['estado'] ?: 'PENDIENTE';
                    if ($estado === 'DEVUELTO' && $detalle->estado_devolucion !== 'DEVUELTO') {
                        $detalle->marcarComoDevuelto($item['observaciones'] ?? null);
                    } elseif ($estado === 'DAÑADO' && $detalle->estado_devolucion !== 'DAÑADO') {
                        $detalle->marcarComoDañado(0, $item['observaciones'] ?? null);
                    } elseif ($estado === 'PERDIDO' && $detalle->estado_devolucion !== 'PERDIDO') {
                        $detalle->update([
                            'estado_devolucion' => 'PERDIDO',
                            'fecha_devolucion' => now(),
                            'observaciones_devolucion' => $item['observaciones'] ?? null,
                        ]);
                    }
                }
            }

            // Procesar penalización en garantía si existe y hay monto a aplicar
            if ($this->selectedAlquiler->tieneGarantia() && $this->penalizacion > 0) {
                $this->selectedAlquiler->aplicarGarantia(
                    $this->penalizacion, 
                    "Penalización por devolución tardía o daños - " . $this->observaciones_devolucion
                );
            }

            $this->selectedAlquiler->update([
                'fecha_devolucion_real' => $this->fecha_devolucion_real,
                'penalizacion' => $this->penalizacion,
                'observaciones' => $this->selectedAlquiler->observaciones . "\nDevolución: " . $this->observaciones_devolucion,
                'usuario_devolucion' => Auth::id(),
            ]);

            // Ajuste de stock por detalle según estado_devolucion
            $detalles = $this->selectedAlquiler->detalles()->get();
            foreach ($detalles as $detalle) {
                // Solo procesar detalles que ya no estén PENDIENTE
                if (in_array($detalle->estado_devolucion, ['DEVUELTO', 'DAÑADO', 'PERDIDO'])) {
                    $stockSucursal = StockPorSucursal::where('producto_id', $detalle->producto_id)
                        ->where('sucursal_id', $this->selectedAlquiler->sucursal_id)
                        ->lockForUpdate()
                        ->first();

                    if ($stockSucursal) {
                        $stockAnterior = $stockSucursal->stock_actual;

                        // Liberar del alquilado
                        if (isset($stockSucursal->stock_alquilado)) {
                            $stockSucursal->stock_alquilado = max(0, (int) $stockSucursal->stock_alquilado - (int) $detalle->cantidad);
                        }

                        if ($detalle->estado_devolucion === 'DEVUELTO') {
                            // Vuelve al stock
                            $stockSucursal->stock_actual += (int) $detalle->cantidad;
                            $stockSucursal->save();

                            MovimientoStockSucursal::create([
                                'producto_id' => $detalle->producto_id,
                                'sucursal_id' => $this->selectedAlquiler->sucursal_id,
                                'tipo_movimiento' => 'ENTRADA',
                                'cantidad' => (int) $detalle->cantidad,
                                'stock_anterior' => $stockAnterior,
                                'stock_nuevo' => $stockSucursal->stock_actual,
                                'referencia' => $this->selectedAlquiler->numero_contrato,
                                'motivo' => 'Devolución de alquiler',
                                'usuario_id' => Auth::id(),
                                'fecha_movimiento' => now(),
                            ]);
                        } else {
                            // DAÑADO o PERDIDO: no incrementa stock_actual, solo ajuste de baja
                            $stockSucursal->save();

                            MovimientoStockSucursal::create([
                                'producto_id' => $detalle->producto_id,
                                'sucursal_id' => $this->selectedAlquiler->sucursal_id,
                                'tipo_movimiento' => 'AJUSTE',
                                'cantidad' => (int) $detalle->cantidad,
                                'stock_anterior' => $stockAnterior,
                                'stock_nuevo' => $stockSucursal->stock_actual,
                                'referencia' => $this->selectedAlquiler->numero_contrato,
                                'motivo' => $detalle->estado_devolucion === 'DAÑADO' ? 'Baja por daño en alquiler' : 'Baja por pérdida en alquiler',
                                'usuario_id' => Auth::id(),
                                'fecha_movimiento' => now(),
                            ]);
                        }
                    }
                }
            }

            // Actualizar el estado general del alquiler según los detalles
            $this->selectedAlquiler->completarDevolucion($this->observaciones_devolucion);

            // Devolver garantía automáticamente si no hubo penalizaciones que agoten el monto
            if ($this->selectedAlquiler->tieneGarantia()) {
                $garantia = $this->selectedAlquiler->garantia;
                
                // Si aún hay monto disponible, devolver la garantía
                if ($garantia->estado === \App\Models\Garantia::ESTADO_RECIBIDA && $garantia->monto_disponible > 0) {
                    $motivo = "Devolución automática por finalización de alquiler {$this->selectedAlquiler->numero_contrato}";
                    if ($this->penalizacion > 0) {
                        $motivo .= " (después de aplicar penalización de Bs. {$this->penalizacion})";
                    }
                    
                    $garantia->marcarComoDevuelta($garantia->monto_disponible, $motivo);
                }
                
                // Liberar la garantía del alquiler
                $this->selectedAlquiler->liberarGarantia('Alquiler finalizado');
            }

            DB::commit();

            $mensaje = 'La devolución ha sido registrada exitosamente.';
            if ($this->selectedAlquiler->tieneGarantia()) {
                $mensaje .= ' La garantía ha sido procesada automáticamente.';
            }

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Devolución Procesada!',
                'text' => $mensaje,
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
        $this->caja_id = '';
        $this->metodo_pago = 'EFECTIVO';
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
            'caja_id' => 'required|exists:cajas,id',
            'metodo_pago' => 'required|string'
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

            // Registrar movimiento en caja
            $caja = Caja::find($this->caja_id);
            if ($caja && $caja->estado === 'ABIERTA') {
                $caja->registrarMovimiento(
                    MovimientoCaja::TIPO_INGRESO,
                    $this->monto_pago,
                    "Pago alquiler {$this->selectedAlquiler->numero_alquiler}",
                    MovimientoCaja::CATEGORIA_ALQUILER,
                    auth()->id(),
                    $this->referencia_pago ?: $this->selectedAlquiler->numero_alquiler
                );
            }

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Pago Registrado!',
                'text' => 'El pago ha sido registrado exitosamente en caja.',
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

    // Métodos para gestión de garantías
    public function updatedClienteId()
    {
        // Filtrar garantías disponibles por cliente
        $this->garantia_id = '';
    }

    public function asignarGarantia($alquilerId, $garantiaId)
    {
        try {
            $alquiler = Alquiler::find($alquilerId);
            $alquiler->asignarGarantia($garantiaId);

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Garantía Asignada!',
                'text' => 'La garantía ha sido asignada al alquiler exitosamente.',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Error al asignar garantía: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function liberarGarantia($alquilerId)
    {
        try {
            $alquiler = Alquiler::find($alquilerId);
            $alquiler->liberarGarantia('Liberada manualmente desde interfaz');

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Garantía Liberada!',
                'text' => 'La garantía ha sido liberada del alquiler.',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Error al liberar garantía: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    // Gestión avanzada de garantías
    public function openGarantiaModal($alquilerId)
    {
        $this->selectedAlquiler = Alquiler::with('garantia')->find($alquilerId);
        
        if (!$this->selectedAlquiler->tieneGarantia()) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Sin Garantía',
                'text' => 'Este alquiler no tiene una garantía asignada.',
                'icon' => 'warning'
            ]);
            return;
        }

        $this->monto_aplicar_garantia = 0;
        $this->motivo_aplicacion = '';
        $this->showGarantiaModal = true;
    }

    public function closeGarantiaModal()
    {
        $this->showGarantiaModal = false;
        $this->selectedAlquiler = null;
        $this->monto_aplicar_garantia = 0;
        $this->motivo_aplicacion = '';
    }

    public function aplicarMontoGarantia()
    {
        $this->validate([
            'monto_aplicar_garantia' => 'required|numeric|min:0.01|max:' . ($this->selectedAlquiler->garantia->monto_disponible ?? 0),
            'motivo_aplicacion' => 'required|string|min:10',
        ]);

        try {
            DB::beginTransaction();

            $this->selectedAlquiler->aplicarGarantia(
                $this->monto_aplicar_garantia,
                $this->motivo_aplicacion
            );

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Monto Aplicado!',
                'text' => "Se aplicó Bs. {$this->monto_aplicar_garantia} de la garantía.",
                'icon' => 'success'
            ]);

            $this->closeGarantiaModal();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Error al aplicar monto: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function devolverGarantiaCompleta($alquilerId)
    {
        try {
            $alquiler = Alquiler::with('garantia')->find($alquilerId);
            
            if (!$alquiler->tieneGarantia()) {
                throw new \Exception('Este alquiler no tiene garantía asignada.');
            }

            $garantia = $alquiler->garantia;
            
            if ($garantia->estado !== \App\Models\Garantia::ESTADO_RECIBIDA) {
                throw new \Exception('Esta garantía no puede ser devuelta en su estado actual.');
            }

            DB::beginTransaction();

            // Devolver el monto disponible completo
            $garantia->marcarComoDevuelta(
                $garantia->monto_disponible, 
                "Devolución manual completa desde alquiler {$alquiler->numero_contrato}"
            );

            // Liberar la garantía del alquiler
            $alquiler->liberarGarantia('Garantía devuelta manualmente');

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Garantía Devuelta!',
                'text' => "Se devolvió Bs. {$garantia->monto_disponible} de la garantía {$garantia->numero_ticket}.",
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Error al devolver garantía: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    private function resetForm()
    {
        $this->cliente_id = '';
        $this->reserva_id = '';
        $this->unidad_educativa_id = '';
        $this->garantia_id = '';
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
