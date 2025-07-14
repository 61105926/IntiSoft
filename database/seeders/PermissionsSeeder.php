<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        try {
            DB::beginTransaction();

            // Limpiar solo las tablas de permisos y roles
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('model_has_permissions')->truncate();
            DB::table('model_has_roles')->truncate();
            DB::table('role_has_permissions')->truncate();
            DB::table('permissions')->truncate();
            DB::table('roles')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            // Crear permisos básicos
            $permissions = [
                'dashboard.view',
                'user.view',
                'user.create',
                'user.edit',
                'user.delete',
                'pet.view',
                'pet.create',
                'pet.edit',
                'pet.delete',
                'pet.detail',
                'breed.view',
                'breed.create',
                'breed.edit',
                'breed.delete',
                'species.view',
                'species.create',
                'species.edit',
                'species.delete',
                'vaccine.view',
                'vaccine.create',
                'vaccine.edit',
                'vaccine.delete',
                'client.view',
                'client.create',
                'client.edit',
                'client.delete',
                'inventory.view',
                'inventory.create',
                'inventory.edit',
                'inventory.delete',
                'sale.view',
                'sale.create',
                'sale.edit',
                'sale.delete',
                'appointment.view',
                'appointment.create',
                'appointment.edit',
                'appointment.delete',
                'provider.view',
                'provider.create',
                'provider.edit',
                'provider.delete',
                'purchase.view',
                'purchase.create',
                'purchase.edit',
                'purchase.delete',
                'cash.view',
                'cash.create'
            ];

            // Crear los permisos
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission]);
            }

            // Crear rol de administrador
            $role = Role::create(['name' => 'Admin']);
            $role->givePermissionTo(Permission::all());

            // Obtener el primer usuario
            $user = User::first();

            if (!$user) {
                throw new \Exception('No se encontró ningún usuario en la base de datos');
            }

            // Asignar rol admin al primer usuario
            $user->assignRole($role);


        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
