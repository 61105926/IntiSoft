<?php

namespace Database\Seeders;

use App\Models\Alquiler;
use App\Models\AlquilerDetalle;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Reserva;
use App\Models\ReservaDetalle;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservaAlquilerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener datos existentes
        $cliente = Cliente::first();
        $sucursal = Sucursal::first();
        $usuario = User::first();
        $productos = Producto::limit(3)->get();
        
        // Obtener tipos de garantía
        $tipoGarantiaEfectivo = \App\Models\TipoGarantia::where('nombre', 'Depósito en Efectivo')->first();
        $tipoGarantiaCI = \App\Models\TipoGarantia::where('nombre', 'Carnet de Identidad')->first();

        if (!$cliente || !$sucursal || !$usuario || $productos->isEmpty()) {
            $this->command->info('⚠️  Se necesitan clientes, sucursales, usuarios y productos existentes para crear los datos de ejemplo.');
            return;
        }

        // Crear garantías de ejemplo
        $garantia1 = null;
        $garantia2 = null;
        
        if ($tipoGarantiaEfectivo) {
            $garantia1 = \App\Models\Garantia::create([
                'tipo_garantia_id' => $tipoGarantiaEfectivo->id,
                'cliente_id' => $cliente->id,
                'descripcion' => 'Depósito en efectivo para alquiler de trajes folklóricos',
                'monto' => 500.00,
                'documento_respaldo' => 'Recibo de depósito #001',
                'estado' => \App\Models\Garantia::ESTADO_RECIBIDA,
                'fecha_vencimiento' => now()->addDays(30),
                'usuario_recepcion' => $usuario->id,
                'sucursal_id' => $sucursal->id,
                'observaciones' => 'Garantía para alquiler de evento folklórico',
            ]);
        }
        
        if ($tipoGarantiaCI) {
            $garantia2 = \App\Models\Garantia::create([
                'tipo_garantia_id' => $tipoGarantiaCI->id,
                'cliente_id' => $cliente->id,
                'descripcion' => 'Carnet de identidad como garantía personal',
                'monto' => 0,
                'documento_respaldo' => 'CI: ' . $cliente->carnet_identidad,
                'estado' => \App\Models\Garantia::ESTADO_RECIBIDA,
                'fecha_vencimiento' => now()->addDays(7),
                'usuario_recepcion' => $usuario->id,
                'sucursal_id' => $sucursal->id,
                'observaciones' => 'Carnet de identidad en garantía',
            ]);
        }

        // 1. Crear reserva de alquiler activa con costos detallados
        $reservaActiva = Reserva::create([
            'numero_reserva' => 'RES-2025-' . str_pad(1, 6, '0', STR_PAD_LEFT),
            'cliente_id' => $cliente->id,
            'tipo_reserva' => 'ALQUILER',
            'fecha_reserva' => now(),
            'fecha_vencimiento' => now()->addDays(7),
            'anticipo' => 500.00,
            'subtotal' => 900.00,
            'descuento' => 50.00,
            'impuestos' => 135.00, // 15% sobre subtotal menos descuento
            'costos_adicionales' => 215.00,
            'detalle_costos_adicionales' => json_encode([
                ['concepto' => 'Seguro de trajes', 'monto' => 150.00, 'fecha' => now()->toDateTimeString()],
                ['concepto' => 'Limpieza especializada', 'monto' => 65.00, 'fecha' => now()->toDateTimeString()]
            ]),
            'total' => 1200.00, // 900 - 50 + 135 + 215
            'observaciones' => 'Reserva para evento de folklore escolar - Incluye seguro',
            'sucursal_id' => $sucursal->id,
            'usuario_creacion_id' => $usuario->id,
            'estado' => 'ACTIVA',
        ]);

        // Agregar detalles a la reserva (precios que suman al subtotal)
        $precios = [300.00, 300.00, 300.00];
        foreach ($productos as $index => $producto) {
            ReservaDetalle::create([
                'reserva_id' => $reservaActiva->id,
                'producto_id' => $producto->id,
                'cantidad' => 1,
                'precio_unitario' => $precios[$index],
                'subtotal' => $precios[$index],
            ]);
        }

        // 2. Crear reserva convertida a alquiler
        $reservaConvertida = Reserva::create([
            'numero_reserva' => 'RES-2025-' . str_pad(2, 6, '0', STR_PAD_LEFT),
            'cliente_id' => $cliente->id,
            'tipo_reserva' => 'ALQUILER',
            'fecha_reserva' => now()->subDays(2),
            'fecha_vencimiento' => now()->addDays(5),
            'anticipo' => 800.00,
            'subtotal' => 1800.00,
            'total' => 1800.00,
            'observaciones' => 'Reserva convertida a alquiler',
            'sucursal_id' => $sucursal->id,
            'usuario_creacion_id' => $usuario->id,
            'estado' => 'CONFIRMADA',
        ]);

        // Agregar detalles a la reserva convertida
        foreach ($productos as $index => $producto) {
            ReservaDetalle::create([
                'reserva_id' => $reservaConvertida->id,
                'producto_id' => $producto->id,
                'cantidad' => $index + 2,
                'precio_unitario' => 200.00,
                'subtotal' => 200.00 * ($index + 2),
            ]);
        }

        // 3. Crear alquiler basado en la reserva convertida
        $alquiler = Alquiler::create([
            'sucursal_id' => $sucursal->id,
            'numero_contrato' => 'ALQ-2025-' . str_pad(1, 6, '0', STR_PAD_LEFT),
            'reserva_id' => $reservaConvertida->id,
            'cliente_id' => $cliente->id,
            'garantia_id' => $garantia1 ? $garantia1->id : null, // Asignar garantía de efectivo
            'fecha_alquiler' => now()->subDay(),
            'hora_entrega' => '09:00:00',
            'fecha_devolucion_programada' => now()->addDays(3),
            'hora_devolucion_programada' => '18:00:00',
            'dias_alquiler' => 4,
            'subtotal' => 1800.00,
            'descuento' => 0,
            'impuestos' => 0,
            'total' => 1800.00,
            'anticipo' => 800.00,
            'saldo_pendiente' => 1000.00,
            'usuario_creacion' => $usuario->id,
            'estado' => 'ACTIVO',
            'estado_pago' => 'PARCIAL',
            'observaciones' => 'Alquiler creado desde reserva con garantía de efectivo',
        ]);

        // Agregar detalles al alquiler
        foreach ($productos as $index => $producto) {
            AlquilerDetalle::create([
                'alquiler_id' => $alquiler->id,
                'producto_id' => $producto->id,
                'cantidad' => $index + 2,
                'precio_unitario' => 200.00,
                'subtotal' => 200.00 * ($index + 2),
                'estado_devolucion' => 'PENDIENTE',
            ]);
        }

        // 4. Crear alquiler directo (sin reserva previa)
        $alquilerDirecto = Alquiler::create([
            'sucursal_id' => $sucursal->id,
            'numero_contrato' => 'ALQ-2025-' . str_pad(2, 6, '0', STR_PAD_LEFT),
            'reserva_id' => null,
            'cliente_id' => $cliente->id,
            'garantia_id' => $garantia2 ? $garantia2->id : null, // Asignar garantía de CI
            'fecha_alquiler' => now()->subDays(5),
            'hora_entrega' => '10:00:00',
            'fecha_devolucion_programada' => now()->subDay(),
            'hora_devolucion_programada' => '17:00:00',
            'dias_alquiler' => 4,
            'subtotal' => 900.00,
            'descuento' => 50.00,
            'impuestos' => 0,
            'total' => 850.00,
            'anticipo' => 850.00,
            'saldo_pendiente' => 0,
            'usuario_creacion' => $usuario->id,
            'estado' => 'VENCIDO',
            'estado_pago' => 'PAGADO',
            'observaciones' => 'Alquiler directo sin reserva previa con garantía de CI',
        ]);

        // Agregar detalles al alquiler directo
        foreach ($productos->take(2) as $index => $producto) {
            AlquilerDetalle::create([
                'alquiler_id' => $alquilerDirecto->id,
                'producto_id' => $producto->id,
                'cantidad' => $index + 1,
                'precio_unitario' => 300.00,
                'subtotal' => 300.00 * ($index + 1),
                'estado_devolucion' => 'PENDIENTE',
            ]);
        }

        // 5. Crear reserva de venta
        $reservaVenta = Reserva::create([
            'numero_reserva' => 'RES-2025-' . str_pad(3, 6, '0', STR_PAD_LEFT),
            'cliente_id' => $cliente->id,
            'tipo_reserva' => 'VENTA',
            'fecha_reserva' => now()->subDay(),
            'fecha_vencimiento' => now()->addDays(3),
            'anticipo' => 300.00,
            'subtotal' => 750.00,
            'total' => 750.00,
            'observaciones' => 'Reserva para compra de trajes',
            'sucursal_id' => $sucursal->id,
            'usuario_creacion_id' => $usuario->id,
            'estado' => 'ACTIVA',
        ]);

        // Agregar detalle a la reserva de venta
        ReservaDetalle::create([
            'reserva_id' => $reservaVenta->id,
            'producto_id' => $productos->first()->id,
            'cantidad' => 1,
            'precio_unitario' => 750.00,
            'subtotal' => 750.00,
        ]);

        $this->command->info('✅ Datos de ejemplo creados exitosamente:');
        $this->command->info('   - 3 reservas (2 de alquiler, 1 de venta)');
        $this->command->info('   - 2 alquileres (1 desde reserva, 1 directo)');
        $this->command->info('   - 2 garantías (1 efectivo, 1 carnet identidad)');
        $this->command->info('   - Detalles correspondientes para todas las transacciones');
        $this->command->info('   - Garantías asignadas a los alquileres para demostración');
    }
}
