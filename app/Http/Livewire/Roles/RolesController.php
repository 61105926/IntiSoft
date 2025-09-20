<?php

namespace App\Http\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class RolesController extends Component
{
    use WithPagination;

    public $roleName;
    public $selected_id;
    public $pageTitle;
    public $componentName;
    public $selectedPermissions = [];
    private $pagination = 5;

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Roles';
        $this->selectedPermissions = [];
    }

    public function render()
    {
        $permissions = Permission::select('id', 'name')->get();
        $roles = Role::orderBy('name', 'asc')->paginate($this->pagination);

        return view('livewire.roles.roles', [
            'roles' => $roles,
            'permissions' => $permissions
        ])->extends('layouts.theme.modern-app')->section('content');
    }

    public function store()
    {
        try {
            DB::beginTransaction();

            $rules = ['roleName' => 'required|min:2|unique:roles,name'];
            $this->validate($rules);

            if (empty($this->selectedPermissions)) {
                throw new \Exception('Debe seleccionar al menos un permiso');
            }

            // Crear el rol
            $role = Role::create([
                'name' => $this->roleName
            ]);

            // Asignar permisos seleccionados
            $role->syncPermissions($this->selectedPermissions);

            DB::commit();

            $this->emit('role-added', 'Rol creado correctamente');
            $this->resetUI();

        } catch (\Exception $e) {
            DB::rollback();
            $this->emit('role-error', $e->getMessage());
        }
    }

    public function edit(Role $role)
    {
        $this->selected_id = $role->id;
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->emit('show-modal', 'Show Modal!');
    }

    public function update()
    {
        try {
            DB::beginTransaction();

            $rules = [
                'roleName' => "required|min:2|unique:roles,name,{$this->selected_id}"
            ];
            $this->validate($rules);

            if (empty($this->selectedPermissions)) {
                throw new \Exception('Debe seleccionar al menos un permiso');
            }

            $role = Role::find($this->selected_id);
            $role->name = $this->roleName;
            $role->save();

            // Sincronizar permisos
            $role->syncPermissions($this->selectedPermissions);

            DB::commit();

            $this->emit('role-updated', 'Rol actualizado correctamente');
            $this->resetUI();

        } catch (\Exception $e) {
            DB::rollback();
            $this->emit('role-error', $e->getMessage());
        }
    }

    protected $listeners = ['deleteRow' => 'destroy'];

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $role = Role::find($id);
            
            if ($role->name === 'Admin') {
                throw new \Exception('No se puede eliminar el rol Admin');
            }

            $role->delete();

            DB::commit();

            $this->emit('role-deleted', 'Rol eliminado correctamente');

        } catch (\Exception $e) {
            DB::rollback();
            $this->emit('role-error', $e->getMessage());
        }
    }

    public function resetUI()
    {
        $this->roleName = '';
        $this->selected_id = 0;
        $this->selectedPermissions = [];
        $this->resetValidation();
        $this->emit('hide-modal');
    }
}
