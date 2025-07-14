<?php

namespace App\Http\Livewire\Especie;

use App\Models\Especie;
use Livewire\Component;
use Livewire\WithPagination;

class EspecieController extends Component
{
    use WithPagination;

    public $componentName;
    public $selected_id;
    public $pageTitle;
    private $pagination = 10;
    public $search;
    public $nombre;

    public function mount()
    {
        $this->pageTitle = 'Listado de Especies';
        $this->componentName = 'Especies';
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function searchSpecies()
    {
        $query = Especie::query();

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
        ];
    }

    public function store()
    {
        $this->validate($this->rules());

        Especie::create(['nombre' => $this->nombre]);

        $this->resetUI();
        $this->emit('person-added', 'Especie Registrada');
        $this->emit('mostrarAlertaSuccess', 'Especie Registrada');
    }

    public function edit(Especie $especie)
    {
        $this->selected_id = $especie->id;
        $this->nombre = $especie->nombre; // Asigna el nombre a la propiedad
        $this->emit('show-modal', 'show');
    }

    public function update()
    {
        $this->validate($this->rules());

        $especie = Especie::find($this->selected_id);

        if ($especie) {
            $especie->update(['nombre' => $this->nombre]);

            $this->resetUI();
            $this->emit('person-updated', 'Especie Actualizada');
            $this->emit('mostrarAlertaSuccess', 'Especie Actualizada');
        } else {
            $this->emit('species-not-found', 'Especie no encontrada');
        }
    }

    protected $listeners = [
        'deleteRow' => 'destroy',
    ];

    public function destroy(Especie $especie)
    {
        $especie->state = $especie->state == 0 ? 1 : 0;
        $especie->save();
        $this->resetUI();
        $message = $especie->state == 1 ? 'Especie Habilitada' : 'Especie Deshabilitada';
        $this->emit('mostrarAlertaSuccess', $message);
    }

    public function resetUI()
    {
        $this->reset();
        $this->componentName = 'Especie';
    }

    public function render()
    {
        $data = $this->searchSpecies();

        return view('livewire.especie.especie', ['data' => $data]) // Pasar los clientes a la vista
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
