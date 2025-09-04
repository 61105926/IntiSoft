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

class DatosFinancierosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar datos previos respetando foreign keys
        AlquilerDetalle::query()->delete();
        Alquiler::query()->delete();
        ReservaDetalle::query()->delete();
        Reserva::query()->delete();

        // Obtener datos existentes
        $cliente = Cliente::first();
        $sucursal = Sucursal::first();
        $usuario = User::first();
        $productos = Producto::limit(5)->get();

        if (!$cliente || !$sucursal || !$usuario || $productos->count() < 3) {
            $this->command->info('⚠️  Se necesitan al menos: 1 cliente, 1 sucursal, 1 usuario y 3 productos');
            return;
        }

        $this->command->info('🏗️  Creando datos financieros realistas para folklore...');

        // ==========================================
        // ESCENARIO 1: Reserva simple activa
        // ==========================================
        $reserva1 = Reserva::create([
            'numero_reserva' => 'RES-2025-000001',
            'cliente_id' => $cliente->id,
            'tipo_reserva' => 'ALQUILER',
            'fecha_reserva' => now(),
            'fecha_vencimiento' => now()->addDays(7),
            'subtotal' => 600.00,
            'descuento' => 30.00, // 5% descuento
            'impuestos' => 85.50, // 15% sobre (subtotal - descuento)
            'costos_adicionales' => 80.00,
            'detalle_costos_adicionales' => json_encode([
                ['concepto' => 'Seguro trajes', 'monto' => 50.00, 'fecha' => now()],
                ['concepto' => 'Limpieza especializada', 'monto' => 30.00, 'fecha' => now()]
            ]),
            'total' => 735.50, // 600 - 30 + 85.50 + 80
            'anticipo' => 200.00,
            'observaciones' => 'Reserva para danza folklórica escolar',
            'sucursal_id' => $sucursal->id,
            'usuario_creacion_id' => $usuario->id,
            'estado' => 'ACTIVA',
        ]);

        // Detalles de la reserva
        ReservaDetalle::create([
            'reserva_id' => $reserva1->id,
            'producto_id' => $productos[0]->id,
            'cantidad' => 2,
            'precio_unitario' => 150.00,
            'subtotal' => 300.00,
        ]);
        
        ReservaDetalle::create([
            'reserva_id' => $reserva1->id,
            'producto_id' => $productos[1]->id,
            'cantidad' => 1,
            'precio_unitario' => 300.00,
            'subtotal' => 300.00,
        ]);

        // ==========================================
        // ESCENARIO 2: Reserva convertida a alquiler con ajustes
        // ==========================================
        $reserva2 = Reserva::create([
            'numero_reserva' => 'RES-2025-000002',
            'cliente_id' => $cliente->id,
            'tipo_reserva' => 'ALQUILER',
            'fecha_reserva' => now()->subDays(3),
            'fecha_vencimiento' => now()->addDays(4),
            'subtotal' => 900.00,
            'descuento' => 100.00, // Descuento por cliente frecuente
            'impuestos' => 120.00,
            'costos_adicionales' => 60.00,
            'total' => 980.00,
            'anticipo' => 300.00,
            'observaciones' => 'Cliente frecuente - Descuento aplicado',
            'sucursal_id' => $sucursal->id,
            'usuario_creacion_id' => $usuario->id,
            'estado' => 'CONFIRMADA',
        ]);

        // Detalles reserva 2
        foreach ($productos->take(3) as $index => $producto) {
            ReservaDetalle::create([
                'reserva_id' => $reserva2->id,
                'producto_id' => $producto->id,
                'cantidad' => 1,
                'precio_unitario' => 300.00,
                'subtotal' => 300.00,
            ]);
        }

        // Convertir a alquiler con ajuste de precios
        $alquiler1 = Alquiler::create([
            'sucursal_id' => $sucursal->id,
            'numero_contrato' => 'ALQ-2025-000001',
            'reserva_id' => $reserva2->id,
            'cliente_id' => $cliente->id,
            'fecha_alquiler' => now()->subDay(),
            'hora_entrega' => '09:00:00',
            'fecha_devolucion_programada' => now()->addDays(4),
            'hora_devolucion_programada' => '18:00:00',
            'dias_alquiler' => 5,
            
            // Financiero mejorado
            'subtotal' => 950.00, // Ajuste de precios al convertir
            'descuento' => 100.00, // Heredado de reserva
            'impuestos' => 127.50, // Recalculado
            'costos_adicionales' => 160.00, // +100 por días extra
            'detalle_costos_adicionales' => json_encode([
                ['concepto' => 'Seguro extendido', 'monto' => 100.00, 'fecha' => now()],
                ['concepto' => 'Transporte especial', 'monto' => 60.00, 'fecha' => now()]
            ]),
            'total' => 1137.50, // 950 - 100 + 127.50 + 160
            'anticipo_reserva' => 300.00, // Del anticipo de la reserva
            'anticipo' => 400.00, // Pago adicional al convertir
            'ajuste_conversion' => 50.00, // 950 - 900
            'motivo_ajuste' => 'Incremento por días adicionales',
            'saldo_pendiente' => 437.50, // 1137.50 - 300 - 400
            
            // Depósito de garantía
            'requiere_deposito' => true,
            'deposito_garantia' => 200.00,
            'deposito_devuelto' => 0,
            
            'usuario_creacion' => $usuario->id,
            'estado' => 'ACTIVO',
            'estado_pago' => 'PARCIAL',
            'observaciones' => 'Convertido desde RES-2025-000002. Precio ajustado por días extra.',
        ]);

        // Detalles del alquiler (con precios ajustados)
        foreach ($productos->take(3) as $index => $producto) {
            AlquilerDetalle::create([
                'alquiler_id' => $alquiler1->id,
                'producto_id' => $producto->id,
                'cantidad' => 1,
                'precio_unitario' => 316.67, // (950 / 3) precio ajustado
                'subtotal' => 316.67,
                'estado_devolucion' => 'PENDIENTE',
            ]);
        }

        // ==========================================
        // ESCENARIO 3: Alquiler directo con depósito
        // ==========================================
        $alquiler2 = Alquiler::create([
            'sucursal_id' => $sucursal->id,
            'numero_contrato' => 'ALQ-2025-000002',
            'reserva_id' => null, // Alquiler directo
            'cliente_id' => $cliente->id,
            'fecha_alquiler' => now()->subDays(2),
            'hora_entrega' => '10:00:00',
            'fecha_devolucion_programada' => now()->addDays(2),
            'hora_devolucion_programada' => '17:00:00',
            'dias_alquiler' => 4,
            
            'subtotal' => 800.00,
            'descuento' => 0,
            'impuestos' => 120.00,
            'costos_adicionales' => 250.00,
            'detalle_costos_adicionales' => json_encode([
                ['concepto' => 'Seguro premium', 'monto' => 150.00, 'fecha' => now()],
                ['concepto' => 'Servicio de entrega', 'monto' => 100.00, 'fecha' => now()]
            ]),
            'total' => 1170.00,
            'anticipo' => 1170.00, // Pagado completo
            'anticipo_reserva' => 0,
            'ajuste_conversion' => 0,
            'saldo_pendiente' => 0,
            
            // Con depósito
            'requiere_deposito' => true,
            'deposito_garantia' => 300.00,
            'deposito_devuelto' => 0,
            
            'usuario_creacion' => $usuario->id,
            'estado' => 'ACTIVO',
            'estado_pago' => 'PAGADO',
            'observaciones' => 'Alquiler directo - Cliente corporativo - Pago completo',
        ]);

        // Detalles alquiler directo
        foreach ($productos->take(4) as $index => $producto) {
            AlquilerDetalle::create([
                'alquiler_id' => $alquiler2->id,
                'producto_id' => $producto->id,
                'cantidad' => 1,
                'precio_unitario' => 200.00,
                'subtotal' => 200.00,
                'estado_devolucion' => 'PENDIENTE',
            ]);
        }

        // ==========================================
        // ESCENARIO 4: Alquiler vencido con penalizaciones
        // ==========================================
        $alquiler3 = Alquiler::create([
            'sucursal_id' => $sucursal->id,
            'numero_contrato' => 'ALQ-2025-000003',
            'reserva_id' => null,
            'cliente_id' => $cliente->id,
            'fecha_alquiler' => now()->subDays(8),
            'fecha_devolucion_programada' => now()->subDays(2), // Vencido
            'dias_alquiler' => 6,
            
            'subtotal' => 600.00,
            'descuento' => 30.00,
            'impuestos' => 85.50,
            'costos_adicionales' => 50.00,
            'total' => 705.50,
            'anticipo' => 300.00,
            'saldo_pendiente' => 405.50,
            
            // Penalizaciones por retraso
            'penalizacion' => 140.00, // $35 x 4 días vencidos
            
            'usuario_creacion' => $usuario->id,
            'estado' => 'VENCIDO',
            'estado_pago' => 'VENCIDO',
            'observaciones' => 'Penalización por retraso de 4 días. Cliente contactado.',
        ]);

        // Detalles alquiler vencido
        foreach ($productos->take(2) as $index => $producto) {
            AlquilerDetalle::create([
                'alquiler_id' => $alquiler3->id,
                'producto_id' => $producto->id,
                'cantidad' => 1,
                'precio_unitario' => 300.00,
                'subtotal' => 300.00,
                'estado_devolucion' => 'PENDIENTE',
            ]);
        }

        // ==========================================
        // ESCENARIO 5: Reserva de venta
        // ==========================================
        $reservaVenta = Reserva::create([
            'numero_reserva' => 'RES-2025-000003',
            'cliente_id' => $cliente->id,
            'tipo_reserva' => 'VENTA',
            'fecha_reserva' => now()->subDay(),
            'fecha_vencimiento' => now()->addDays(5),
            'subtotal' => 1200.00,
            'descuento' => 120.00, // 10% descuento
            'impuestos' => 162.00, // 15%
            'costos_adicionales' => 0,
            'total' => 1242.00,
            'anticipo' => 500.00,
            'observaciones' => 'Compra de trajes para grupo de danza',
            'sucursal_id' => $sucursal->id,
            'usuario_creacion_id' => $usuario->id,
            'estado' => 'ACTIVA',
        ]);

        ReservaDetalle::create([
            'reserva_id' => $reservaVenta->id,
            'producto_id' => $productos->last()->id,
            'cantidad' => 4,
            'precio_unitario' => 300.00,
            'subtotal' => 1200.00,
        ]);

        $this->command->info('✅ Datos financieros creados exitosamente:');
        $this->command->info('   📝 2 reservas de alquiler + 1 de venta');
        $this->command->info('   🏠 3 alquileres (1 desde reserva, 2 directos)');
        $this->command->info('   💰 Diferentes escenarios: pagado, parcial, vencido');
        $this->command->info('   🔄 Conversión reserva→alquiler con ajustes');
        $this->command->info('   🛡️  Depósitos de garantía y penalizaciones');
        $this->command->info('   📊 Costos adicionales detallados');
    }
}