<?php

namespace App\Http\Livewire\Pet;

use App\Models\Notas;
use App\Models\Pet;
use Livewire\Component;

class PetDetailController extends Component
{
    public $petId; // ID de la mascota
    public $pet; // Detalles de la mascota

    public $noteId; // ID de la nota que se está editando (si aplica)

    public $note;
    public function mount($id)
    {
        // Aquí recibimos el ID de la mascota
        $this->petId = $id;
        $this->pet = Pet::find($this->petId); // Cargar los detalles de la mascota
    }
    
    public function storeNote()
    {
        // Validar el contenido de la nota
        $this->validate([
            'note' => 'required|string|max:255',
        ]);

        // Si estamos editando una nota existente
        if ($this->noteId) {
            $existingNote = Notas::find($this->noteId);
            if ($existingNote) {
                $existingNote->update([
                    'nota' => $this->note,
                ]);
                session()->flash('message', 'Nota actualizada exitosamente.');
            }
        } else {
            // Si es una nueva nota
            Notas::create([
                'pets_id' => $this->petId,
                'nota' => $this->note,
            ]);
            session()->flash('message', 'Nota guardada exitosamente.');
        }

        // Limpiar el campo de entrada y el ID de la nota
        $this->note = '';
        $this->noteId = null;

        // Recargar la página
        return redirect()->route('pet.detail', ['id' => $this->petId]);
    }
    public function editNote($id)
    {
        $nota = Notas::find($id);
        if ($nota) {
            $this->noteId = $nota->id; // Guardar el ID de la nota que se está editando
            $this->note = $nota->nota; // Cargar el contenido de la nota en el campo
        }
    }

    public function deleteNote($noteId)
    {
        // Eliminar la nota
        Notas::find($noteId)->delete();

        // Mostrar un mensaje de éxito
        session()->flash('message', 'Nota eliminada exitosamente.');
        return redirect()->route('pet.detail', ['id' => $this->petId]);
    }
   
    public function render()
    {
        $notas = Notas::where('pets_id', $this->petId)->get();

        return view('livewire.pet.pet-detail', ['notas' => $notas])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
