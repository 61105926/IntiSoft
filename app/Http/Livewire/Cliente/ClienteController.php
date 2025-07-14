<?php

namespace App\Http\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\ClienteEmpresa;
use App\Models\Sucursal;
use App\Models\UnidadEducativa;
use Livewire\Component;

class ClienteController extends Component
{
    public $tipo_cliente;
    public $institucional_tipo; // EMPRESA o UNIDAD_EDUCATIVA

    public $sucursal_id;

    // Campos persona natural
    public $nombres;
    public $apellidos;
    public $carnet_identidad;

    // Campos empresa
    public $razon_social;
    public $nit;
    public $telefono_principal;
    public $telefono_secundario;

    // Campos comunes
    public $telefono;
    public $email;
    public $direccion;

    // Campos unidad educativa
    public $nombre_unidad;
    public $codigo_unidad;
    public $contacto_responsable;
    public $cargo_responsable;
    public $tipo_unidad = 'COLEGIO';

    public $activo = true;

    public $sucursales;

    public function mount()
    {
        $this->sucursales = Sucursal::all();
    }

    protected function rules()
    {
        $rules = [
            'tipo_cliente' => 'required|in:INDIVIDUAL,INSTITUCIONAL',
            'sucursal_id' => 'required|exists:sucursals,id',
            'email' => 'nullable|email',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
        ];

        if ($this->tipo_cliente === 'INDIVIDUAL') {
            $rules = array_merge($rules, [
                'nombres' => 'required|string|max:100',
                'apellidos' => 'required|string|max:100',
                'carnet_identidad' => 'required|string|max:20|unique:clientes,carnet_identidad',
            ]);
        } elseif ($this->tipo_cliente === 'INSTITUCIONAL') {
            $rules['institucional_tipo'] = 'required|in:EMPRESA,UNIDAD_EDUCATIVA';

            if ($this->institucional_tipo === 'EMPRESA') {
                $rules = array_merge($rules, [
                    'razon_social' => 'required|string|max:200',
                    'nit' => 'required|string|max:20|unique:cliente_empresas,nit',
                    'telefono_principal' => 'nullable|string|max:20',
                    'telefono_secundario' => 'nullable|string|max:20',
                ]);
            } elseif ($this->institucional_tipo === 'UNIDAD_EDUCATIVA') {
                $rules = array_merge($rules, [
                    'nombre_unidad' => 'required|string|max:200',
                    'codigo_unidad' => 'nullable|string|max:20|unique:unidad_educativas,codigo',
                    'contacto_responsable' => 'nullable|string|max:150',
                    'cargo_responsable' => 'nullable|string|max:100',
                    'tipo_unidad' => 'required|in:COLEGIO,UNIVERSIDAD,INSTITUTO,ACADEMIA,OTRO',
                    'telefono_principal' => 'nullable|string|max:20',
                ]);
            }
        }

        return $rules;
    }

    public function updatedTipoCliente()
    {
        $this->reset(['nombres', 'apellidos', 'carnet_identidad', 'razon_social', 'nit', 'telefono_principal', 'telefono_secundario', 'nombre_unidad', 'codigo_unidad', 'contacto_responsable', 'cargo_responsable', 'tipo_unidad', 'institucional_tipo']);
    }

