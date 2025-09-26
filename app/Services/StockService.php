<?php

namespace App\Services;

use App\Models\StockPorSucursal;
use App\Models\Producto;
use App\Models\HistorialProducto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StockService
{
    /**
     * Actualizar stock para operaciones del sistema
     */
    public function actualizarStock($data)
    {
        try {
            DB::beginTransaction();

            $stock = StockPorSucursal::where('producto_id', $data['producto_id'])
                                   ->where('sucursal_id', $data['sucursal_id'])
                                   ->lockForUpdate()
                                   ->first();

            if (!$stock) {
                throw new \Exception("No se encontró stock para el producto en esta sucursal");
            }

            $cantidadAnterior = $this->obtenerCantidadSegunTipo($stock, $data['tipo_stock']);

            switch ($data['operacion']) {
                case 'RESERVAR':
                    return $this->reservarStock($stock, $data);

                case 'LIBERAR':
                    return $this->liberarStock($stock, $data);

                case 'ALQUILAR':
                    return $this->alquilarStock($stock, $data);

                case 'DEVOLVER':
                    return $this->devolverStock($stock, $data);

                case 'VENDER':
                    return $this->venderStock($stock, $data);

                case 'EVENTO':
                    return $this->asignarEvento($stock, $data);

                case 'FINALIZAR_EVENTO':
                    return $this->finalizarEvento($stock, $data);

                case 'MANTENIMIENTO':
                    return $this->moverMantenimiento($stock, $data);

                case 'AJUSTE':
                    return $this->ajustarStock($stock, $data);

                default:
                    throw new \Exception("Operación de stock no válida: {$data['operacion']}");
            }

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Reservar stock (bloquear para reserva)
     */
    private function reservarStock($stock, $data)
    {
        if ($stock->stock_disponible < $data['cantidad']) {
            throw new \Exception("Stock insuficiente. Disponible: {$stock->stock_disponible}, Solicitado: {$data['cantidad']}");
        }

        $cantidadAnterior = $stock->stock_disponible;

        $stock->decrement('stock_disponible', $data['cantidad']);
        $stock->increment('stock_reservado', $data['cantidad']);

        $this->registrarHistorial([
            'producto_id' => $data['producto_id'],
            'tipo_movimiento' => HistorialProducto::TIPO_RESERVA,
            'referencia_tipo' => HistorialProducto::REF_RESERVA,
            'referencia_id' => $data['referencia_id'],
            'sucursal_id' => $data['sucursal_id'],
            'cantidad_anterior' => $cantidadAnterior,
            'cantidad_movimiento' => $data['cantidad'],
            'cantidad_posterior' => $stock->stock_disponible,
            'precio_unitario' => $data['precio_unitario'] ?? null,
            'observaciones' => "Reserva {$data['numero_operacion']} - " . ($data['observaciones'] ?? ''),
        ]);

        DB::commit();
        return $stock->fresh();
    }

    /**
     * Liberar stock reservado
     */
    private function liberarStock($stock, $data)
    {
        if ($stock->stock_reservado < $data['cantidad']) {
            throw new \Exception("No hay suficiente stock reservado para liberar");
        }

        $cantidadAnterior = $stock->stock_disponible;

        $stock->increment('stock_disponible', $data['cantidad']);
        $stock->decrement('stock_reservado', $data['cantidad']);

        $this->registrarHistorial([
            'producto_id' => $data['producto_id'],
            'tipo_movimiento' => HistorialProducto::TIPO_LIBERACION,
            'referencia_tipo' => $data['referencia_tipo'],
            'referencia_id' => $data['referencia_id'],
            'sucursal_id' => $data['sucursal_id'],
            'cantidad_anterior' => $cantidadAnterior,
            'cantidad_movimiento' => $data['cantidad'],
            'cantidad_posterior' => $stock->stock_disponible,
            'observaciones' => "Liberación {$data['numero_operacion']} - " . ($data['observaciones'] ?? ''),
        ]);

        DB::commit();
        return $stock->fresh();
    }

    /**
     * Alquilar stock (mover de reservado a alquilado)
     */
    private function alquilarStock($stock, $data)
    {
        if ($data['desde_reserva'] ?? false) {
            // Viene de una reserva
            if ($stock->stock_reservado < $data['cantidad']) {
                throw new \Exception("No hay suficiente stock reservado");
            }
            $stock->decrement('stock_reservado', $data['cantidad']);
        } else {
            // Alquiler directo
            if ($stock->stock_disponible < $data['cantidad']) {
                throw new \Exception("Stock insuficiente para alquiler directo");
            }
            $stock->decrement('stock_disponible', $data['cantidad']);
        }

        $cantidadAnterior = $stock->stock_alquilado;
        $stock->increment('stock_alquilado', $data['cantidad']);

        $this->registrarHistorial([
            'producto_id' => $data['producto_id'],
            'tipo_movimiento' => HistorialProducto::TIPO_ALQUILER,
            'referencia_tipo' => HistorialProducto::REF_ALQUILER,
            'referencia_id' => $data['referencia_id'],
            'sucursal_id' => $data['sucursal_id'],
            'cantidad_anterior' => $cantidadAnterior,
            'cantidad_movimiento' => $data['cantidad'],
            'cantidad_posterior' => $stock->stock_alquilado,
            'precio_unitario' => $data['precio_unitario'] ?? null,
            'observaciones' => "Alquiler {$data['numero_operacion']} - " . ($data['observaciones'] ?? ''),
        ]);

        DB::commit();
        return $stock->fresh();
    }

    /**
     * Devolver stock alquilado
     */
    private function devolverStock($stock, $data)
    {
        if ($stock->stock_alquilado < $data['cantidad']) {
            throw new \Exception("No hay suficiente stock alquilado para devolver");
        }

        $cantidadAnterior = $stock->stock_disponible;

        // Verificar si va a mantenimiento o vuelve a disponible
        if ($data['requiere_mantenimiento'] ?? false) {
            $stock->decrement('stock_alquilado', $data['cantidad']);
            $stock->increment('stock_mantenimiento', $data['cantidad']);
            $observaciones = "Devolución con mantenimiento - {$data['numero_operacion']}";
        } else {
            $stock->decrement('stock_alquilado', $data['cantidad']);
            $stock->increment('stock_disponible', $data['cantidad']);
            $observaciones = "Devolución - {$data['numero_operacion']}";
        }

        $this->registrarHistorial([
            'producto_id' => $data['producto_id'],
            'tipo_movimiento' => HistorialProducto::TIPO_DEVOLUCION,
            'referencia_tipo' => HistorialProducto::REF_ALQUILER,
            'referencia_id' => $data['referencia_id'],
            'sucursal_id' => $data['sucursal_id'],
            'cantidad_anterior' => $cantidadAnterior,
            'cantidad_movimiento' => $data['cantidad'],
            'cantidad_posterior' => $stock->stock_disponible,
            'observaciones' => $observaciones . " - " . ($data['observaciones'] ?? ''),
        ]);

        DB::commit();
        return $stock->fresh();
    }

    /**
     * Vender stock
     */
    private function venderStock($stock, $data)
    {
        if ($stock->stock_disponible < $data['cantidad']) {
            throw new \Exception("Stock insuficiente para venta");
        }

        $cantidadAnterior = $stock->stock_disponible;

        $stock->decrement('stock_disponible', $data['cantidad']);
        $stock->decrement('stock_total', $data['cantidad']); // La venta reduce el stock total

        $this->registrarHistorial([
            'producto_id' => $data['producto_id'],
            'tipo_movimiento' => HistorialProducto::TIPO_VENTA,
            'referencia_tipo' => HistorialProducto::REF_VENTA,
            'referencia_id' => $data['referencia_id'],
            'sucursal_id' => $data['sucursal_id'],
            'cantidad_anterior' => $cantidadAnterior,
            'cantidad_movimiento' => $data['cantidad'],
            'cantidad_posterior' => $stock->stock_disponible,
            'precio_unitario' => $data['precio_unitario'] ?? null,
            'observaciones' => "Venta {$data['numero_operacion']} - " . ($data['observaciones'] ?? ''),
        ]);

        DB::commit();
        return $stock->fresh();
    }

    /**
     * Asignar stock a evento
     */
    private function asignarEvento($stock, $data)
    {
        if ($stock->stock_disponible < $data['cantidad']) {
            throw new \Exception("Stock insuficiente para evento");
        }

        $cantidadAnterior = $stock->stock_disponible;

        $stock->decrement('stock_disponible', $data['cantidad']);
        $stock->increment('stock_en_eventos', $data['cantidad']);

        $this->registrarHistorial([
            'producto_id' => $data['producto_id'],
            'tipo_movimiento' => HistorialProducto::TIPO_EVENTO,
            'referencia_tipo' => HistorialProducto::REF_EVENTO,
            'referencia_id' => $data['referencia_id'],
            'sucursal_id' => $data['sucursal_id'],
            'cantidad_anterior' => $cantidadAnterior,
            'cantidad_movimiento' => $data['cantidad'],
            'cantidad_posterior' => $stock->stock_disponible,
            'observaciones' => "Evento {$data['numero_operacion']} - " . ($data['observaciones'] ?? ''),
        ]);

        DB::commit();
        return $stock->fresh();
    }

    /**
     * Finalizar evento (devolver stock)
     */
    private function finalizarEvento($stock, $data)
    {
        if ($stock->stock_en_eventos < $data['cantidad']) {
            throw new \Exception("No hay suficiente stock en eventos para finalizar");
        }

        $cantidadAnterior = $stock->stock_disponible;

        // Verificar estado de devolución
        if ($data['estado_devolucion'] === 'BUENO') {
            $stock->decrement('stock_en_eventos', $data['cantidad']);
            $stock->increment('stock_disponible', $data['cantidad']);
        } elseif ($data['estado_devolucion'] === 'MANTENIMIENTO') {
            $stock->decrement('stock_en_eventos', $data['cantidad']);
            $stock->increment('stock_mantenimiento', $data['cantidad']);
        } elseif ($data['estado_devolucion'] === 'PERDIDO') {
            $stock->decrement('stock_en_eventos', $data['cantidad']);
            $stock->decrement('stock_total', $data['cantidad']); // Pérdida total
        }

        $this->registrarHistorial([
            'producto_id' => $data['producto_id'],
            'tipo_movimiento' => HistorialProducto::TIPO_DEVOLUCION,
            'referencia_tipo' => HistorialProducto::REF_EVENTO,
            'referencia_id' => $data['referencia_id'],
            'sucursal_id' => $data['sucursal_id'],
            'cantidad_anterior' => $cantidadAnterior,
            'cantidad_movimiento' => $data['cantidad'],
            'cantidad_posterior' => $stock->stock_disponible,
            'observaciones' => "Finalización evento {$data['numero_operacion']} - Estado: {$data['estado_devolucion']} - " . ($data['observaciones'] ?? ''),
        ]);

        DB::commit();
        return $stock->fresh();
    }

    /**
     * Mover a mantenimiento
     */
    private function moverMantenimiento($stock, $data)
    {
        $cantidadAnterior = $stock->stock_disponible;

        if ($stock->stock_disponible < $data['cantidad']) {
            throw new \Exception("No hay suficiente stock disponible para mantenimiento");
        }

        $stock->decrement('stock_disponible', $data['cantidad']);
        $stock->increment('stock_mantenimiento', $data['cantidad']);

        $this->registrarHistorial([
            'producto_id' => $data['producto_id'],
            'tipo_movimiento' => HistorialProducto::TIPO_MANTENIMIENTO,
            'referencia_tipo' => HistorialProducto::REF_MANTENIMIENTO,
            'referencia_id' => $data['referencia_id'] ?? 0,
            'sucursal_id' => $data['sucursal_id'],
            'cantidad_anterior' => $cantidadAnterior,
            'cantidad_movimiento' => $data['cantidad'],
            'cantidad_posterior' => $stock->stock_disponible,
            'observaciones' => "Mantenimiento - " . ($data['observaciones'] ?? ''),
        ]);

        DB::commit();
        return $stock->fresh();
    }

    /**
     * Ajustar stock manualmente
     */
    private function ajustarStock($stock, $data)
    {
        $cantidadAnterior = $stock->stock_total;
        $diferencia = $data['nueva_cantidad'] - $stock->stock_total;

        if ($diferencia > 0) {
            // Incrementar stock
            $stock->increment('stock_total', $diferencia);
            $stock->increment('stock_disponible', $diferencia);
            $tipoMovimiento = HistorialProducto::TIPO_ENTRADA;
        } else {
            // Decrementar stock
            $diferencia = abs($diferencia);
            if ($stock->stock_disponible < $diferencia) {
                throw new \Exception("No hay suficiente stock disponible para el ajuste");
            }
            $stock->decrement('stock_total', $diferencia);
            $stock->decrement('stock_disponible', $diferencia);
            $tipoMovimiento = HistorialProducto::TIPO_SALIDA;
        }

        $this->registrarHistorial([
            'producto_id' => $data['producto_id'],
            'tipo_movimiento' => $tipoMovimiento,
            'referencia_tipo' => HistorialProducto::REF_AJUSTE,
            'referencia_id' => $data['referencia_id'] ?? 0,
            'sucursal_id' => $data['sucursal_id'],
            'cantidad_anterior' => $cantidadAnterior,
            'cantidad_movimiento' => abs($diferencia),
            'cantidad_posterior' => $stock->stock_total,
            'observaciones' => "Ajuste manual - " . ($data['observaciones'] ?? 'Ajuste de inventario'),
        ]);

        DB::commit();
        return $stock->fresh();
    }

    /**
     * Validar disponibilidad de stock
     */
    public function validarDisponibilidad($productos, $sucursalId)
    {
        $errores = [];

        foreach ($productos as $producto) {
            $stock = StockPorSucursal::where('producto_id', $producto['id'])
                                   ->where('sucursal_id', $sucursalId)
                                   ->first();

            if (!$stock) {
                $errores[] = "Producto {$producto['nombre']}: No tiene stock en esta sucursal";
                continue;
            }

            if ($stock->stock_disponible < $producto['cantidad']) {
                $errores[] = "Producto {$producto['nombre']}: Stock insuficiente (Disponible: {$stock->stock_disponible}, Solicitado: {$producto['cantidad']})";
            }
        }

        return [
            'valido' => empty($errores),
            'errores' => $errores
        ];
    }

    /**
     * Obtener alertas de stock
     */
    public function obtenerAlertasStock($sucursalId = null)
    {
        $query = StockPorSucursal::with(['producto', 'sucursal'])
                                ->whereHas('producto', function($q) {
                                    $q->where('activo', true);
                                });

        if ($sucursalId) {
            $query->where('sucursal_id', $sucursalId);
        }

        $stocks = $query->get();

        $alertas = [
            'stock_bajo' => [],
            'stock_agotado' => [],
            'stock_excesivo' => [],
        ];

        foreach ($stocks as $stock) {
            if ($stock->stock_disponible <= 0) {
                $alertas['stock_agotado'][] = $stock;
            } elseif ($stock->stock_disponible <= $stock->stock_minimo) {
                $alertas['stock_bajo'][] = $stock;
            } elseif ($stock->stock_total >= $stock->stock_maximo) {
                $alertas['stock_excesivo'][] = $stock;
            }
        }

        return $alertas;
    }

    /**
     * Transferir stock entre sucursales
     */
    public function transferirStock($productoId, $sucursalOrigenId, $sucursalDestinoId, $cantidad, $observaciones = '')
    {
        try {
            DB::beginTransaction();

            // Validar stock origen
            $stockOrigen = StockPorSucursal::where('producto_id', $productoId)
                                         ->where('sucursal_id', $sucursalOrigenId)
                                         ->lockForUpdate()
                                         ->first();

            if (!$stockOrigen || $stockOrigen->stock_disponible < $cantidad) {
                throw new \Exception("Stock insuficiente en sucursal origen");
            }

            // Obtener o crear stock destino
            $stockDestino = StockPorSucursal::firstOrCreate(
                [
                    'producto_id' => $productoId,
                    'sucursal_id' => $sucursalDestinoId
                ],
                [
                    'stock_total' => 0,
                    'stock_disponible' => 0,
                    'stock_reservado' => 0,
                    'stock_alquilado' => 0,
                    'stock_en_eventos' => 0,
                    'stock_mantenimiento' => 0,
                ]
            );

            // Realizar transferencia
            $stockOrigen->decrement('stock_disponible', $cantidad);
            $stockOrigen->decrement('stock_total', $cantidad);

            $stockDestino->increment('stock_disponible', $cantidad);
            $stockDestino->increment('stock_total', $cantidad);

            // Registrar historial en origen (salida)
            $this->registrarHistorial([
                'producto_id' => $productoId,
                'tipo_movimiento' => HistorialProducto::TIPO_SALIDA,
                'referencia_tipo' => HistorialProducto::REF_AJUSTE,
                'referencia_id' => 0,
                'sucursal_id' => $sucursalOrigenId,
                'cantidad_anterior' => $stockOrigen->stock_disponible + $cantidad,
                'cantidad_movimiento' => $cantidad,
                'cantidad_posterior' => $stockOrigen->stock_disponible,
                'observaciones' => "Transferencia a sucursal {$sucursalDestinoId} - {$observaciones}",
            ]);

            // Registrar historial en destino (entrada)
            $this->registrarHistorial([
                'producto_id' => $productoId,
                'tipo_movimiento' => HistorialProducto::TIPO_ENTRADA,
                'referencia_tipo' => HistorialProducto::REF_AJUSTE,
                'referencia_id' => 0,
                'sucursal_id' => $sucursalDestinoId,
                'cantidad_anterior' => $stockDestino->stock_disponible - $cantidad,
                'cantidad_movimiento' => $cantidad,
                'cantidad_posterior' => $stockDestino->stock_disponible,
                'observaciones' => "Transferencia desde sucursal {$sucursalOrigenId} - {$observaciones}",
            ]);

            DB::commit();

            return [
                'stock_origen' => $stockOrigen->fresh(),
                'stock_destino' => $stockDestino->fresh()
            ];

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Registrar movimiento en historial
     */
    private function registrarHistorial($data)
    {
        return HistorialProducto::create([
            'producto_id' => $data['producto_id'],
            'tipo_movimiento' => $data['tipo_movimiento'],
            'referencia_tipo' => $data['referencia_tipo'],
            'referencia_id' => $data['referencia_id'],
            'sucursal_id' => $data['sucursal_id'],
            'cantidad_anterior' => $data['cantidad_anterior'],
            'cantidad_movimiento' => $data['cantidad_movimiento'],
            'cantidad_posterior' => $data['cantidad_posterior'],
            'precio_unitario' => $data['precio_unitario'] ?? null,
            'usuario_id' => Auth::id() ?? 1,
            'observaciones' => $data['observaciones'],
            'fecha_movimiento' => now(),
        ]);
    }

    /**
     * Obtener cantidad según tipo de stock
     */
    private function obtenerCantidadSegunTipo($stock, $tipo)
    {
        switch ($tipo) {
            case 'disponible':
                return $stock->stock_disponible;
            case 'reservado':
                return $stock->stock_reservado;
            case 'alquilado':
                return $stock->stock_alquilado;
            case 'eventos':
                return $stock->stock_en_eventos;
            case 'mantenimiento':
                return $stock->stock_mantenimiento;
            case 'total':
            default:
                return $stock->stock_total;
        }
    }

    /**
     * Generar reporte de rotación de productos
     */
    public function generarReporteRotacion($sucursalId, $fechaInicio, $fechaFin)
    {
        $movimientos = HistorialProducto::with(['producto'])
                                       ->where('sucursal_id', $sucursalId)
                                       ->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin])
                                       ->whereIn('tipo_movimiento', [
                                           HistorialProducto::TIPO_ALQUILER,
                                           HistorialProducto::TIPO_VENTA,
                                           HistorialProducto::TIPO_EVENTO
                                       ])
                                       ->get()
                                       ->groupBy('producto_id');

        $reporte = [];

        foreach ($movimientos as $productoId => $movimientosProducto) {
            $producto = $movimientosProducto->first()->producto;
            $totalMovimientos = $movimientosProducto->sum('cantidad_movimiento');

            $reporte[] = [
                'producto_id' => $productoId,
                'producto_nombre' => $producto->nombre,
                'total_movimientos' => $totalMovimientos,
                'alquileres' => $movimientosProducto->where('tipo_movimiento', HistorialProducto::TIPO_ALQUILER)->sum('cantidad_movimiento'),
                'ventas' => $movimientosProducto->where('tipo_movimiento', HistorialProducto::TIPO_VENTA)->sum('cantidad_movimiento'),
                'eventos' => $movimientosProducto->where('tipo_movimiento', HistorialProducto::TIPO_EVENTO)->sum('cantidad_movimiento'),
                'frecuencia_uso' => $movimientosProducto->count(),
            ];
        }

        // Ordenar por total de movimientos
        usort($reporte, function($a, $b) {
            return $b['total_movimientos'] <=> $a['total_movimientos'];
        });

        return $reporte;
    }
}