<?php

namespace App\Http\Livewire\Raza;

use App\Models\Especie;
use App\Models\Raza;
use Livewire\Component;
use Livewire\WithPagination;

class RazaController extends Component
{
    use WithPagination;

    public $componentName;
    public $selected_id;
    public $pageTitle;
    private $pagination = 10;
    public $search;
    public $nombre;
    public $especie_id; // Para almacenar la especie seleccionada

    public function mount()
    {
        $this->pageTitle = 'Listado de Razas';
        $this->componentName = 'Raza';
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function searchRazas()
    {
        $query = Raza::query();

        if (strlen($this->search) > 0) {
            $query->where('nombre', 'like', '%' . $this->search . '%');
            $this->resetPage();
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($this->pagination);
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'especie_id' => 'required|exists:especies,id', // Asegúrate de que la especie exista
        ];
    }

    public function store()
    {
        $this->validate($this->rules());

        Raza::create([
            'nombre' => $this->nombre,
            'especie_id' => $this->especie_id,
        ]);

        $this->resetUI();
        $this->emit('person-added', 'Raza Registrada');
        $this->emit('mostrarAlertaSuccess', 'Raza Registrada');
    }

    public function edit(Raza $raza)
    {
        $this->selected_id = $raza->id;
        $this->nombre = $raza->nombre; // Asigna el nombre a la propiedad
        $this->especie_id = $raza->especie_id; // Asigna la especie a la propiedad
        $this->emit('show-modal', 'show');
    }

    public function update()
    {
        $this->validate($this->rules());

        $raza = Raza::find($this->selected_id);

        if ($raza) {
            $raza->update([
                'nombre' => $this->nombre,
                'especie_id' => $this->especie_id,
            ]);

            $this->resetUI();
            $this->emit('person-updated', 'Raza Actualizada');
            $this->emit('mostrarAlertaSuccess', 'Raza Actualizada');
        } else {
            $this->emit('razas-not-found', 'Raza no encontrada');
        }
    }

    protected $listeners = [
        'deleteRow' => 'destroy',
    ];

    public function destroy(Raza $raza)
    {
        $raza->state = $raza->state == 0 ? 1 : 0; // Cambia el estado a 0 en lugar de eliminar
        $raza->save();
        $this->resetUI();
        $message = $raza->state == 1 ? 'Raza Habilitada' : 'Raza Deshabilitada';
        $this->emit('mostrarAlertaSuccess', $message);    }

    public function resetUI()
    {
        $this->reset();
        $this->componentName = 'Raza';
        $this->especie_id = null; // Reinicia la especie seleccionada
    }

    public function render()
    {
        $data = $this->searchRazas();
        $especies = Especie::where('state', 1)->get(); // Obtener todas las especies para el formulario

        return view('livewire.raza.raza', [
            'data' => $data,
            'especies' => $especies, // Pasar las especies a la vista
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
