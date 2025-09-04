<?php

namespace Database\Seeders;

use App\Models\Reserva;
use App\Models\ReservaDetalle;
use App\Models\Alquiler;
use App\Models\AlquilerDetalle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestFinancieroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🧪 Creando datos de prueba financiera...');

        // Limpiar datos previos
        AlquilerDetalle::query()->delete();
        Alquiler::query()->delete();
        ReservaDetalle::query()->delete();
        Reserva::query()->delete();

        // Crear reserva con sistema financiero nuevo
        $reserva = Reserva::create([
            'numero_reserva' => 'TEST-2025-001',
            'cliente_id' => 1, // Debe existir
            'tipo_reserva' => 'ALQUILER',
            'fecha_reserva' => now(),
            'fecha_vencimiento' => now()->addDays(7),
            'subtotal' => 600.00,
            'descuento' => 50.00,
            'impuestos' => 82.50, // 15% sobre (600-50)
            'costos_adicionales' => 120.00,
            'detalle_costos_adicionales' => json_encode([
                ['concepto' => 'Seguro trajes', 'monto' => 80.00, 'fecha' => now()],
                ['concepto' => 'Limpieza especializada', 'monto' => 40.00, 'fecha' => now()]
            ]),
            'total' => 752.50, // 600 - 50 + 82.50 + 120
            'anticipo' => 300.00,
            'observaciones' => 'Prueba sistema financiero integrado',
            'sucursal_id' => 1, // Debe existir
            'usuario_creacion_id' => 1, // Debe existir
            'estado' => 'ACTIVA',
        ]);

        // Recalcular usando el trait
        $reserva->actualizarCalculosFinancieros();

        $this->command->info("✅ Reserva creada: {$reserva->numero_reserva}");
        $this->command->info("   💰 Subtotal: \${$reserva->subtotal}");
        $this->command->info("   💰 Total: \${$reserva->total}");
        $this->command->info("   💰 Saldo pendiente: \${$reserva->saldo_pendiente}");

        // Convertir a alquiler si es posible
        if ($reserva->puedeConvertirseAAlquiler()) {
            $alquiler = $reserva->convertirAAlquiler([
                'fecha_alquiler' => now()->addDay(),
                'fecha_devolucion_programada' => now()->addDays(5),
                'dias_alquiler' => 4,
                'anticipo' => 200.00, // Pago adicional al convertir
                'requiere_deposito' => true,
                'deposito_garantia' => 150.00,
                'ajuste_conversion' => 25.00,
                'motivo_ajuste' => 'Días adicionales solicitados'
            ]);

            $this->command->info("✅ Alquiler creado: {$alquiler->numero_contrato}");
            $this->command->info("   🔄 Anticipo reserva: \${$alquiler->anticipo_reserva}");
            $this->command->info("   🔄 Anticipo adicional: \${$alquiler->anticipo}");
            $this->command->info("   🔄 Ajuste conversión: \${$alquiler->ajuste_conversion}");
            $this->command->info("   🛡️  Depósito garantía: \${$alquiler->deposito_garantia}");
            $this->command->info("   💰 Total final: \${$alquiler->total}");
            $this->command->info("   💰 Saldo pendiente: \${$alquiler->saldo_pendiente}");

            // Probar métodos financieros
            $this->command->info('🧮 Probando métodos financieros...');

            // Aplicar costo adicional
            $alquiler->aplicarCostoAdicional(30.00, 'Transporte especial');
            $this->command->info("   ✅ Costo adicional: +$30.00");
            $this->command->info("   💰 Nuevo total: \${$alquiler->total}");

            // Registrar pago
            $alquiler->registrarPago(100.00, 'transferencia', 'TXN-TEST-001');
            $this->command->info("   ✅ Pago registrado: +$100.00");
            $this->command->info("   💰 Saldo pendiente: \${$alquiler->saldo_pendiente}");

            $pagado = $alquiler->estaCompletamentePagado() ? 'PAGADO' : 'PENDIENTE';
            $this->command->info("   📊 Estado: {$pagado}");

            $resumen = $alquiler->getResumenFinanciero();
            $this->command->info('   📊 Porcentaje pagado: ' . number_format($resumen['porcentaje_pagado'], 1) . '%');

            $this->command->info('🎉 ¡Sistema financiero funcionando correctamente!');

        } else {
            $this->command->info('❌ La reserva no puede convertirse a alquiler');
        }
    }
}