    public function saveCliente()
    {
        $this->validate();

        if ($this->tipo_cliente === 'INDIVIDUAL') {
            if (Cliente::where('carnet_identidad', $this->carnet_identidad)->exists()) {
                session()->flash('error', 'Ya existe un cliente con ese Carnet de Identidad.');
                return;
            }
        } elseif ($this->tipo_cliente === 'INSTITUCIONAL') {
            if ($this->institucional_tipo === 'EMPRESA') {
                $this->tipo_cliente = 'EMPRESA';
                if (ClienteEmpresa::where('nit', $this->nit)->exists()) {
                    session()->flash('error', 'Ya existe una empresa con ese NIT.');
                    return;
                }
            } elseif ($this->institucional_tipo === 'UNIDAD_EDUCATIVA') {
                $this->tipo_cliente = 'UNIDAD_EDUCATIVA';

                if (UnidadEducativa::where('codigo', $this->codigo_unidad)->exists()) {
                    session()->flash('error', 'Ya existe una unidad educativa con ese código.');
                    return;
                }
            }
        }

        $cliente = Cliente::create([
            'sucursal_id' => $this->sucursal_id,
            'tipo_cliente' => $this->tipo_cliente,
            'nombres' => $this->tipo_cliente === 'INDIVIDUAL' ? $this->nombres : null,
            'apellidos' => $this->tipo_cliente === 'INDIVIDUAL' ? $this->apellidos : null,
            'carnet_identidad' => $this->tipo_cliente === 'INDIVIDUAL' ? $this->carnet_identidad : null,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'direccion' => $this->direccion,
            'activo' => $this->activo,
        ]);

        if ($this->tipo_cliente === 'EMPRESA') {
            ClienteEmpresa::create([
                'cliente_id' => $cliente->id,
                'razon_social' => $this->razon_social,
                'nit' => $this->nit,
                'telefono_principal' => $this->telefono_principal,
                'telefono_secundario' => $this->telefono_secundario,
                'email' => $this->email,
                'direccion' => $this->direccion,
            ]);
        }

        if ($this->tipo_cliente === 'UNIDAD_EDUCATIVA') {
            UnidadEducativa::create([
                'cliente_id' => $cliente->id,
                'nombre' => $this->nombre_unidad,
                'codigo' => $this->codigo_unidad,
                'direccion' => $this->direccion,
                'telefono' => $this->telefono_principal,
                'email' => $this->email,
                'contacto_responsable' => $this->contacto_responsable,
                'cargo_responsable' => $this->cargo_responsable,
                'tipo' => $this->tipo_unidad,
                'activa' => true,
            ]);
        }

        session()->flash('message', 'Cliente guardado correctamente.');

        $this->reset(['tipo_cliente', 'institucional_tipo', 'sucursal_id', 'nombres', 'apellidos', 'carnet_identidad', 'razon_social', 'nit', 'telefono_principal', 'telefono_secundario', 'telefono', 'email', 'direccion', 'nombre_unidad', 'codigo_unidad', 'contacto_responsable', 'cargo_responsable', 'tipo_unidad']);

        $this->dispatchBrowserEvent('closeModal');
    }
    public $modalMode = 'create'; // create | edit | view
    public $selectedClienteId = null;

    public function verCliente($id)
    {
        $this->resetValidation();
        $this->selectedClienteId = $id;
        $cliente = Cliente::with(['clienteEmpresa', 'unidadEducativa'])->findOrFail($id);

        $this->modalMode = 'view';

        // Cargar campos según tipo
        $this->tipo_cliente = $cliente->tipo_cliente;
        $this->sucursal_id = $cliente->sucursal_id;
        $this->telefono = $cliente->telefono;
        $this->email = $cliente->email;
        $this->direccion = $cliente->direccion;
        $this->activo = $cliente->activo;

        if ($this->tipo_cliente === 'INDIVIDUAL') {
            $this->nombres = $cliente->nombres;
            $this->apellidos = $cliente->apellidos;
            $this->carnet_identidad = $cliente->carnet_identidad;
            $this->institucional_tipo = null;
        } elseif ($this->tipo_cliente === 'EMPRESA') {
            $this->institucional_tipo = 'EMPRESA';
            $empresa = $cliente->clienteEmpresa;
            $this->razon_social = $empresa->razon_social ?? null;
            $this->nit = $empresa->nit ?? null;
            $this->telefono_principal = $empresa->telefono_principal ?? null;
            $this->telefono_secundario = $empresa->telefono_secundario ?? null;
        } elseif ($this->tipo_cliente === 'UNIDAD_EDUCATIVA') {
            $this->institucional_tipo = 'UNIDAD_EDUCATIVA';
            $unidad = $cliente->unidadEducativa;
            $this->nombre_unidad = $unidad->nombre ?? null;
            $this->codigo_unidad = $unidad->codigo ?? null;
            $this->contacto_responsable = $unidad->contacto_responsable ?? null;
            $this->cargo_responsable = $unidad->cargo_responsable ?? null;
            $this->tipo_unidad = $unidad->tipo ?? null;
            $this->telefono_principal = $unidad->telefono ?? null;
        }

        $this->dispatchBrowserEvent('showClienteModal');
    }

