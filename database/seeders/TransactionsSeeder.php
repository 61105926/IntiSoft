<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Reserva;
use App\Models\ReservaDetalle;
use App\Models\Alquiler;
use App\Models\AlquilerDetalle;
use App\Models\MovimientoCaja;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Caja;
use Carbon\Carbon;

class TransactionsSeeder extends Seeder
{
    public function run()
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        $sucursales = Sucursal::all();
        $usuario = User::first();
        $cajas = Caja::all();

        if ($clientes->isEmpty() || $productos->isEmpty() || $sucursales->isEmpty()) {
            $this->command->error('No hay clientes, productos o sucursales. Ejecuta DemoDataSeeder primero.');
            return;
        }

        // Crear algunas ventas
        $ventasExistentes = Venta::count();
        for ($i = 0; $i < 15; $i++) {
            $fechaVenta = Carbon::now()->subDays(rand(0, 30));
            $cliente = $clientes->random();
            $sucursal = $sucursales->random();
            $caja = $cajas->where('sucursal_id', $sucursal->id)->first() ?? $cajas->first();

            $venta = Venta::create([
                'numero_venta' => 'V-' . str_pad($ventasExistentes + $i + 1, 6, '0', STR_PAD_LEFT),
                'cliente_id' => $cliente->id,
                'sucursal_id' => $sucursal->id,
                'usuario_id' => $usuario->id,
                'caja_id' => $caja->id,
                'fecha_venta' => $fechaVenta,
                'subtotal' => 0,
                'impuestos' => 0,
                'descuento' => 0,
                'total' => 0,
                'monto_pagado' => 0,
                'saldo_pendiente' => 0,
                'estado' => collect(['PENDIENTE', 'COMPLETADA', 'CANCELADA'])->random(),
                'estado_pago' => collect(['PENDIENTE', 'PAGADO', 'PARCIAL'])->random(),
                'metodo_pago' => collect(['EFECTIVO', 'TARJETA', 'TRANSFERENCIA'])->random(),
                'observaciones' => 'Venta de prueba #' . ($i + 1),
                'created_at' => $fechaVenta,
                'updated_at' => $fechaVenta,
            ]);

            // Agregar detalles de venta
            $numItems = rand(1, 4);
            $subtotal = 0;

            for ($j = 0; $j < $numItems; $j++) {
                $producto = $productos->random();
                $cantidad = rand(1, 3);
                $precio = rand(50, 300);
                $itemSubtotal = $cantidad * $precio;
                $subtotal += $itemSubtotal;

                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'descuento_unitario' => 0,
                    'subtotal' => $itemSubtotal,
                    'nombre_producto' => $producto->nombre,
                    'codigo_producto' => $producto->codigo,
                    'estado' => 'ACTIVO',
                ]);
            }

            // Actualizar totales de la venta
            $montoPagado = $venta->estado === 'COMPLETADA' ? $subtotal : rand(0, $subtotal);
            $venta->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'monto_pagado' => $montoPagado,
                'saldo_pendiente' => $subtotal - $montoPagado,
            ]);

            // Crear movimiento de caja si la venta está completada
            if ($venta->estado === 'COMPLETADA') {
                $saldoAnterior = $caja->saldo_actual;
                $nuevoSaldo = $saldoAnterior + $venta->total;

                MovimientoCaja::create([
                    'caja_id' => $caja->id,
                    'tipo' => 'INGRESO',
                    'categoria' => 'VENTA',
                    'monto' => $venta->total,
                    'concepto' => 'Venta ' . $venta->numero_venta,
                    'fecha_movimiento' => $fechaVenta,
                    'usuario_registro' => $usuario->id,
                    'saldo_anterior' => $saldoAnterior,
                    'saldo_posterior' => $nuevoSaldo,
                    'venta_id' => $venta->id,
                    'metodo_pago' => $venta->metodo_pago,
                ]);

                // Actualizar saldo de caja
                $caja->update(['saldo_actual' => $nuevoSaldo]);
            }
        }

        // Por ahora omitir reservas para evitar problemas de estructura

        // Por ahora omitir alquileres para enfocarnos en las ventas

        $this->command->info('Transacciones de prueba creadas exitosamente!');
        $this->command->info('- ' . Venta::count() . ' ventas creadas');
        $this->command->info('- ' . Reserva::count() . ' reservas creadas');
        $this->command->info('- ' . Alquiler::count() . ' alquileres creados');
    }
}