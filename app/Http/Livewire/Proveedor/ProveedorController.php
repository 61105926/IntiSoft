<?php

namespace App\Http\Livewire\Proveedor;

use App\Models\Caja;
use App\Models\CajaSalidaOperaciones;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProveedorController extends Component
{
    use WithPagination;

    public $selected_id;
    public $componentName = 'Proveedor';

    // Atributos del proveedor
    public $nombreProveedor = '';
    public $documentoNit = '';
    public $direccion = '';
    public $telefono1 = '';
    public $telefono2 = '';
    public $email = '';
    public $numeroCuenta = ''; // Número de cuenta para el pago
    public $fechaPago; // Fecha del pago
    public $categoria = ''; // Categoría del proveedor

    // Atributos del producto
    public $selectedProduct = '';
    public $quantity = 1;
    public $price = 0;
    public $cart = [];
    public $total = 0;
    public $caja_id;

    public $banco;
    public $ci_proveedor;
    public $tipo_cuenta;

    public function mount()
    {
    }
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function updatedCart()
    {
        // Recalcular el total cada vez que el carrito se actualiza
        $this->calculateTotal();
    }

    public function addProduct()
    {
        $this->validate([
            'selectedProduct' => 'required',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $this->cart[] = [
            'code' => $this->selectedProduct,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->price * $this->quantity,
        ];

        // Reiniciar los campos de producto
        $this->resetProductFields();
        $this->calculateTotal();
    }

    public function resetProductFields()
    {
        $this->selectedProduct = '';
        $this->quantity = 1;
        $this->price = 0;
    }

    public function calculateTotal()
    {
        // Sumar el total de cada producto en el carrito
        $this->total = array_sum(array_column($this->cart, 'total'));
    }

    public function removeProduct($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart); // Reindexar el array
        $this->calculateTotal();
    }

    public function store()
    {
        // Validar los datos del proveedor
        $this->validate([
            'nombreProveedor' => 'required|string|max:255',
            'documentoNit' => 'required|string|max:50',
            'direccion' => 'required|string|max:255',
            'telefono1' => 'nullable|string|max:20',
            'telefono2' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'numeroCuenta' => 'required|string|max:20',
            'categoria' => 'required|string|max:255',
            'banco' => 'required|string|max:255',
            'ci_proveedor' => 'required|string|max:255',
            'tipo_cuenta' => 'required|string|max:255'
        ]);

        // Guardar los datos del proveedor
        $proveedor = Proveedor::create([
            'proveedor_nombre' => $this->nombreProveedor,
            'nit' => $this->documentoNit,
            'direccion' => $this->direccion,
            'telefono1' => $this->telefono1,
            'telefono2' => $this->telefono2,
            'email' => $this->email,
            'numero_cuenta' => $this->numeroCuenta,
            'categoria' => $this->categoria,
            'banco' => $this->banco,
            'ci_proveedor' => $this->ci_proveedor,
            'tipo_cuenta' => $this->tipo_cuenta
        ]);
        $this->render();
        // Guardar los productos en el carrito (si es necesario)
        // foreach ($this->cart as $item) {
        //     $proveedor->productos()->create([
        //         'proveedor_id' => $proveedor->id,
        //         'codigo' => $item['code'],
        //         'cantidad' => $item['quantity'],
        //         'precio_unitario' => $item['price'],
        //         'precio_total' => $item['total'],
        //     ]);
        // }
        // $this->registerCajaSalida($proveedor->id, $this->total);

        // Reiniciar los campos del formulario
        // $this->resetFields();

        // Mensaje de éxito
        $this->emit('user-added', 'Proveedor Registrada');

        $this->emit('mostrarAlertaSuccess', 'Proveedor Registrado');
    }
    private function registerCajaSalida($proveedorId, $monto)
    {
        $cajaSalida = new CajaSalidaOperaciones();
        $cajaSalida->caja_id = $this->caja_id; // Asegúrate de definir el ID de la caja correspondiente
        $cajaSalida->type = 'Gasto'; // O el tipo que corresponda
        $cajaSalida->monto = $monto;
        $cajaSalida->description = 'Pago a proveedor ID: ' . $proveedorId;
        $cajaSalida->save();
    }
    public function resetUI()
    {
        $this->reset();
        $this->componentName = 'Proveedor';
    }
    public function resetFields()
    {
        $this->nombreProveedor = '';
        $this->documentoNit = '';
        $this->direccion = '';
        $this->telefono1 = '';
        $this->telefono2 = '';
        $this->email = '';
        $this->numeroCuenta = '';
        $this->fechaPago = null;
        $this->categoria = '';
        $this->cart = []; // Reiniciar el carrito
        $this->total = 0; // Reiniciar el total
        $this->banco = '';
        $this->ci_proveedor = '';
        $this->tipo_cuenta = '';
    }
    public $search;
    public $pagination = 10;

    public function searchProveedor()
    {
        $query = Proveedor::query();

        if (strlen($this->search) > 0) {
            $query->where(function ($query) {
                $query->where('proveedor_nombre', 'like', '%' . $this->search . '%')->orWhere('nit', 'like', '%' . $this->search . '%');
            });
            $this->resetPage();
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->paginate($this->pagination);
        // Agregar un atributo "restante" a cada producto

        // Agregar un atributo "vendido" y "restante" a cada producto en la colección paginada

        return $products;
    }

    public function render()
    {
        $proveedores = $this->searchProveedor();

        $user = Auth::user(); // O auth()->user();

        $atm = Caja::where('user_id', $user->id)
            ->where('state', 0)
            ->get(); // Asumiendo que tienes un campo user_id en la tabla Caja

        return view('livewire.proveedor.proveedor', ['proveedores' => $proveedores, 'atm' => $atm])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function edit($id)
    {
        $proveedor = Proveedor::find($id);
        $this->selected_id = $proveedor->id;
        $this->nombreProveedor = $proveedor->proveedor_nombre;
        $this->documentoNit = $proveedor->nit;
        $this->direccion = $proveedor->direccion;
        $this->telefono1 = $proveedor->telefono1;
        $this->telefono2 = $proveedor->telefono2;
        $this->email = $proveedor->email;
        $this->numeroCuenta = $proveedor->numero_cuenta;
        $this->categoria = $proveedor->categoria;
        $this->banco = $proveedor->banco;
        $this->ci_proveedor = $proveedor->ci_proveedor;
        $this->tipo_cuenta = $proveedor->tipo_cuenta;

        $this->emit('show-modal', 'Editar Proveedor');
    }

    public function update()
    {
        $this->validate([
            'nombreProveedor' => 'required',
            'documentoNit' => 'required',
            'direccion' => 'required',
            'numeroCuenta' => 'required',
            'categoria' => 'required',
            'banco' => 'required',
            'ci_proveedor' => 'required',
            'tipo_cuenta' => 'required'
        ]);

        if ($this->selected_id) {
            $proveedor = Proveedor::find($this->selected_id);
            $proveedor->update([
                'proveedor_nombre' => $this->nombreProveedor,
                'nit' => $this->documentoNit,
                'direccion' => $this->direccion,
                'telefono1' => $this->telefono1,
                'telefono2' => $this->telefono2,
                'email' => $this->email,
                'numero_cuenta' => $this->numeroCuenta,
                'categoria' => $this->categoria,
                'banco' => $this->banco,
                'ci_proveedor' => $this->ci_proveedor,
                'tipo_cuenta' => $this->tipo_cuenta
            ]);

            $this->emit('user-updated', 'Proveedor Actualizado');
            $this->resetUI();
        }
    }

    public function toggleState($id)
    {
        $proveedor = Proveedor::find($id);
        $proveedor->estado = !$proveedor->estado;
        $proveedor->save();

        $this->emit('mostrarAlertaSuccess', 
            $proveedor->estado ? 'Proveedor activado correctamente' : 'Proveedor desactivado correctamente'
        );
    }
}
