<?php

namespace App\Http\Livewire\Usuario;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UsuarioController extends Component
{
    use WithPagination;
    public $componentName;
    public $selected_id;
    public $pageTitle;



    public $names;
    public $last_name;
    public $second_last_name;
    public $birthdate;
    public $nationality;
    public $address;
    public $city;
    public $gender;
    public $email;
    public $password;
    public $post;


    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    public function mount()
    {
        $this->pageTitle = 'listado';
        $this->componentName = 'Usuario';
    }
    public function generateCode()
    {
        $prefix = 'US-';

        // Obtener el último contador para el año actual.
        $counter = User::max('id') + 1;

        return $prefix . $counter;
    }
    public function rules()
    {
        return [
            'names' => 'required',
            'last_name' => 'required',
            'second_last_name' => 'required',
            'birthdate' => 'required',
            'nationality' => 'required',
            'address' => 'required',
            'city' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8', // Establece la longitud mínima del campo de contraseña a 6 caracteres, ajusta según sea necesario
        ];
    }
    public function store()
    {
        $this->validate();

        $user = new User();

        // Asigna los valores del formulario a las propiedades del modelo User
        $user->user_code = $this->generateCode();
        $user->names = $this->names;
        $user->last_name = $this->last_name;
        $user->second_last_name = $this->second_last_name;
        $user->birthdate = $this->birthdate;
        $user->nationality = $this->nationality;
        $user->ci = '12345678';
        $user->address = $this->address;
        $user->city = $this->city;
        $user->gender = $this->gender;
        $user->email = $this->email;
        $user->password = $this->password;

        $user->post = $this->post;

        // Guarda el nuevo usuario en la base de datos
        $user->assignRole($this->post);
        $user->save();

        $this->resetUI();

        $this->emit('user-added', 'Usuario Registrado');
    }
    public function edit(User $user)
    {
        $this->selected_id = $user->id;

        $this->fill($user->toArray());
        $this->emit('show-modal', 'show');
    }
    public function update()
    {
        $user = User::find($this->selected_id);

        // Asigna los nuevos valores del formulario al modelo User
        $user->names = $this->names;
        $user->last_name = $this->last_name;
        $user->second_last_name = $this->second_last_name;
        $user->birthdate = $this->birthdate;
        $user->nationality = $this->nationality;
        $user->address = $this->address;
        $user->city = $this->city;
        $user->gender = $this->gender;
        $user->email = $this->email;
        $user->password = $this->password;

        $user->post = $this->post;

        $user->syncRoles([$this->post]);


        $user->save();

        $this->resetUI();

        $this->emit('user-updated', 'Cliente Actualizada');
    }
    protected $listeners = [
        'deleteRow' => 'destroy',
    ];
    public function destroy(User $user)
    {
        $user->state = $user->state == 0 ? 1 : 0;
        $user->save();

        // Restablecer la interfaz de usuario si es necesario
        $this->resetUI();
        $this->emit('user-delete', 'Cliente Eliminada');
    }
    private function searchData()
    {
        $query = User::query();

        // Aquí puedes agregar lógica de búsqueda adicional, como filtros, ordenamiento, etc.
        // Ejemplo: $query->where('status', 'active')->orderBy('created_at', 'desc');
        $query->orderBy('created_at', 'desc');

        return $query->paginate(10);
    }
    public function resetUI()
    {
        $this->reset();
        $this->componentName = 'Usuario';
    }
    public function render()
    {
        $roles = Role::all();

        $users = $this->searchData();
        return view('livewire.usuario.usuario', ['users' => $users, 'roles'
        => $roles])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
