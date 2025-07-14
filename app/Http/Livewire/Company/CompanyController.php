<?php

namespace App\Http\Livewire\Company;

use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyController extends Component
{
    use WithFileUploads;

    public $name;
    public $phone;
    public $email;
    public $address;
    public $nit;
    public $image;
    public $logo;
    public function mount()
    {
        $company = Company::first(); // Asumiendo que solo hay una empresa
        if ($company) {
            $this->name = $company->name;
            $this->phone = $company->phone;
            $this->email = $company->email;
            $this->address = $company->address;
            $this->nit = $company->nit;
            $this->logo = $company->image;
        }
    }


    public function updateCompany()
    {
        $this->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
            'nit' => 'required',
            // 'image' => 'required', // Validamos la imagen

        ]);
        //  dd($this->image);
        $company = Company::first();
        if ($company) {
            // Si existe la compañía, actualizamos sus datos
            $company->update([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'nit' => $this->nit,
            ]);

            if ($this->image) {
                // Genera un nombre único para el archivo
                $customFileName = uniqid() . '.' . $this->image->extension();
                // Define la ruta del archivo
                $filePath = 'public/company/' . $customFileName;
                // Redimensiona la imagen
                $image = Image::read($this->image)
                    ->encode(); // Codifica en el formato original

                // Guarda la imagen redimensionada en el almacenamiento
                Storage::setVisibility($filePath, 'public');

                Storage::put($filePath, (string) $image);

                $absolutePath = storage_path('app/' . $filePath);

                // Asigna permisos de lectura, escritura y ejecución para todos
                chmod($absolutePath, 0755);

                $company->update(['image' => $customFileName]);
            }
        } else {
            // Creamos la compañía si no existe
            $company = Company::create([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'nit' => $this->nit,
            ]);

            if ($this->image) {
                // Genera un nombre único para el archivo
                $customFileName = uniqid() . '.' . $this->image->extension();
                // Define la ruta del archivo
                $filePath = 'public/company/' . $customFileName;
                // Redimensiona la imagen
                $image = Image::read($this->image)
                    ->encode(); // Codifica en el formato original

                // Guarda la imagen redimensionada en el almacenamiento
                Storage::setVisibility($filePath, 'public');

                Storage::put($filePath, (string) $image);

                $absolutePath = storage_path('app/' . $filePath);

                // Asigna permisos de lectura, escritura y ejecución para todos
                chmod($absolutePath, 0755);

                $company->update(['image' => $customFileName]);
            }
        }

        // Emitir alerta de éxito
        $this->emit('show-alert', ['title' => 'Datos de la empresa actualizados', 'type' => 'success']);
    }
    public function render()
    {
        $company = Company::all();
        return view('livewire.company.company')->extends('layouts.theme.app')
            ->section('content');


    }
}
