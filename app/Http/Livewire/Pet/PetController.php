<?php

namespace App\Http\Livewire\Pet;

use App\Models\Client;
use App\Models\Especie;
use App\Models\Pet;
use App\Models\Raza;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class PetController extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $componentName;
    public $selected_id;
    public $pageTitle;
    private $pagination = 10;
    public $search;
    public $nombre;
    public $especie;
    public $raza;
    public $sexo;
    public $fecha_nacimiento;
    public $color;
    public $peso;
    public $chip;
    public $tatuaje;
    public $pasaporte;
    public $entero_castrado;
    public $veterinario_habitual;
    public $client_id; // Agregado el campo client_id
    public $razas;
    public $especies;

    public $image;

    public function mount()
    {
        $this->pageTitle = 'Listado de Mascotas';
        $this->componentName = 'Mascotas';
        $this->especies = Especie::all(); // Cargar todas las especies
        $this->razas = []; // Inicializa las razas
        //  phpinfo();
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function searchPets()
    {
        $query = Pet::query();

        if (strlen($this->search) > 0) {
            $query->where(function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')->orWhere('chip', 'like', '%' . $this->search . '%');
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
            'nombre' => 'required',
            'especie' => 'required',
            'raza' => 'required',
            'sexo' => 'required',
            'fecha_nacimiento' => 'required',
            'color' => 'required',
            'peso' => 'required',
            'chip' => 'nullable',
            'tatuaje' => 'nullable',
            'pasaporte' => 'nullable',
            'entero_castrado' => 'required',
            'veterinario_habitual' => 'required',
            'client_id' => 'required',
            'image' => 'nullable', // Validación para la imagen
        ];
    }

    public function store()
    {
        $this->validate();
        $pet = new Pet();
        $pet->nombre = $this->nombre;
        $pet->especie = $this->especie;
        $pet->raza_id = $this->raza;

        $pet->raza = $this->raza;
        $pet->sexo = $this->sexo;
        $pet->fecha_nacimiento = $this->fecha_nacimiento;
        $pet->color = $this->color;
        $pet->peso = $this->peso;
        $pet->chip = $this->chip;
        $pet->tatuaje = $this->tatuaje;
        $pet->pasaporte = $this->pasaporte;
        $pet->entero_castrado = $this->entero_castrado;
        $pet->veterinario_habitual = $this->veterinario_habitual;
        $pet->client_id = $this->client_id; // Asignar client_id

        $pet->save();
        if ($this->image) {
            // dd($this->image);
            // Genera un nombre único para el archivo
            $customFileName = uniqid() . '.' . $this->image->extension();
            // Define la ruta del archivo
            $filePath = 'public/pet/' . $customFileName;
            // Redimensiona la imagen
            $image = Image::read($this->image)->encode(); // Codifica en el formato original

            // Guarda la imagen redimensionada en el almacenamiento
            Storage::setVisibility($filePath, 'public');

            Storage::put($filePath, (string) $image);

            $absolutePath = storage_path('app/' . $filePath);

            // Asigna permisos de lectura, escritura y ejecución para todos
            chmod($absolutePath, 0755);

            $pet->update(['image' => $customFileName]);
        }
        $this->resetUI();
        $this->emit('person-added', 'Mascota Registrada');
        $this->emit('mostrarAlertaSuccess', 'Mascota Registrada');
    }

    public function edit(Pet $pet)
    {
        $this->selected_id = $pet->id;

        // Asegúrate de que el id de raza se está asignando correctamente
        $this->nombre = $pet->nombre;
        $this->especie = $pet->especie;
        $this->raza = $pet->raza_id; // Asignar el id de la raza aquí
        $this->sexo = $pet->sexo;
        $this->fecha_nacimiento = $pet->fecha_nacimiento;
        $this->color = $pet->color;
        $this->peso = $pet->peso;
        $this->chip = $pet->chip;
        $this->tatuaje = $pet->tatuaje;
        $this->pasaporte = $pet->pasaporte;
        $this->entero_castrado = $pet->entero_castrado;
        $this->veterinario_habitual = $pet->veterinario_habitual;
        $this->client_id = $pet->client_id; // Asignar client_id
        $this->razas = Raza::where('especie_id', $pet->especie)->get();

        $this->emit('show-modal', 'show');
    }

    public function update()
    {
        $pet = Pet::find($this->selected_id);

        if ($pet) {
            $pet->nombre = $this->nombre;
            $pet->especie = $this->especie;
            $pet->raza = $this->raza;
            $pet->raza_id = $this->raza;
            $pet->sexo = $this->sexo;
            $pet->fecha_nacimiento = $this->fecha_nacimiento;
            $pet->color = $this->color;
            $pet->peso = $this->peso;
            $pet->chip = $this->chip;
            $pet->tatuaje = $this->tatuaje;
            $pet->pasaporte = $this->pasaporte;
            $pet->entero_castrado = $this->entero_castrado;
            $pet->veterinario_habitual = $this->veterinario_habitual;
            $pet->client_id = $this->client_id; // Asignar client_id

            $pet->save();
            // dd($this->image);
            if ($this->image && $this->image != $pet->image) {
                // dd($this->image);

                // Genera un nombre único para el archivo
                $customFileName = uniqid() . '.' . $this->image->extension();
                // Define la ruta del archivo
                $filePath = 'public/pet/' . $customFileName;
                // Redimensiona la imagen
                $image = Image::read($this->image)->encode(); // Codifica en el formato original

                // Guarda la imagen redimensionada en el almacenamiento
                Storage::setVisibility($filePath, 'public');

                Storage::put($filePath, (string) $image);

                $absolutePath = storage_path('app/' . $filePath);

                // Asigna permisos de lectura, escritura y ejecución para todos
                chmod($absolutePath, 0755);

                $pet->update(['image' => $customFileName]);
            }
            $this->resetUI();
            $this->emit('person-updated', 'Mascota Actualizada');
            $this->emit('mostrarAlertaSuccess', 'Mascota Actualizada');
        } else {
            $this->emit('pet-not-found', 'Mascota no encontrada');
        }
    }

    protected $listeners = [
        'deleteRow' => 'destroy',
    ];

    public function destroy(Pet $pet)
    {
        $pet->state = $pet->state == 0 ? 1 : 0;
        $pet->save();
        $this->resetUI();
        $this->emit('mostrarAlertaSuccess', 'Mascota Eliminada');
    }

    public function resetUI()
    {
        $this->image = '';

        $this->reset();
        $this->componentName = 'Mascota';
    }

    public function updatedEspecie($value)
    {
        // Cargar razas según la especie seleccionada
        $this->razas = Raza::where('especie_id', $value)->where('state', 1)->get();
        $this->raza = ''; // Reinicia la raza seleccionada
    }
    public $client_data;

    public function updatedClientId($value)
    {
        $this->client_data = Client::find($value); // Ajusta esto según tu modelo
    }
    public function updatedImage()
    {
        $this->emit('imageUpdated'); // Disparar un evento personalizado
    }
    public function render()
    {
        $data = $this->searchPets();
        $clients = Client::all(); // Cargar todos los clientes

        return view('livewire.pet.pet', ['data' => $data, 'clients' => $clients]) // Pasar los clientes a la vista
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
