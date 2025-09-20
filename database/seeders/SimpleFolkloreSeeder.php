<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SimpleFolkloreSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🎭 Insertando datos básicos...');

        // 1. Insertar categorías (solo campos que existen)
        $this->command->info('🏷️ Creando categorías...');
        DB::table('categoria_productos')->insert([
            [
                'nombre' => 'Polleras',
                'descripcion' => 'Polleras tradicionales bolivianas',
                'activa' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Sombreros',
                'descripcion' => 'Sombreros y tocados folklóricos',
                'activa' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Accesorios',
                'descripcion' => 'Accesorios folklóricos',
                'activa' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Insertar productos básicos
        $this->command->info('📦 Creando productos...');
        DB::table('productos')->insert([
            [
                'categoria_id' => 1,
                'codigo' => 'PLR-001',
                'nombre' => 'Pollera Tradicional',
                'descripcion' => 'Pollera tradicional de cholita paceña',
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
                'nombre' => 'Sombrero Bombín',
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
                'nombre' => 'Aguayo Tradicional',
                'descripcion' => 'Aguayo boliviano multicolor',
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

        // 3. Insertar clientes básicos (verificar estructura primero)
        $this->command->info('👥 Creando clientes...');
        DB::table('clientes')->insert([
            [
                'nombres' => 'Ana María',
                'apellidos' => 'González Pérez',
                'telefono' => '70111222',
                'email' => 'ana.gonzalez@email.com',
                'direccion' => 'Calle Comercio #123, La Paz',
                'ci' => '12345678',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombres' => 'Luis Fernando',
                'apellidos' => 'Mamani Quispe',
                'telefono' => '70333444',
                'email' => 'luis.mamani@email.com',
                'direccion' => 'Av. Buenos Aires #456, La Paz',
                'ci' => '87654321',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 4. Si hay sucursales, crear stock básico
        $sucursales = DB::table('sucursals')->get();
        $productos = DB::table('productos')->get();

        if ($sucursales->count() > 0 && $productos->count() > 0) {
            $this->command->info('📊 Creando stock...');

            foreach ($productos as $producto) {
                foreach ($sucursales as $sucursal) {
                    DB::table('stock_por_sucursals')->insert([
                        'producto_id' => $producto->id,
                        'sucursal_id' => $sucursal->id,
                        'stock_actual' => rand(5, 25),
                        'stock_minimo' => 3,
                        'precio_venta_sucursal' => rand(200, 800),
                        'precio_alquiler_sucursal' => rand(50, 200),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // 5. Crear algunos movimientos de ejemplo
            $this->command->info('📈 Creando movimientos...');
            DB::table('movimiento_stock_sucursals')->insert([
                [
                    'producto_id' => $productos->first()->id,
                    'sucursal_id' => $sucursales->first()->id,
                    'tipo_movimiento' => 'ENTRADA',
                    'cantidad' => 15,
                    'stock_anterior' => 0,
                    'stock_nuevo' => 15,
                    'valor_unitario' => 450.00,
                    'referencia' => 'INV-001',
                    'motivo' => 'Inventario inicial',
                    'observaciones' => 'Carga inicial de productos',
                    'usuario_id' => 1,
                    'fecha_movimiento' => Carbon::now()->subDays(3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'producto_id' => $productos->first()->id,
                    'sucursal_id' => $sucursales->first()->id,
                    'tipo_movimiento' => 'VENTA',
                    'cantidad' => -2,
                    'stock_anterior' => 15,
                    'stock_nuevo' => 13,
                    'valor_unitario' => 550.00,
                    'referencia' => 'VEN-001',
                    'motivo' => 'Venta directa',
                    'observaciones' => 'Venta a cliente Ana González',
                    'usuario_id' => 1,
                    'fecha_movimiento' => Carbon::now()->subDays(1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }

        $this->command->info('✅ Datos básicos insertados correctamente!');
        $this->command->info('📊 Verificando datos:');
        $this->command->info('   - Categorías: ' . DB::table('categoria_productos')->count());
        $this->command->info('   - Productos: ' . DB::table('productos')->count());
        $this->command->info('   - Clientes: ' . DB::table('clientes')->count());
        $this->command->info('   - Stock: ' . DB::table('stock_por_sucursals')->count());
        $this->command->info('   - Movimientos: ' . DB::table('movimiento_stock_sucursals')->count());
    }
}