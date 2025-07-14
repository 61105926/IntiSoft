<?php

namespace App\Http\Livewire\Person;

use App\Models\Person;
use Livewire\Component;
use Livewire\WithPagination;

class PersonController extends Component
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
    public function mount()
    {
        $this->pageTitle = 'listado';
        $this->componentName = 'Personal';
    }
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    public function generateCode()
    {
        $prefix = 'PER-';

        // Obtener el último contador para el año actual.
        $counter = Person::max('id') + 1;

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
        ];
    }
    public function store()
    {
        $this->validate();

        $person = new Person();

        // Asigna los valores del formulario a las propiedades del modelo person
        $person->code = $this->generateCode();
        $person->names = $this->names;
        $person->last_name = $this->last_name;
        $person->second_last_name = $this->second_last_name;
        $person->birthdate = $this->birthdate;
        $person->nationality = $this->nationality;
        $person->ci = '12345678';
        $person->address = $this->address;
        $person->city = $this->city;
        $person->gender = $this->gender;
        // Guarda el nuevo usuario en la base de datos
        $person->save();
        $this->resetUI();

        $this->emit('person-added', 'Usuario Registrado');
    }
    public function edit(Person $person)
    {
        $this->selected_id = $person->id;

        $this->fill($person->toArray());
        $this->emit('show-modal', 'show');
    }
    public function update()
    {
        $person = Person::find($this->selected_id);

        // Asigna los nuevos valores del formulario al modelo person
        $person->names = $this->names;
        $person->last_name = $this->last_name;
        $person->second_last_name = $this->second_last_name;
        $person->birthdate = $this->birthdate;
        $person->nationality = $this->nationality;
        $person->address = $this->address;
        $person->city = $this->city;
        $person->gender = $this->gender;


        $person->save();

        $this->resetUI();

        $this->emit('person-updated', 'Cliente Actualizada');
    }
    protected $listeners = [
        'deleteRow' => 'destroy',
    ];
    public function destroy(Person $person)
    {
        $person->state = $person->state == 0 ? 1 : 0;

        $person->save();
        $items = $person->item;
        // Iterar sobre cada item y cambiar su estado
        if ($items) {

            $items->state = 0;
            $items->save();
        }



        // Restablecer la interfaz de usuario si es necesario
        $this->resetUI();
        $this->emit('person-delete', 'Cliente Eliminada');
    }
    private function searchData()
    {
        $query = Person::query();

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
        $persons = $this->searchData();

        return view('livewire.person.person', ['persons' => $persons])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
