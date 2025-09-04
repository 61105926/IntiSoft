<?php

namespace Database\Seeders;

use App\Models\TipoGarantia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TiposGarantiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🛡️  Creando tipos de garantía...');

        $tiposGarantia = [
            [
                'nombre' => 'Carnet de Identidad',
                'descripcion' => 'Cédula de identidad como garantía personal',
                'requiere_monto' => false,
                'monto_minimo' => 0,
                'monto_maximo' => 0,
                'dias_devolucion' => 7,
                'activo' => true,
            ],
            [
                'nombre' => 'Depósito en Efectivo',
                'descripcion' => 'Garantía monetaria en efectivo',
                'requiere_monto' => true,
                'monto_minimo' => 50.00,
                'monto_maximo' => 2000.00,
                'dias_devolucion' => 30,
                'activo' => true,
            ],
            [
                'nombre' => 'Cheque Garantía',
                'descripcion' => 'Cheque personal como garantía de pago',
                'requiere_monto' => true,
                'monto_minimo' => 100.00,
                'monto_maximo' => 5000.00,
                'dias_devolucion' => 15,
                'activo' => true,
            ],
            [
                'nombre' => 'Documento de Propiedad',
                'descripcion' => 'Título de propiedad de vehículo u otros bienes',
                'requiere_monto' => false,
                'monto_minimo' => 0,
                'monto_maximo' => 0,
                'dias_devolucion' => 30,
                'activo' => true,
            ],
            [
                'nombre' => 'Prenda Física',
                'descripcion' => 'Objeto físico de valor como garantía',
                'requiere_monto' => true,
                'monto_minimo' => 20.00,
                'monto_maximo' => 1000.00,
                'dias_devolucion' => 60,
                'activo' => true,
            ],
            [
                'nombre' => 'Aval Personal',
                'descripcion' => 'Garantía personal de tercero con documentos',
                'requiere_monto' => false,
                'monto_minimo' => 0,
                'monto_maximo' => 0,
                'dias_devolucion' => 7,
                'activo' => true,
            ],
            [
                'nombre' => 'Depósito Bancario',
                'descripcion' => 'Boleta de garantía bancaria',
                'requiere_monto' => true,
                'monto_minimo' => 500.00,
                'monto_maximo' => 10000.00,
                'dias_devolucion' => 90,
                'activo' => true,
            ],
        ];

        foreach ($tiposGarantia as $tipo) {
            TipoGarantia::create($tipo);
            $this->command->info("   ✅ {$tipo['nombre']}");
        }

        $this->command->info('✅ Tipos de garantía creados exitosamente:');
        $this->command->info('   🆔 Documentales: CI, Documentos de propiedad, Aval personal');
        $this->command->info('   💰 Monetarias: Efectivo, Cheque, Depósito bancario');
        $this->command->info('   📦 Físicas: Prendas de valor');
        $this->command->info('   ⏱️  Diferentes períodos de devolución según el tipo');
    }
}