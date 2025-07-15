<?php

namespace Database\Seeders;

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
                'nombre' => 'Sucursal Central',
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
                'nombre' => 'Sucursal sur',
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
        DB::table('users')->insert([
            [
                'sucursal_id' => 1, // Suponiendo que ya existe la sucursal con ID 1
                'username' => 'gabriel',
                'email' => 'admin@gmail.com',
                'password_hash' => Hash::make('12345678'),
                'password' => null,
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
            // Más usuarios si quieres
        ]);
    }
}
