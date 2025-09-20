<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BasicDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🎭 Insertando datos básicos de prueba...');

        // 1. Categorías
        $this->command->info('📦 Insertando categorías...');
        DB::table('categoria_productos')->insertOrIgnore([
            [
                'nombre' => 'Polleras',
                'descripcion' => 'Polleras tradicionales',
                'activa' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Sombreros',
                'descripcion' => 'Sombreros folklóricos',
                'activa' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Accesorios',
                'descripcion' => 'Accesorios varios',
                'activa' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Productos
        $this->command->info('🎽 Insertando productos...');
        DB::table('productos')->insertOrIgnore([
            [
                'categoria_id' => 1,
                'codigo' => 'PLR-001',
                'nombre' => 'Pollera Tradicional Verde',
                'descripcion' => 'Pollera tradicional boliviana',
                'talla' => 'M',
                'color' => 'Verde',
                'material' => 'Terciopelo',
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => 1,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categoria_id' => 2,
                'codigo' => 'SOM-001',
                'nombre' => 'Sombrero Bombín Negro',
                'descripcion' => 'Sombrero bombín tradicional',
                'talla' => 'Único',
                'color' => 'Negro',
                'material' => 'Fieltro',
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'usuario_creacion' => 1,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'categoria_id' => 3,
                'codigo' => 'ACC-001',
                'nombre' => 'Aguayo Multicolor',
                'descripcion' => 'Aguayo tradicional boliviano',
                'talla' => 'Único',
                'color' => 'Multicolor',
                'material' => 'Lana',
                'disponible_venta' => true,
                'disponible_alquiler' => false,
                'usuario_creacion' => 1,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 3. Clientes (con estructura correcta)
        $this->command->info('👥 Insertando clientes...');
        $sucursal = DB::table('sucursals')->first();

        if ($sucursal) {
            DB::table('clientes')->insertOrIgnore([
                [
                    'sucursal_id' => $sucursal->id,
                    'tipo_cliente' => 'NATURAL',
                    'nombres' => 'Ana María',
                    'apellidos' => 'González Pérez',
                    'carnet_identidad' => '12345678',
                    'telefono' => '70111222',
                    'email' => 'ana.gonzalez@email.com',
                    'direccion' => 'Calle Comercio #123, La Paz',
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'sucursal_id' => $sucursal->id,
                    'tipo_cliente' => 'NATURAL',
                    'nombres' => 'Carlos',
                    'apellidos' => 'Mamani Quispe',
                    'carnet_identidad' => '87654321',
                    'telefono' => '70333444',
                    'email' => 'carlos.mamani@email.com',
                    'direccion' => 'Av. Buenos Aires #456, La Paz',
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }

        // 4. Stock (si hay sucursales)
        $sucursales = DB::table('sucursals')->get();
        $productos = DB::table('productos')->get();

        if ($sucursales->count() > 0 && $productos->count() > 0) {
            $this->command->info('📊 Creando inventario...');

            foreach ($productos as $producto) {
                foreach ($sucursales as $sucursal) {
                    DB::table('stock_por_sucursals')->insertOrIgnore([
                        'producto_id' => $producto->id,
                        'sucursal_id' => $sucursal->id,
                        'stock_actual' => rand(10, 30),
                        'stock_minimo' => 5,
                        'precio_venta_sucursal' => rand(200, 800),
                        'precio_alquiler_sucursal' => rand(50, 200),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 5. Movimientos de inventario
        if ($productos->count() > 0 && $sucursales->count() > 0) {
            $this->command->info('📈 Creando historial...');

            DB::table('movimiento_stock_sucursals')->insertOrIgnore([
                [
                    'producto_id' => $productos->first()->id,
                    'sucursal_id' => $sucursales->first()->id,
                    'tipo_movimiento' => 'ENTRADA',
                    'cantidad' => 20,
                    'stock_anterior' => 0,
                    'stock_nuevo' => 20,
                    'valor_unitario' => 450.00,
                    'referencia' => 'INV-001',
                    'motivo' => 'Inventario inicial',
                    'observaciones' => 'Carga inicial de productos',
                    'usuario_id' => 1,
                    'fecha_movimiento' => Carbon::now()->subDays(5),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'producto_id' => $productos->first()->id,
                    'sucursal_id' => $sucursales->first()->id,
                    'tipo_movimiento' => 'VENTA',
                    'cantidad' => -3,
                    'stock_anterior' => 20,
                    'stock_nuevo' => 17,
                    'valor_unitario' => 550.00,
                    'referencia' => 'VEN-001',
                    'motivo' => 'Venta a cliente',
                    'observaciones' => 'Venta directa',
                    'usuario_id' => 1,
                    'fecha_movimiento' => Carbon::now()->subDays(2),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }

        $this->command->info('✅ ¡Datos de prueba insertados correctamente!');
        $this->command->info('');
        $this->command->info('📋 Resumen:');
        $this->command->info('   📦 Categorías: ' . DB::table('categoria_productos')->count());
        $this->command->info('   🎽 Productos: ' . DB::table('productos')->count());
        $this->command->info('   👥 Clientes: ' . DB::table('clientes')->count());
        $this->command->info('   🏪 Sucursales: ' . DB::table('sucursals')->count());
        $this->command->info('   📊 Inventario: ' . DB::table('stock_por_sucursals')->count() . ' registros');
        $this->command->info('   📈 Movimientos: ' . DB::table('movimiento_stock_sucursals')->count());
        $this->command->info('');
        $this->command->info('🎉 ¡Ahora puedes probar el sistema!');
    }
}