<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstanciaConjuntoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear instancias para las variaciones existentes
        $variaciones = [
            // Caporales Masculino - variaciones 1, 2, 3
            1 => 15, // S - 15 instancias
            2 => 20, // M - 20 instancias
            3 => 18, // L - 18 instancias

            // Caporales Femenino - variaciones 4, 5, 6
            4 => 12, // XS - 12 instancias
            5 => 16, // S - 16 instancias
            6 => 14, // M - 14 instancias
        ];

        $sucursales = [1, 2]; // Asumiendo que existen sucursales con ID 1 y 2

        foreach ($variaciones as $variacionId => $cantidad) {
            for ($i = 1; $i <= $cantidad; $i++) {
                $estados = ['DISPONIBLE', 'ALQUILADO', 'EN_LIMPIEZA'];
                $estadoAleatorio = $estados[array_rand($estados)];

                // Más probabilidad de estar disponible
                if (rand(1, 100) <= 70) {
                    $estadoAleatorio = 'DISPONIBLE';
                }

                \DB::table('instancias_conjunto')->insert([
                    'variacion_conjunto_id' => $variacionId,
                    'numero_serie' => 'INST-' . str_pad($variacionId, 3, '0', STR_PAD_LEFT) . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'codigo_interno' => 'INT-' . $variacionId . '-' . $i,
                    'sucursal_id' => $sucursales[array_rand($sucursales)],
                    'estado_fisico' => ['EXCELENTE', 'BUENO', 'REGULAR'][array_rand(['EXCELENTE', 'BUENO', 'REGULAR'])],
                    'estado_disponibilidad' => $estadoAleatorio,
                    'fecha_adquisicion' => now()->subDays(rand(30, 365)),
                    'fecha_ultimo_uso' => $estadoAleatorio === 'DISPONIBLE' ? now()->subDays(rand(1, 30)) : null,
                    'total_usos' => rand(5, 50),
                    'total_ingresos' => rand(500, 5000),
                    'ubicacion_almacen' => 'ESTANTE-' . chr(65 + rand(0, 4)) . '-' . rand(1, 10),
                    'usuario_creacion' => 1,
                    'activa' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        echo "Instancias creadas exitosamente!\n";
        echo "Total instancias: " . array_sum($variaciones) . "\n";
    }
}
