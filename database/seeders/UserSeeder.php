<?php

namespace Database\Seeders;

use App\Models\CategoriaProducto;
use App\Models\User;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('empresas')->insert([
            [
                'razon_social' => 'Empresa Folklore S.R.L.',
                'nit' => '123456789',

                'direccion' => 'Av. Principal #100, La Paz',
                'telefono' => '71234567',
                'email' => 'contacto@folklore.com',
                'logo_url' => 'https://example.com/logo1.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        // Crear usuario administrador
        DB::table('sucursals')->insert([
            [
                'nombre' => 'LaPaz Central',
                'empresa_id' => 1,
                'codigo' => 123123,

                'direccion' => 'Av. Principal #123',
                'telefono' => '12345678',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Puedes agregar más sucursales aquí
        ]);
        DB::table('sucursals')->insert([
            [
                'nombre' => 'alto sur',
                'empresa_id' => 1,
                'codigo' => 23232,

                'direccion' => 'Av. Principal #123',
                'telefono' => '12345678',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Puedes agregar más sucursales aquí
        ]);
        $categorias = ['TRAJES FOLKLORICOS', 'POLLERAS', 'ACCESSORIOS DANZA', 'INSTRUMENTOS', 'CALZADOS', 'SOMBREROS ENSAYO'];

        foreach ($categorias as $nombre) {
            CategoriaProducto::create([
                'nombre' => strtoupper($nombre),
                'descripcion' => 'CATEGORÍA: ' . strtoupper($nombre),
                'activa' => true,
            ]);
        }
        DB::table('users')->insert([
            [
                'sucursal_id' => 1,
                'username' => 'gabriel',
                'email' => 'gabo@gmail.com',
                'password' => Hash::make('61105926'), // Este campo debe llamarse 'password'
                'nombres' => 'Gabriel',
                'apellidos' => 'Perez',
                'telefono' => '76543210',
                'activo' => true,
                'ultimo_login' => null,
                'intentos_fallidos' => 0,
                'bloqueado_hasta' => null,
                'email_verified_at' => now(),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $productos = [
            [
                'sucursal_id' => 1,
                'categoria_id' => 1, // TRAJES FOLKLORICOS
                'codigo' => 'TF-001',
                'nombre' => 'Traje Morenada Niño',
                'descripcion' => 'Traje típico para danza Morenada (talla niño).',
                'precio_venta' => 500.0,
                'precio_alquiler' => 80.0,
                'costo_promedio' => 300.0,
                'margen_venta' => 40.0,
                'margen_alquiler' => 20.0,
                'talla' => '10',
                'color' => 'Azul con dorado',
                'material' => 'Tela bordada',
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'stock_actual' => 5,
                'stock_minimo' => 1,
                'stock_reservado' => 0,
                'usuario_creacion' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sucursal_id' => 2,
                'categoria_id' => 2, // POLLERAS
                'codigo' => 'PL-015',
                'nombre' => 'Pollera Tinku Mujer',
                'descripcion' => 'Pollera colorida típica para Tinku (adulto).',
                'precio_venta' => 300.0,
                'precio_alquiler' => 60.0,
                'costo_promedio' => 150.0,
                'margen_venta' => 50.0,
                'margen_alquiler' => 30.0,
                'talla' => 'M',
                'color' => 'Multicolor',
                'material' => 'Bayeta',
                'disponible_venta' => true,
                'disponible_alquiler' => true,
                'stock_actual' => 8,
                'stock_minimo' => 2,
                'stock_reservado' => 1,
                'usuario_creacion' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sucursal_id' => 1,
                'categoria_id' => 3, // ACCESSORIOS DANZA
                'codigo' => 'AC-003',
                'nombre' => 'Máscara Diablada',
                'descripcion' => 'Máscara con cuernos para danza Diablada.',
                'precio_venta' => 200.0,
                'precio_alquiler' => 40.0,
                'costo_promedio' => 120.0,
                'margen_venta' => 35.0,
                'margen_alquiler' => 25.0,
                'talla' => null,
                'color' => 'Rojo con negro',
                'material' => 'Fibra de vidrio',
                'disponible_venta' => false,
                'disponible_alquiler' => true,
                'stock_actual' => 3,
                'stock_minimo' => 1,
                'stock_reservado' => 0,
                'usuario_creacion' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('productos')->insert($productos);
    }
}
