<?php

namespace App\Http\Livewire\Client;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ClientController extends Component
{
    use WithPagination;
    public $componentName;
    public $selected_id;
    public $pageTitle;



    public $nombre_completo;
    public $ci;
    public $nacionalidad;
    public $sexo;
    public $fecha_nacimiento;
    public $direccion;
    public $numero_telefono;
    public $numero_telefono2;
    public $correo;
    public $search;
    private $pagination = 10;

    public function mount()
    {
        $this->pageTitle = 'listado';
        $this->componentName = 'Clientes';
    }
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    public function searchClients()
    {
        $query = Client::query();

        // Aplicar búsqueda si hay un término de búsqueda
        if (strlen($this->search) > 0) {
            $query->where(function ($query) {
                $query->where('nombre_completo', 'like', '%' . $this->search . '%')
                    ->orWhere('ci', 'like', '%' . $this->search . '%');
            });
            $this->resetPage();
        } else {
            $query->orderBy('id', 'desc');
        }
        return $query->paginate($this->pagination);
    }
    public function rules()
    {
        return [
            'nombre_completo' => 'required',
            'nacionalidad' => 'required',
            'fecha_nacimiento' => 'nullable|date',
            'direccion' => 'required',
            'numero_telefono' => 'required',
            'numero_telefono2' => 'nullable',
            'correo' => 'nullable',
            'sexo' => 'required',
        ];
    }

    public function store()
    {
        $this->validate();

        $person = new Client();

        // Asigna los valores del formulario a las propiedades del modelo person
        $person->nombre_completo = $this->nombre_completo;
        $person->ci = $this->ci;
        $person->nacionalidad = $this->nacionalidad;
        $person->fecha_nacimiento = $this->fecha_nacimiento;
        $person->direccion = $this->direccion;
        $person->numero_telefono = $this->numero_telefono;
        $person->numero_telefono2 = $this->numero_telefono2;
        $person->correo = $this->correo;
        $person->sexo = $this->sexo;

        // Guarda el nuevo usuario en la base de datos
        $person->save();
        $this->resetUI();

        $this->emit('person-added', 'Cliente Registrado');
        $this->emit('mostrarAlertaSuccess', 'Cliente Registrado');
    }
    public function edit(Client $client)
    {
        $this->selected_id = $client->id;

        $this->fill($client->toArray());
        $this->emit('show-modal', 'show');
    }
    public function update()
    {
        $person = Client::find($this->selected_id);

        if ($person) {
            // Asigna los nuevos valores del formulario al modelo person
            $person->nombre_completo = $this->nombre_completo;
            $person->ci = $this->ci;
            $person->nacionalidad = $this->nacionalidad;
            $person->fecha_nacimiento = $this->fecha_nacimiento;
            $person->direccion = $this->direccion;
            $person->numero_telefono = $this->numero_telefono;
            $person->numero_telefono2 = $this->numero_telefono2;
            $person->correo = $this->correo;
            $person->sexo = $this->sexo;

            // Guarda los cambios en el modelo
            $person->save();

            $this->resetUI();

            $this->emit('person-updated', 'Cliente Actualizado');
            $this->emit('mostrarAlertaSuccess', 'Cliente Actualizado');

        } else {
            $this->emit('person-not-found', 'Cliente no encontrada');
        }
    }

    protected $listeners = [
        'deleteRow' => 'destroy',
    ];
    public function destroy(Client $client)
    {
        $client->state = $client->state == 0 ? 1 : 0;

        $client->save();
        $items = $client->item;
        // Iterar sobre cada item y cambiar su estado
        if ($items) {

            $items->state = 0;
            $items->save();
        }



        // Restablecer la interfaz de usuario si es necesario
        $this->resetUI();
        $this->emit('mostrarAlertaSuccess', 'Cliente Eliminada');
    }

    public function resetUI()
    {
        $this->reset();
        $this->componentName = 'Mascota';
    }


    public function render()
    {
        $data = $this->searchClients();

        return view('livewire.client.client', ['data' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

}
