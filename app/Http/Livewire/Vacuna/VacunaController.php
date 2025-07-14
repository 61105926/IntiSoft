<?php

namespace App\Http\Livewire\Vacuna;

use App\Models\Especie;
use App\Models\Vacuna;
use Livewire\Component;

class VacunaController extends Component
{
    public $especies;
    public $nuevaVacuna;
    public $especieSeleccionada;
    public $colores;
    public $componentName = 'Vacuna';

    protected $rules = [
        'nuevaVacuna' => 'required|string|max:255',
        'especieSeleccionada' => 'required|exists:especies,id',
    ];

    public function mount()
    {
        $this->colores = [
            1 => '#f9dcbf', // Canino
            2 => '#ffe9c9', // Felino
            3 => '#cfe9f0', // Ave
            4 => '#e0cfe9', // Reptil
            // Agrega más colores según sea necesario
        ];
    
        // Cargar las especies con sus respectivas vacunas al montar el componente
        $this->especies = Especie::with('vacunas')->get();
    }

    public $nombreVacuna;

    public function agregarVacuna($especieId)
    {
        $this->validate([
            'nombreVacuna' => 'required|string|max:255',
        ]);

        // Crear la nueva vacuna
        Vacuna::create([
            'nombre' => $this->nombreVacuna,
            'especie_id' => $especieId,
        ]);

        // Limpiar el input después de agregar
        $this->nombreVacuna = '';

        // Opcional: puedes emitir un evento para refrescar la lista o dar feedback
        session()->flash('message', 'Vacuna agregada exitosamente.');
        $this->mount();
    }

    public function toggleEspecie($especieId)
    {
        if ($this->especieSeleccionada === $especieId) {
            $this->especieSeleccionada = null; // Si ya está seleccionada, ciérrala
        } else {
            $this->especieSeleccionada = $especieId; // Abre la nueva especie
        }
    }

    public function eliminarVacuna($vacunaId)
    {
        // Eliminar la vacuna seleccionada
        Vacuna::find($vacunaId)->delete();

        // Refrescar la lista de especies y vacunas
        $this->especies = Especie::with('vacunas')->get();
    }
    public function resetUI()
    {
        $this->reset();
        $this->componentName = 'Vacuna';
    }
    public function render()
    {
        
        return view('livewire.vacuna.vacuna')->extends('layouts.theme.app')->section('content');
    }
}