    public function editarCliente($id)
    {
        $this->resetValidation();
        $this->selectedClienteId = $id;
        $cliente = Cliente::with(['clienteEmpresa', 'unidadEducativa'])->findOrFail($id);

        $this->modalMode = 'edit';

        // Cargar campos para edición (igual que en verCliente)
        $this->tipo_cliente = $cliente->tipo_cliente;
        $this->sucursal_id = $cliente->sucursal_id;
        $this->telefono = $cliente->telefono;
        $this->email = $cliente->email;
        $this->direccion = $cliente->direccion;
        $this->activo = $cliente->activo;

        if ($this->tipo_cliente === 'INDIVIDUAL') {
            $this->nombres = $cliente->nombres;
            $this->apellidos = $cliente->apellidos;
            $this->carnet_identidad = $cliente->carnet_identidad;
            $this->institucional_tipo = null;
        } elseif ($this->tipo_cliente === 'EMPRESA') {
            $this->institucional_tipo = 'EMPRESA';
            $empresa = $cliente->clienteEmpresa;
            $this->razon_social = $empresa->razon_social ?? null;
            $this->nit = $empresa->nit ?? null;
            $this->telefono_principal = $empresa->telefono_principal ?? null;
            $this->telefono_secundario = $empresa->telefono_secundario ?? null;
        } elseif ($this->tipo_cliente === 'UNIDAD_EDUCATIVA') {
            $this->institucional_tipo = 'UNIDAD_EDUCATIVA';
            $unidad = $cliente->unidadEducativa;
            $this->nombre_unidad = $unidad->nombre ?? null;
            $this->codigo_unidad = $unidad->codigo ?? null;
            $this->contacto_responsable = $unidad->contacto_responsable ?? null;
            $this->cargo_responsable = $unidad->cargo_responsable ?? null;
            $this->tipo_unidad = $unidad->tipo ?? null;
            $this->telefono_principal = $unidad->telefono ?? null;
        }

        $this->dispatchBrowserEvent('showClienteModal');
    }

    public function updateCliente()
    {
        $this->validate();

        $cliente = Cliente::findOrFail($this->selectedClienteId);
        $cliente->sucursal_id = $this->sucursal_id;
        $cliente->telefono = $this->telefono;
        $cliente->email = $this->email;
        $cliente->direccion = $this->direccion;
        $cliente->activo = $this->activo;

        if ($this->tipo_cliente === 'INDIVIDUAL') {
            $cliente->nombres = $this->nombres;
            $cliente->apellidos = $this->apellidos;
            $cliente->carnet_identidad = $this->carnet_identidad;
        } else {
            $cliente->nombres = null;
            $cliente->apellidos = null;
            $cliente->carnet_identidad = null;
        }

        $cliente->save();

        if ($this->tipo_cliente === 'EMPRESA') {
            $empresa = ClienteEmpresa::updateOrCreate(
                ['cliente_id' => $cliente->id],
                [
                    'razon_social' => $this->razon_social,
                    'nit' => $this->nit,
                    'telefono_principal' => $this->telefono_principal,
                    'telefono_secundario' => $this->telefono_secundario,
                    'email' => $this->email,
                    'direccion' => $this->direccion,
                ],
            );
        } elseif ($this->tipo_cliente === 'UNIDAD_EDUCATIVA') {
            $unidad = UnidadEducativa::updateOrCreate(
                ['cliente_id' => $cliente->id],
                [
                    'nombre' => $this->nombre_unidad,
                    'codigo' => $this->codigo_unidad,
                    'direccion' => $this->direccion,
                    'telefono' => $this->telefono_principal,
                    'email' => $this->email,
                    'contacto_responsable' => $this->contacto_responsable,
                    'cargo_responsable' => $this->cargo_responsable,
                    'tipo' => $this->tipo_unidad,
                    'activa' => true,
                ],
            );
        }

        session()->flash('message', 'Cliente actualizado correctamente.');

        $this->resetInputFields();
        $this->dispatchBrowserEvent('closeClienteModal');
    }

    public function resetInputFields()
    {
        $this->reset(['tipo_cliente', 'institucional_tipo', 'sucursal_id', 'nombres', 'apellidos', 'carnet_identidad', 'razon_social', 'nit', 'telefono_principal', 'telefono_secundario', 'telefono', 'email', 'direccion', 'nombre_unidad', 'codigo_unidad', 'contacto_responsable', 'cargo_responsable', 'tipo_unidad', 'activo', 'selectedClienteId', 'modalMode']);
    }

    public function eliminarCliente($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->activo = false;
        $cliente->save();
        session()->flash('message', 'Cliente desactivado correctamente.');
    }

    public function render()
    {
        $clientes = Cliente::with(['sucursal', 'usuario'])->get();

        $estadisticas = [
            'total' => Cliente::count(),
            'persona_natural' => Cliente::where('tipo_cliente', 'INDIVIDUAL')->count(),
            'empresa' => Cliente::where('tipo_cliente', 'EMPRESA')->count(),
            'unidad_educativa' => Cliente::where('tipo_cliente', 'UNIDAD_EDUCATIVA')->count(),
        ];

        return view('livewire.cliente.cliente', [
            'clientes' => $clientes,
            'estadisticas' => $estadisticas,
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
