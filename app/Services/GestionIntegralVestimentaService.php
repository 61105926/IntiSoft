<?php

namespace App\Services;

use App\Models\Reserva;
use App\Models\Alquiler;
use App\Models\EventoFolklorico;
use App\Models\ReservaStockTemporal;
use App\Models\GarantiaIndividual;
use App\Models\FleteProgramado;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\StockPorSucursal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GestionIntegralVestimentaService
{
    /**
     * Crear reserva con control temporal de stock
     */
    public function crearReservaConStock($datosReserva, $productos, $fechaInicio, $fechaFin)
    {
        return DB::transaction(function() use ($datosReserva, $productos, $fechaInicio, $fechaFin) {
            // 1. Validar disponibilidad de todos los productos
            foreach ($productos as $item) {
                if (!ReservaStockTemporal::verificarDisponibilidad(
                    $item['producto_id'],
                    $datosReserva['sucursal_id'],
                    $item['cantidad'],
                    $fechaInicio,
                    $fechaFin
                )) {
                    $producto = Producto::find($item['producto_id']);
                    throw new \Exception("Producto '{$producto->nombre}' no disponible para las fechas solicitadas.");
                }
            }

            // 2. Crear la reserva
            $reserva = Reserva::create(array_merge($datosReserva, [
                'fecha_inicio_uso' => $fechaInicio,
                'fecha_fin_uso' => $fechaFin,
                'bloquea_stock' => true,
            ]));

            // 3. Crear detalles de reserva
            foreach ($productos as $item) {
                $reserva->detalles()->create($item);
            }

            // 4. Reservar stock temporal
            if ($reserva->bloquea_stock) {
                foreach ($productos as $item) {
                    ReservaStockTemporal::reservarStock(
                        $reserva->id,
                        $item['producto_id'],
                        $datosReserva['sucursal_id'],
                        $item['cantidad'],
                        $fechaInicio,
                        $fechaFin,
                        "Reserva automática para {$reserva->numero_reserva}"
                    );
                }
            }

            // 5. Actualizar cálculos financieros
            $reserva->actualizarCalculosFinancieros();

            return $reserva;
        });
    }

    /**
     * Convertir reserva a alquiler con garantías individuales
     */
    public function convertirReservaConGarantias($reserva, $datosAlquiler, $garantiasPorProducto = [])
    {
        return DB::transaction(function() use ($reserva, $datosAlquiler, $garantiasPorProducto) {
            // 1. Convertir reserva tradicional
            $alquiler = $reserva->convertirAAlquiler($datosAlquiler);

            // 2. Confirmar reservas temporales de stock
            foreach ($reserva->stocksTemporales as $stockTemporal) {
                $stockTemporal->confirmar();
            }

            // 3. Crear garantías individuales
            foreach ($alquiler->detalles as $detalle) {
                $garantiaConfig = $garantiasPorProducto[$detalle->producto_id] ?? [];

                if (!empty($garantiaConfig['monto_garantia_unitario']) && $garantiaConfig['monto_garantia_unitario'] > 0) {
                    GarantiaIndividual::crearGarantiaIndividual([
                        'alquiler_id' => $alquiler->id,
                        'producto_id' => $detalle->producto_id,
                        'cliente_id' => $alquiler->cliente_id,
                        'cantidad' => $detalle->cantidad,
                        'monto_garantia_unitario' => $garantiaConfig['monto_garantia_unitario'],
                        'observaciones_entrega' => $garantiaConfig['observaciones'] ?? null,
                        'usuario_entrega' => $datosAlquiler['usuario_creacion'] ?? auth()->id(),
                    ]);
                }
            }

            // 4. Actualizar totales de garantías en alquiler
            $totalGarantias = $alquiler->garantiasIndividuales()->sum('monto_garantia_total');
            $alquiler->update(['total_garantias_individuales' => $totalGarantias]);

            return $alquiler;
        });
    }

    /**
     * Crear alquiler desde evento folklórico
     */
    public function crearAlquilerDesdeEvento($eventoId, $participanteId, $vestimentas, $datosAlquiler = [])
    {
        return DB::transaction(function() use ($eventoId, $participanteId, $vestimentas, $datosAlquiler) {
            $evento = EventoFolklorico::findOrFail($eventoId);
            $participante = $evento->participantes()->findOrFail($participanteId);

            // Datos base del alquiler
            $datosBase = [
                'evento_folklorico_id' => $eventoId,
                'cliente_id' => $participante->cliente_id,
                'sucursal_id' => $evento->sucursal_id,
                'numero_contrato' => 'ALQ-EVT-' . date('Y') . '-' . str_pad(Alquiler::count() + 1, 6, '0', STR_PAD_LEFT),
                'fecha_alquiler' => $evento->fecha_evento->subDays(1), // Entrega un día antes
                'fecha_devolucion_programada' => $evento->fecha_evento->addDays(1), // Devolución un día después
                'dias_alquiler' => 3,
                'estado' => 'ACTIVO',
                'observaciones' => "Alquiler generado automáticamente para evento: {$evento->nombre_evento}",
                'usuario_creacion' => auth()->id(),
            ];

            // Crear alquiler
            $alquiler = Alquiler::create(array_merge($datosBase, $datosAlquiler));

            // Crear detalles de alquiler
            $subtotal = 0;
            foreach ($vestimentas as $vestimenta) {
                $detalle = $alquiler->detalles()->create([
                    'producto_id' => $vestimenta['producto_id'],
                    'cantidad' => $vestimenta['cantidad'],
                    'precio_unitario' => $vestimenta['precio_unitario'],
                    'subtotal' => $vestimenta['precio_unitario'] * $vestimenta['cantidad'],
                    'estado_devolucion' => 'PENDIENTE',
                ]);
                $subtotal += $detalle->subtotal;

                // Crear garantía individual automática
                GarantiaIndividual::crearGarantiaIndividual([
                    'alquiler_id' => $alquiler->id,
                    'evento_id' => $eventoId,
                    'producto_id' => $vestimenta['producto_id'],
                    'cliente_id' => $participante->cliente_id,
                    'cantidad' => $vestimenta['cantidad'],
                    'monto_garantia_unitario' => $participante->monto_garantia / count($vestimentas), // Dividir garantía total
                    'observaciones_entrega' => "Garantía para evento: {$evento->nombre_evento}",
                    'usuario_entrega' => auth()->id(),
                ]);
            }

            // Actualizar totales
            $alquiler->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'total_garantias_individuales' => $participante->monto_garantia,
            ]);

            $alquiler->actualizarCalculosFinancieros();

            return $alquiler;
        });
    }

    /**
     * Programar flete integral (entrega y/o recogida)
     */
    public function programarFleteIntegral($referencia, $tipoFlete, $datosEntrega, $datosRecogida = null, $costos = [])
    {
        $datosBase = [
            'tipo_flete' => $tipoFlete,
            'usuario_programacion' => auth()->id(),
        ];

        // Determinar referencia
        if ($referencia instanceof Alquiler) {
            $datosBase['alquiler_id'] = $referencia->id;
        } elseif ($referencia instanceof Reserva) {
            $datosBase['reserva_id'] = $referencia->id;
        } elseif ($referencia instanceof EventoFolklorico) {
            $datosBase['evento_id'] = $referencia->id;
        }

        // Datos de entrega
        if (in_array($tipoFlete, ['ENTREGA', 'AMBOS'])) {
            $datosBase = array_merge($datosBase, [
                'direccion_entrega' => $datosEntrega['direccion'],
                'referencia_entrega' => $datosEntrega['referencia'] ?? null,
                'fecha_entrega_programada' => $datosEntrega['fecha_programada'],
                'contacto_entrega' => $datosEntrega['contacto'],
                'telefono_entrega' => $datosEntrega['telefono'],
                'costo_entrega' => $costos['entrega'] ?? 0,
            ]);
        }

        // Datos de recogida
        if (in_array($tipoFlete, ['RECOGIDA', 'AMBOS']) && $datosRecogida) {
            $datosBase = array_merge($datosBase, [
                'direccion_recogida' => $datosRecogida['direccion'],
                'referencia_recogida' => $datosRecogida['referencia'] ?? null,
                'fecha_recogida_programada' => $datosRecogida['fecha_programada'],
                'contacto_recogida' => $datosRecogida['contacto'],
                'telefono_recogida' => $datosRecogida['telefono'],
                'costo_recogida' => $costos['recogida'] ?? 0,
            ]);
        }

        return FleteProgramado::programarFlete($datosBase);
    }

    /**
     * Procesar devolución integral con garantías
     */
    public function procesarDevolucionIntegral($alquilerId, $devolucionesPorProducto, $usuarioDevolucionId)
    {
        return DB::transaction(function() use ($alquilerId, $devolucionesPorProducto, $usuarioDevolucionId) {
            $alquiler = Alquiler::findOrFail($alquilerId);

            $totalDevuelto = 0;
            $totalRetenido = 0;

            // Procesar cada garantía individual
            foreach ($alquiler->garantiasIndividuales as $garantia) {
                $devolucion = $devolucionesPorProducto[$garantia->producto_id] ?? null;

                if ($devolucion) {
                    $garantia->registrarDevolucion(
                        $devolucion['estado_prenda'],
                        $devolucion['evaluacion'],
                        $usuarioDevolucionId,
                        $devolucion['observaciones'] ?? null
                    );

                    $totalDevuelto += $garantia->monto_devuelto;
                    $totalRetenido += $garantia->monto_retenido;
                }
            }

            // Actualizar estado del alquiler
            $todasDevueltas = $alquiler->garantiasIndividuales()
                                     ->whereIn('estado_garantia', ['DEVUELTA_COMPLETA', 'DEVUELTA_PARCIAL', 'RETENIDA'])
                                     ->count() === $alquiler->garantiasIndividuales()->count();

            $alquiler->update([
                'garantias_devueltas' => $totalDevuelto,
                'garantias_retenidas' => $totalRetenido,
                'garantias_completadas' => $todasDevueltas,
                'fecha_devolucion_real' => now(),
                'estado' => $todasDevueltas ? 'DEVUELTO' : 'PARCIAL',
                'usuario_devolucion' => $usuarioDevolucionId,
            ]);

            // Completar flete de recogida si existe
            $fleteRecogida = $alquiler->fletes()
                                    ->whereIn('tipo_flete', ['RECOGIDA', 'AMBOS'])
                                    ->whereIn('estado_flete', ['PROGRAMADO', 'EN_RUTA'])
                                    ->first();

            if ($fleteRecogida) {
                $fleteRecogida->completarRecogida(
                    ['devolucion_completada' => now()],
                    'Devolución procesada integralmente'
                );
            }

            return $alquiler;
        });
    }

    /**
     * Obtener disponibilidad de productos por fechas
     */
    public function obtenerDisponibilidadPorFechas($sucursalId, $fechaInicio, $fechaFin, $productosIds = null)
    {
        $query = StockPorSucursal::with('producto')
                                ->where('sucursal_id', $sucursalId);

        if ($productosIds) {
            $query->whereIn('producto_id', $productosIds);
        }

        $stocks = $query->get();

        return $stocks->map(function($stock) use ($fechaInicio, $fechaFin) {
            $reservado = ReservaStockTemporal::where('producto_id', $stock->producto_id)
                                           ->where('sucursal_id', $stock->sucursal_id)
                                           ->activas()
                                           ->enFecha($fechaInicio, $fechaFin)
                                           ->sum('cantidad_reservada');

            return [
                'producto_id' => $stock->producto_id,
                'producto_nombre' => $stock->producto->nombre,
                'stock_total' => $stock->cantidad_disponible,
                'stock_reservado' => $reservado,
                'stock_disponible' => $stock->cantidad_disponible - $reservado,
                'disponible' => ($stock->cantidad_disponible - $reservado) > 0,
            ];
        });
    }

    /**
     * Generar reporte integral de gestión
     */
    public function generarReporteIntegral($fechaInicio, $fechaFin, $sucursalId = null)
    {
        $filtros = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ];

        if ($sucursalId) {
            $filtros['sucursal_id'] = $sucursalId;
        }

        // Estadísticas de reservas
        $reservasQuery = Reserva::whereBetween('fecha_reserva', [$fechaInicio, $fechaFin]);
        if ($sucursalId) $reservasQuery->where('sucursal_id', $sucursalId);

        // Estadísticas de alquileres
        $alquileresQuery = Alquiler::whereBetween('fecha_alquiler', [$fechaInicio, $fechaFin]);
        if ($sucursalId) $alquileresQuery->where('sucursal_id', $sucursalId);

        // Estadísticas de eventos
        $eventosQuery = EventoFolklorico::whereBetween('fecha_evento', [$fechaInicio, $fechaFin]);
        if ($sucursalId) $eventosQuery->where('sucursal_id', $sucursalId);

        return [
            'periodo' => ['inicio' => $fechaInicio, 'fin' => $fechaFin],
            'reservas' => [
                'total' => $reservasQuery->count(),
                'activas' => $reservasQuery->activas()->count(),
                'confirmadas' => $reservasQuery->confirmadas()->count(),
                'monto_total' => $reservasQuery->sum('total'),
            ],
            'alquileres' => [
                'total' => $alquileresQuery->count(),
                'activos' => $alquileresQuery->activos()->count(),
                'devueltos' => $alquileresQuery->where('estado', 'DEVUELTO')->count(),
                'monto_total' => $alquileresQuery->sum('total'),
                'garantias_devueltas' => $alquileresQuery->sum('garantias_devueltas'),
                'garantias_retenidas' => $alquileresQuery->sum('garantias_retenidas'),
            ],
            'eventos' => [
                'total' => $eventosQuery->count(),
                'finalizados' => $eventosQuery->where('estado', 'FINALIZADO')->count(),
                'participantes_total' => $eventosQuery->sum('numero_participantes'),
                'monto_total' => $eventosQuery->sum('total_estimado'),
            ],
            'fletes' => [
                'programados' => FleteProgramado::whereBetween('created_at', [$fechaInicio, $fechaFin])->count(),
                'completados' => FleteProgramado::whereBetween('created_at', [$fechaInicio, $fechaFin])->completados()->count(),
                'costo_total' => FleteProgramado::whereBetween('created_at', [$fechaInicio, $fechaFin])->sum('costo_total'),
            ],
        ];
    }
}