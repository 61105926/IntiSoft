<?php

namespace App\Http\Livewire\Empresa;

use App\Models\Empresa;
use Livewire\Component;

class EmpresaController extends Component
{
    public $nombre_legal,
        $razon_social,
        $nit,
        $direccion,
        $telefono,
        $email,
        $sitio_web,
        $moneda_base = 'BOB (Bolivianos)';

    public function mount()
    {
        $empresa = Empresa::first();
        if ($empresa) {
            $this->razon_social = $empresa->razon_social;
            $this->nombre_legal = $empresa->razon_social;
            $this->nit = $empresa->nit;
            $this->direccion = $empresa->direccion;
            $this->telefono = $empresa->telefono;
            $this->email = $empresa->email;
            $this->sitio_web = $empresa->logo_url;
        }
    }

    public function guardar()
    {
        $this->validate([
            'razon_social' => 'required|string|max:200',
            'nombre_legal' => 'nullable|string|max:200',
            'nit' => 'required|string|max:20',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'sitio_web' => 'nullable|string|max:500',
            'moneda_base' => 'required|string|max:50',
        ]);

        Empresa::updateOrCreate(
            ['nit' => $this->nit],
            [
                'razon_social' => $this->razon_social,
                'direccion' => $this->direccion,
                'telefono' => $this->telefono,
                'email' => $this->email,
                'logo_url' => $this->sitio_web,
            ],
        );

        session()->flash('message', 'Empresa actualizada correctamente.');
    }

    public function render()
    {
        return view('livewire.empresa.empresa')->extends('layouts.theme.app')->section('content');
    }
}
