<?php

namespace App\Http\Livewire\Alquiler;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Alquiler;
use App\Models\Cliente;
use App\Models\Sucursal;
use App\Models\UnidadEducativa;
use App\Models\Caja;
use App\Models\MovimientoCaja;
use App\Models\StockPorSucursal;
use App\Models\MovimientoStockSucursal;
use App\Models\AlquilerDetalle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AlquilerController extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

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
    public $showPrintModal = false;
    public $mostrarModalNuevoCliente = false;

    // Form data para nuevo alquiler
    public $cliente_id = '';
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

    // Garantía integrada
    public $tipo_garantia = 'NINGUNA';
    public $documento_garantia = '';
    public $monto_garantia = 0;
    public $observaciones_garantia = '';


    // Conjuntos seleccionados
    public $selectedConjuntos = [];
    public $currentConjuntoId = '';

    // Alquiler seleccionado
    public $selectedAlquiler = null;

    // Devolución
    public $fecha_devolucion_real;
    public $hora_devolucion_real;
    public $penalizacion_retraso = 0;
    public $penalizacion_danos = 0;
    public $penalizacion_perdida = 0;
    public $observaciones_devolucion = '';
    public $devolucionDetalles = [];
    public $aplicar_penalizaciones_garantia = true;
    public $devolver_garantia = true;

    // Pago
    public $monto_pago = 0;
    public $referencia_pago = '';
    public $observaciones_pago = '';
    public $caja_id = '';
    public $metodo_pago = 'EFECTIVO';

    // Modal de pago
    public $showPaymentModal = false;

    // Gestión de garantías
    public $showGarantiaModal = false;
    public $monto_aplicar_garantia = 0;
    public $motivo_aplicacion = '';

    // Nuevo cliente rápido
    public $nuevoCliente = [
        'nombres' => '',
        'apellidos' => '',
        'carnet_identidad' => '',
        'telefono' => '',
        'email' => ''
    ];

    protected $rules = [
        'cliente_id' => 'required|exists:clientes,id',
        'fecha_alquiler' => 'required|date',
        'fecha_devolucion_programada' => 'required|date|after:fecha_alquiler',
        'dias_alquiler' => 'required|integer|min:1',
        'sucursal_id' => 'required|exists:sucursals,id',
        'tipo_garantia' => 'required|in:NINGUNA,CI,EFECTIVO,QR',
        'documento_garantia' => 'required_if:tipo_garantia,CI',
        'monto_garantia' => 'required_if:tipo_garantia,EFECTIVO,QR|numeric|min:0',
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
        $sucursales = Sucursal::orderBy('nombre')->get();
        $unidadesEducativas = UnidadEducativa::orderBy('nombre')->get();

        // Agregar instancias de conjuntos disponibles para alquilar
        // Filtrar por sucursal si está seleccionada
        $conjuntosQuery = \App\Models\InstanciaConjunto::with(['variacionConjunto.conjunto.categoriaConjunto'])
            ->where('estado_disponibilidad', 'DISPONIBLE')
            ->where('activa', true);

        if ($this->sucursal_id) {
            $conjuntosQuery->where('sucursal_id', $this->sucursal_id);
        }

        $conjuntos = $conjuntosQuery->orderBy('id')->get();

        $cajas = Caja::where('estado', 'ABIERTA')->orderBy('nombre')->get();
        $estadisticas = $this->getEstadisticas();

        $tiposGarantia = \App\Models\TipoGarantia::activos()->orderBy('nombre')->get();

        return view('livewire.alquiler.alquiler', [
            'alquileres' => $alquileres,
            'clientes' => $clientes,
            'sucursales' => $sucursales,
            'unidadesEducativas' => $unidadesEducativas,
            'conjuntos' => $conjuntos,
            'cajas' => $cajas,
            'estadisticas' => $estadisticas,
            'tiposGarantia' => $tiposGarantia,
        ])->extends('layouts.theme.modern-app')->section('content');
    }

    private function getFilteredAlquileres()
    {
        $query = Alquiler::with([
            'cliente',
            'sucursal',
            'usuarioCreacion',
            'unidadEducativa',
            'detalles.instanciaConjunto.variacionConjunto.conjunto'
        ]);

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('numero_contrato', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('cliente', function ($clienteQuery) {
                        $clienteQuery->where('nombres', 'like', '%' . $this->searchTerm . '%')
                            ->orWhere('apellidos', 'like', '%' . $this->searchTerm . '%');
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

    public function getEstadisticas()
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

    // Métodos para nuevo cliente rápido
    public function abrirModalNuevoCliente()
    {
        $this->mostrarModalNuevoCliente = true;
        $this->nuevoCliente = [
            'nombres' => '',
            'apellidos' => '',
            'carnet_identidad' => '',
            'telefono' => '',
            'email' => ''
        ];
    }

    public function cerrarModalNuevoCliente()
    {
        $this->mostrarModalNuevoCliente = false;
        $this->nuevoCliente = [
            'nombres' => '',
            'apellidos' => '',
            'carnet_identidad' => '',
            'telefono' => '',
            'email' => ''
        ];
    }

    public function guardarNuevoCliente()
    {
        $this->validate([
            'nuevoCliente.nombres' => 'required|string|max:100',
            'nuevoCliente.apellidos' => 'required|string|max:100',
            'nuevoCliente.carnet_identidad' => 'required|string|max:50|unique:clientes,carnet_identidad',
            'nuevoCliente.telefono' => 'required|string|max:20',
            'nuevoCliente.email' => 'nullable|email|max:100'
        ]);

        $cliente = Cliente::create([
            'nombres' => $this->nuevoCliente['nombres'],
            'apellidos' => $this->nuevoCliente['apellidos'],
            'carnet_identidad' => $this->nuevoCliente['carnet_identidad'],
            'telefono' => $this->nuevoCliente['telefono'],
            'email' => $this->nuevoCliente['email'],
            'activo' => true
        ]);

        // Recargar clientes
        $clientes = Cliente::orderBy('nombres')->get();
        $this->cliente_id = $cliente->id;

        session()->flash('message', 'Cliente creado exitosamente');
        $this->cerrarModalNuevoCliente();

        // Forzar recarga de datos
        $this->emit('refreshComponent');
    }

    public function saveNewAlquiler()
    {
        // Validar que haya conjuntos seleccionados
        if (empty($this->selectedConjuntos)) {
            session()->flash('errorModal', 'Debe seleccionar al menos un conjunto folklórico para alquilar.');
            return;
        }

        $this->validate();

        try {
            DB::beginTransaction();

            $numeroContrato = 'ALQ-' . date('Y') . '-' . str_pad(Alquiler::count() + 1, 4, '0', STR_PAD_LEFT);

            // Calcular total
            $subtotal = $this->calculateSubtotal();
            $total = $subtotal;
            $saldoPendiente = $total - $this->anticipo;

            $alquiler = Alquiler::create([
                'sucursal_id' => $this->sucursal_id,
                'numero_contrato' => $numeroContrato,
                'cliente_id' => $this->cliente_id,
                'unidad_educativa_id' => $this->unidad_educativa_id ?: null,
                'tipo_pago_id' => 1,
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
                'tipo_garantia' => $this->tipo_garantia,
                'documento_garantia' => $this->documento_garantia,
                'monto_garantia' => $this->monto_garantia,
                'observaciones_garantia' => $this->observaciones_garantia,
                'estado_garantia' => 'PENDIENTE',
            ]);

            // Crear detalles del alquiler con conjuntos
            foreach ($this->selectedConjuntos as $conjunto) {
                AlquilerDetalle::create([
                    'alquiler_id' => $alquiler->id,
                    'instancia_conjunto_id' => $conjunto['instancia_id'],
                    'conjunto_id' => $conjunto['conjunto_id'],
                    'cantidad' => 1,
                    'precio_unitario' => $conjunto['precio_unitario'] ?? 0,
                    'subtotal' => $conjunto['subtotal'] ?? 0,
                    'estado_devolucion' => 'PENDIENTE',
                ]);

                // Actualizar estado de la instancia del conjunto
                $instancia = \App\Models\InstanciaConjunto::find($conjunto['instancia_id']);
                if ($instancia) {
                    $instancia->update([
                        'estado_disponibilidad' => 'ALQUILADO',
                        'ultimo_alquiler_id' => $alquiler->id,
                        'fecha_ultimo_alquiler' => now(),
                    ]);
                }
            }

            // Registrar anticipo en caja si hay anticipo
            if ($this->anticipo > 0) {
                $cajaAbierta = Caja::where('estado', 'ABIERTA')
                    ->where('sucursal_id', $this->sucursal_id)
                    ->first();

                if ($cajaAbierta) {
                    $cajaAbierta->registrarMovimiento(
                        MovimientoCaja::TIPO_INGRESO,
                        $this->anticipo,
                        "Anticipo alquiler {$numeroContrato}",
                        MovimientoCaja::CATEGORIA_ALQUILER,
                        Auth::id(),
                        "Cliente: {$alquiler->cliente->nombres} {$alquiler->cliente->apellidos}"
                    );
                }
            }



            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Alquiler Creado!',
                'text' => 'El alquiler ha sido creado exitosamente y registrado en caja.',
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
        $this->selectedAlquiler = Alquiler::with([
            'cliente',
            'sucursal',
            'usuarioCreacion',
            'unidadEducativa',
            'detalles.instanciaConjunto.variacionConjunto.conjunto',
            'detalles.instanciaConjunto.componentesActivos'
        ])->find($alquilerId);
        $this->showViewAlquilerModal = true;
    }

    public function closeViewAlquilerModal()
    {
        $this->showViewAlquilerModal = false;
        $this->selectedAlquiler = null;
    }

    public function printAlquiler($alquilerId)
    {
        $this->selectedAlquiler = Alquiler::with([
            'cliente',
            'sucursal',
            'usuarioCreacion',
            'unidadEducativa',
            'detalles.instanciaConjunto.variacionConjunto.conjunto',
            'detalles.instanciaConjunto.componentesActivos'
        ])->find($alquilerId);
        $this->showPrintModal = true;
    }

    public function closePrintModal()
    {
        $this->showPrintModal = false;
        $this->selectedAlquiler = null;
    }

    public function openDevolucionModal($alquilerId)
    {
        $this->selectedAlquiler = Alquiler::with([
            'detalles.instanciaConjunto.variacionConjunto.conjunto',
            'detalles.instanciaConjunto.componentesActivos.componente',
            'cliente'
        ])->find($alquilerId);

        $this->fecha_devolucion_real = now()->format('Y-m-d');
        $this->hora_devolucion_real = now()->format('H:i');
        $this->penalizacion_retraso = 0;
        $this->penalizacion_danos = 0;
        $this->penalizacion_perdida = 0;
        $this->observaciones_devolucion = '';
        $this->aplicar_penalizaciones_garantia = true;
        $this->devolver_garantia = true;

        // Inicializar estructura de devolución para cada detalle
        $this->devolucionDetalles = [];
        foreach ($this->selectedAlquiler->detalles as $index => $detalle) {
            $this->devolucionDetalles[$index] = [
                'detalle_id' => $detalle->id,
                'estado_general' => 'COMPLETO',
                'observaciones_generales' => '',
                'componentes' => []
            ];

            // Inicializar cada componente si existen
            if ($detalle->instanciaConjunto && $detalle->instanciaConjunto->componentesActivos) {
                foreach ($detalle->instanciaConjunto->componentesActivos as $componente) {
                    $this->devolucionDetalles[$index]['componentes'][$componente->id] = [
                        'presente' => true,
                        'estado' => 'DEVUELTO',
                        'costo_penalizacion' => 0,
                        'observaciones' => ''
                    ];
                }
            }
        }

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
        ]);

        try {
            DB::beginTransaction();

            $totalPenalizaciones = $this->penalizacion_retraso + $this->penalizacion_danos + $this->penalizacion_perdida;

            // Procesar cada conjunto devuelto
            foreach ($this->devolucionDetalles as $index => $detalleData) {
                $detalle = $this->selectedAlquiler->detalles->firstWhere('id', $detalleData['detalle_id']);

                if (!$detalle) continue;

                // Actualizar estado del detalle
                $detalle->update([
                    'estado_devolucion' => $detalleData['estado_general'],
                    'fecha_devolucion' => $this->fecha_devolucion_real . ' ' . $this->hora_devolucion_real,
                    'observaciones_devolucion' => $detalleData['observaciones_generales'],
                    'penalizacion_retraso' => $this->penalizacion_retraso,
                    'penalizacion_daños' => $this->penalizacion_danos,
                    'penalizacion_perdida' => $this->penalizacion_perdida,
                ]);

                // Procesar componentes individuales
                if (isset($detalleData['componentes']) && $detalle->instanciaConjunto) {
                    foreach ($detalleData['componentes'] as $componenteId => $componenteData) {
                        $instanciaComponente = \App\Models\InstanciaComponente::find($componenteId);

                        if (!$instanciaComponente) continue;

                        // Actualizar estado del componente
                        $nuevoEstado = 'ASIGNADO';
                        if (!$componenteData['presente'] || $componenteData['estado'] === 'PERDIDO') {
                            $nuevoEstado = 'PERDIDO';
                        } elseif (in_array($componenteData['estado'], ['DAÑADO_LEVE', 'DAÑADO_GRAVE'])) {
                            $nuevoEstado = 'DANADO';
                        }

                        $instanciaComponente->update([
                            'estado_actual' => $nuevoEstado,
                            'estado_fisico' => $componenteData['estado'] === 'DEVUELTO' ? 'BUENO' : 'MALO',
                            'observaciones' => $componenteData['observaciones']
                        ]);

                        // Registrar en historial
                        if ($nuevoEstado === 'PERDIDO') {
                            \App\Models\HistorialComponentesConjunto::registrarPerdida(
                                $detalle->instancia_conjunto_id,
                                $instanciaComponente->componente_id,
                                $componenteId,
                                $detalle->id,
                                $componenteData['observaciones']
                            );
                        }

                        // Registrar costo de penalización por componente
                        if ($componenteData['costo_penalizacion'] > 0) {
                            $this->penalizacion_danos += $componenteData['costo_penalizacion'];
                        }
                    }
                }

                // Actualizar estado de disponibilidad de la instancia del conjunto
                if ($detalle->instanciaConjunto) {
                    $estadoDisponibilidad = 'DISPONIBLE';

                    if ($detalleData['estado_general'] === 'PERDIDO') {
                        $estadoDisponibilidad = 'PERDIDO';
                    } elseif ($detalleData['estado_general'] === 'CON_DAÑOS' || $detalleData['estado_general'] === 'INCOMPLETO') {
                        $estadoDisponibilidad = 'MANTENIMIENTO';
                    }

                    $detalle->instanciaConjunto->update([
                        'estado_disponibilidad' => $estadoDisponibilidad,
                        'fecha_ultima_devolucion' => now(),
                    ]);
                }
            }

            // Actualizar alquiler con penalizaciones
            $this->selectedAlquiler->update([
                'fecha_devolucion_real' => $this->fecha_devolucion_real . ' ' . $this->hora_devolucion_real,
                'estado' => 'DEVUELTO',
                'observaciones' => ($this->selectedAlquiler->observaciones ?? '') . "\n\n[DEVOLUCIÓN] " . $this->observaciones_devolucion,
                'usuario_devolucion' => Auth::id(),
            ]);

            // Agregar penalizaciones al total
            if ($totalPenalizaciones > 0) {
                $this->selectedAlquiler->update([
                    'total' => $this->selectedAlquiler->total + $totalPenalizaciones,
                    'saldo_pendiente' => $this->selectedAlquiler->saldo_pendiente + $totalPenalizaciones,
                ]);
            }

            // Gestionar garantía
            if ($this->selectedAlquiler->tieneGarantia() && $this->selectedAlquiler->estado_garantia !== 'DEVUELTA') {

                if ($this->aplicar_penalizaciones_garantia && $totalPenalizaciones > 0) {
                    // Descontar penalizaciones de la garantía
                    $montoDescontado = min($totalPenalizaciones, $this->selectedAlquiler->monto_garantia);
                    $montoDevolver = max(0, $this->selectedAlquiler->monto_garantia - $montoDescontado);

                    $this->selectedAlquiler->update([
                        'estado_garantia' => 'APLICADA',
                        'fecha_devolucion_garantia' => now(),
                        'monto_devuelto_garantia' => $montoDevolver,
                        'observaciones_garantia' => "Penalizaciones aplicadas: Bs. {$montoDescontado}. Monto devuelto: Bs. {$montoDevolver}"
                    ]);

                    // Si queda saldo de penalizaciones, agregarlo al saldo pendiente
                    if ($totalPenalizaciones > $montoDescontado) {
                        $saldoAdicional = $totalPenalizaciones - $montoDescontado;
                        $this->selectedAlquiler->increment('saldo_pendiente', $saldoAdicional);
                    }
                } else {
                    // Devolver garantía completa
                    if ($this->devolver_garantia) {
                        $this->selectedAlquiler->devolverGarantia(
                            $this->selectedAlquiler->monto_garantia,
                            'Devolución completa - Sin penalizaciones'
                        );
                    }
                }
            }

            DB::commit();

            $mensaje = '✅ Devolución registrada exitosamente';
            if ($totalPenalizaciones > 0) {
                $mensaje .= " | Penalizaciones: Bs. {$totalPenalizaciones}";
            }
            if ($this->selectedAlquiler->tieneGarantia()) {
                $mensaje .= ' | Garantía procesada';
            }

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Devolución Completada!',
                'text' => $mensaje,
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

    public function openPaymentModal($alquilerId)
    {
        $this->selectedAlquiler = Alquiler::with([
            'cliente',
            'sucursal',
            'pagos.usuario'
        ])->find($alquilerId);
        $this->monto_pago = $this->selectedAlquiler->saldo_pendiente;
        $this->caja_id = '';
        $this->metodo_pago = 'EFECTIVO';
        $this->referencia_pago = '';
        $this->observaciones_pago = '';
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->selectedAlquiler = null;
        $this->monto_pago = 0;
        $this->caja_id = '';
        $this->metodo_pago = 'EFECTIVO';
        $this->referencia_pago = '';
        $this->observaciones_pago = '';
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
            \Log::info('Intentando registrar pago de alquiler', [
                'monto' => $this->monto_pago,
                'caja_id' => $this->caja_id,
                'alquiler' => $this->selectedAlquiler->numero_alquiler
            ]);

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
                \Log::info('Pago de alquiler registrado exitosamente en caja');
            } else {
                \Log::warning('Caja no encontrada o no está abierta para alquiler', [
                    'caja_id' => $this->caja_id,
                    'caja_estado' => $caja ? $caja->estado : 'no_encontrada'
                ]);
            }

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Pago Registrado!',
                'text' => 'El pago ha sido registrado exitosamente en caja.',
                'icon' => 'success'
            ]);

            $this->closePaymentModal();
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al registrar el pago: ' . $e->getMessage());
        }
    }

    // Métodos para conjuntos folklóricos
    public function addConjuntoToAlquiler()
    {
        if (!$this->currentConjuntoId) {
            session()->flash('errorModal', 'Debe seleccionar un conjunto.');
            return;
        }

        if (!$this->sucursal_id) {
            session()->flash('errorModal', 'Debe seleccionar una sucursal primero.');
            return;
        }

        $instancia = \App\Models\InstanciaConjunto::with(['variacionConjunto.conjunto'])
            ->where('id', $this->currentConjuntoId)
            ->where('estado_disponibilidad', 'DISPONIBLE')
            ->where('sucursal_id', $this->sucursal_id)
            ->first();

        if (!$instancia) {
            session()->flash('errorModal', 'Conjunto no disponible en esta sucursal.');
            return;
        }

        // Verificar si ya está agregado
        $existingIndex = collect($this->selectedConjuntos)->search(function ($item) use ($instancia) {
            return $item['instancia_id'] == $instancia->id;
        });

        if ($existingIndex !== false) {
            session()->flash('errorModal', 'Este conjunto ya fue agregado.');
            return;
        }

        $conjunto = $instancia->variacionConjunto->conjunto;
        $precio = $conjunto->precio_alquiler_dia ?? 0;

        $this->selectedConjuntos[] = [
            'instancia_id' => $instancia->id,
            'conjunto_id' => $conjunto->id,
            'nombre' => $conjunto->nombre,
            'variacion' => $instancia->variacionConjunto->nombre_variacion ?? '',
            'numero_serie' => $instancia->numero_serie,
            'precio_unitario' => $precio,
            'subtotal' => $precio * $this->dias_alquiler,
        ];

        $this->currentConjuntoId = '';
    }

    public function removeConjuntoFromAlquiler($index)
    {
        unset($this->selectedConjuntos[$index]);
        $this->selectedConjuntos = array_values($this->selectedConjuntos);
    }

    private function calculateSubtotal()
    {
        return collect($this->selectedConjuntos)->sum('subtotal');
    }

    // Métodos para gestión de garantías
    public function updatedClienteId()
    {
        // Filtrar garantías disponibles por cliente
        $this->garantia_id = '';
    }

    public function liberarGarantiaExterna($alquilerId)
    {
        try {
            $alquiler = Alquiler::find($alquilerId);

            // Ya no se usa tabla externa de garantías
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Función obsoleta',
                'text' => 'Las garantías ahora están integradas en el alquiler.',
                'icon' => 'info'
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

            $this->selectedAlquiler->aplicarGarantia($this->motivo_aplicacion);

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
            $alquiler = Alquiler::find($alquilerId);

            if (!$alquiler->tieneGarantia()) {
                throw new \Exception('Este alquiler no tiene garantía asignada.');
            }

            if ($alquiler->estado_garantia === 'DEVUELTA') {
                throw new \Exception('Esta garantía ya fue devuelta.');
            }

            DB::beginTransaction();

            $alquiler->devolverGarantia(null, 'Devolución manual completa');

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => '¡Garantía Devuelta!',
                'text' => "Se devolvió la garantía del tipo {$alquiler->tipo_garantia_display}.",
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


    public function resetFilters()
    {
        $this->searchTerm = '';
        $this->filterEstado = 'TODOS';
        $this->filterEstadoPago = 'TODOS';
        $this->filterSucursal = 'TODAS';
    }

    private function resetForm()
    {
        $this->cliente_id = '';
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
        $this->tipo_garantia = 'NINGUNA';
        $this->documento_garantia = '';
        $this->monto_garantia = 0;
        $this->observaciones_garantia = '';
        $this->selectedConjuntos = [];
        $this->currentConjuntoId = '';
    }
}
