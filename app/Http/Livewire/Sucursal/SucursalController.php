<?php

namespace App\Http\Livewire\Sucursal;

use App\Models\Sucursal;
use Livewire\Component;

class SucursalController extends Component
{
    public $sucursales,
        $empresa_id = 1;

    public $sucursal_id,
        $nombre,
        $codigo,
        $direccion,
        $telefono,
        $responsable,
        $activa = true;
    public $modo_edicion = false;
    public $mostrar_modal = false;

    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:150',
            'codigo' => 'required|string|max:10|unique:sucursals,codigo,' . $this->sucursal_id,
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:20',
            'responsable' => 'nullable|string|max:100',
        ];
    }

    public function mount()
    {
        $this->cargarSucursales();
    }

    public function cargarSucursales()
    {
        $this->sucursales = Sucursal::where('empresa_id', $this->empresa_id)->get();
    }

    public function crear()
    {
        $this->resetFormulario();
        $this->mostrar_modal = true;
    }

    public function editar($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        $this->sucursal_id = $sucursal->id;
        $this->nombre = $sucursal->nombre;
        $this->codigo = $sucursal->codigo;
        $this->direccion = $sucursal->direccion;
        $this->telefono = $sucursal->telefono;
        $this->responsable = $sucursal->responsable;
        $this->activa = $sucursal->activa;
        $this->modo_edicion = true;
        $this->mostrar_modal = true;
    }

    public function guardar()
    {
        $this->validate();

        Sucursal::updateOrCreate(
            ['id' => $this->sucursal_id],
            [
                'empresa_id' => $this->empresa_id,
                'nombre' => $this->nombre,
                'codigo' => $this->codigo,
                'direccion' => $this->direccion,
                'telefono' => $this->telefono,
                'responsable' => $this->responsable,
                'activa' => $this->activa,
            ],
        );

        session()->flash('message', $this->modo_edicion ? 'Sucursal actualizada' : 'Sucursal creada');
        $this->resetFormulario();
        $this->cargarSucursales();
        $this->mostrar_modal = false;
    }

    public function desactivar($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->activa = !$sucursal->activa;
        $sucursal->save();
        $this->cargarSucursales();
    }

    public function resetFormulario()
    {
        $this->reset(['sucursal_id', 'nombre', 'codigo', 'direccion', 'telefono', 'responsable', 'activa', 'modo_edicion']);
    }

    public function render()
    {
        return view('livewire.sucursal.sucursal')->extends('layouts.theme.app')->section('content');
    }
}
