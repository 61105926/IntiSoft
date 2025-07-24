<?php

namespace App\Http\Livewire\Producto;

use App\Models\CategoriaProducto;
use App\Models\MovimientoStockSucursal;
use App\Models\Producto;
use App\Models\StockPorSucursal;
use App\Models\Sucursal;
use Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class ProductoController extends Component
{
    use WithPagination;

    public $search = '',
        $sucursal_id = '',
        $categoria_id = '',
        $estado_stock = '',
        $disponible_venta = '',
        $disponible_alquiler = '';
    public $perPage = 10;

    public $showModal = false,
        $isEdit = false,
        $editingId = null;
    public $showStockModal = false,
        $stockAdd = 0,
        $stockProductoId = null;
    public $showDetailModal = false,
        $detailProducto = null;

    public $nombre, $descripcion, $talla, $color, $material;
    public $precio_venta, $precio_alquiler, $stock_actual, $stock_minimo;
    public $categoria_id_form, $sucursal_id_form;
    public $disponible_venta_form = false,
        $disponible_alquiler_form = false;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSucursalId()
    {
        $this->resetPage();
    }
    public function updatingCategoriaId()
    {
        $this->resetPage();
    }
    public $productosAll;

    public function render()
    {
        $query = Producto::query()->select('productos.*', 'sps.stock_actual', 'sps.stock_minimo', 'sps.precio_venta_sucursal', 'sps.precio_alquiler_sucursal', 'sps.sucursal_id')->join('stock_por_sucursals as sps', 'productos.id', '=', 'sps.producto_id')->when($this->sucursal_id, fn($q) => $q->where('sps.sucursal_id', $this->sucursal_id))->with('categoria');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('productos.nombre', 'like', "%{$this->search}%")->orWhere('productos.descripcion', 'like', "%{$this->search}%");
            });
        }

        if ($this->categoria_id) {
            $query->where('productos.categoria_id', $this->categoria_id);
        }
        if ($this->estado_stock === 'sin_stock') {
            $query->where('sps.stock_actual', '<=', 0);
        } elseif ($this->estado_stock === 'stock_bajo') {
            $query->whereColumn('sps.stock_actual', '<=', 'sps.stock_minimo');
        }

        if ($this->disponible_venta !== '') {
            $query->where('productos.disponible_venta', $this->disponible_venta);
        }
        if ($this->disponible_alquiler !== '') {
            $query->where('productos.disponible_alquiler', $this->disponible_alquiler);
        }
        $this->productosAll = Producto::all(); // o la consulta que uses para cargar productos

        return view('livewire.producto.producto', [
            'productosAll' => $this->productosAll,
            'productos' => $query->orderBy('productos.nombre')->paginate($this->perPage),
            'sucursales' => Sucursal::activo()->get(),
            'categorias' => CategoriaProducto::all(),
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function showCreateModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->emit('showModal');
    }

    public function showEditModal($productoId, $sucursalId)
    {
        $this->isEdit = true;

        $producto = Producto::findOrFail($productoId);
        $stockSucursal = StockPorSucursal::where('producto_id', $productoId)
            ->where('sucursal_id', $sucursalId)
            ->first();
        $this->productoSeleccionadoId = $producto->id;

        // Setear datos del producto
        $this->nombre = $producto->nombre;
        $this->descripcion = $producto->descripcion;
        $this->categoria_id_form = $producto->categoria_id;
        $this->talla = $producto->talla;
        $this->color = $producto->color;
        $this->material = $producto->material;
        $this->disponible_venta_form = $producto->disponible_venta;
        $this->disponible_alquiler_form = $producto->disponible_alquiler;

        // Setear datos específicos de la sucursal
        $this->sucursal_id_form = $sucursalId;
        $this->stock_actual = $stockSucursal->stock_actual ?? 0;
        $this->stock_minimo = $stockSucursal->stock_minimo ?? 0;
        $this->precio_venta = $stockSucursal->precio_venta_sucursal ?? 0;
        $this->precio_alquiler = $stockSucursal->precio_alquiler_sucursal ?? 0;

        $this->productosExistentes = true;

        $this->emit('showModal'); // abrir modal en frontend
    }


    public function showStockModal($productoId)
    {
        $this->stockProductoId = $productoId;
        $this->stockAdd = 0;
        $this->emit('showStockModal');
    }

    public function addStock()
    {
        $this->validate(['stockAdd' => 'required|integer|min:1']);

        $stock = StockPorSucursal::where('producto_id', $this->stockProductoId)->where('sucursal_id', $this->sucursal_id)->first();

        if (!$stock) {
            session()->flash('error', 'No se encontró stock para este producto en la sucursal seleccionada.');
            return;
        }

        $stock->increment('stock_actual', $this->stockAdd);

        MovimientoStockSucursal::create([
            'producto_id' => $this->stockProductoId,
            'sucursal_id' => $this->sucursal_id,
            'tipo_movimiento' => 'ENTRADA',
            'cantidad' => $this->stockAdd,
            'stock_anterior' => $stock->stock_actual - $this->stockAdd,
            'stock_nuevo' => $stock->stock_actual,
            'referencia' => 'AGREGAR-STOCK-' . now()->format('YmdHis'),
            'motivo' => 'Stock agregado manualmente',
            'usuario_id' => Auth::id(),
            'fecha_movimiento' => now(),
        ]);

        session()->flash('message', 'Stock actualizado.');
        $this->emit('hideStockModal');
    }

    public function showDetailModal($id)
    {
        $this->detailProducto = Producto::with(['categoria'])->findOrFail($id);
        $this->emit('showDetailModal');
    }
    private function getFormData()
    {
        return [
            'nombre' => $this->nombre,
            'sucursal_id_form' => $this->sucursal_id_form,
            'categoria_id_form' => $this->categoria_id_form,
            'precio_venta' => $this->precio_venta,
            'precio_alquiler' => $this->precio_alquiler,
            'stock_actual' => $this->stock_actual,
            'stock_minimo' => $this->stock_minimo,
        ];
    }
    public function save()
    {
        $validator = Validator::make($this->getFormData(), [
            'nombre' => 'required|string|max:100',
            'sucursal_id_form' => 'required|exists:sucursals,id',
            'categoria_id_form' => 'required|exists:categoria_productos,id',
            'precio_venta' => 'nullable|numeric|min:0',
            'precio_alquiler' => 'nullable|numeric|min:0',
            'stock_actual' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
        ]);

        // Regla personalizada para existencia producto en sucursal
        $validator->after(function ($validator) {
            if ($this->productoSeleccionadoId) {
                $existeEnSucursal = StockPorSucursal::where('producto_id', $this->productoSeleccionadoId)->where('sucursal_id', $this->sucursal_id_form)->exists();

                if ($existeEnSucursal) {
                    $validator->errors()->add('productoSeleccionadoId', 'El producto ya existe en esta sucursal.');
                }
            }
        });

        if ($validator->fails()) {
            $this->setErrorBag($validator->errors());
            // dd($validator->errors());
            return;
        }

        $validated = $validator->validated();

        // Buscar si el producto ya existe por nombre (puedes incluir más filtros como color, talla, etc.)
        $producto = Producto::where('nombre', $validated['nombre'])->first();

        if (!$producto) {
            $producto = Producto::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $this->descripcion,
                'talla' => $this->talla,
                'color' => $this->color,
                'material' => $this->material,
                'disponible_venta' => $this->disponible_venta_form,
                'disponible_alquiler' => $this->disponible_alquiler_form,
                'categoria_id' => $validated['categoria_id_form'],
                'usuario_creacion' => Auth::id(),
                'codigo' => $this->generateCode(),
            ]);
        }

        // Verificar si ya existe el producto en esa sucursal

        // Crear el stock por sucursal
        StockPorSucursal::create([
            'producto_id' => $producto->id,
            'sucursal_id' => $validated['sucursal_id_form'],
            'stock_actual' => $this->stock_actual ?? 0,
            'stock_minimo' => $this->stock_minimo ?? 0,
            'stock_reservado' => 0,
            'stock_alquilado' => 0,
            'stock_vendido' => 0,
            'precio_venta_sucursal' => $this->precio_venta,
            'precio_alquiler_sucursal' => $this->precio_alquiler,
            'activo' => true,
        ]);

        // Registrar el movimiento
        MovimientoStockSucursal::create([
            'producto_id' => $producto->id,
            'sucursal_id' => $validated['sucursal_id_form'],
            'tipo_movimiento' => 'ENTRADA',
            'cantidad' => $this->stock_actual ?? 0,
            'stock_anterior' => 0,
            'stock_nuevo' => $this->stock_actual ?? 0,
            'referencia' => 'INI-' . now()->format('Ymd'),
            'motivo' => 'Stock inicial al registrar en otra sucursal',
            'usuario_id' => Auth::id(),
            'fecha_movimiento' => now(),
        ]);

        session()->flash('message', 'Producto registrado en la sucursal correctamente.');
        $this->emit('hideModal');
        $this->resetForm();
    }

    private function generateCode()
    {
        $base = strtoupper(substr($this->nombre, 0, 3));
        $contador = Producto::where('codigo', 'like', "$base-%")->count() + 1;
        return sprintf('%s-%04d', $base, $contador);
    }

public function resetForm()
{
    $this->reset([
        'productoSeleccionadoId',
        'nombre',
        'descripcion',
        'categoria_id_form',
        'sucursal_id_form',
        'precio_venta',
        'precio_alquiler',
        'stock_actual',
        'stock_minimo',
        'talla',
        'color',
        'material',
        'disponible_venta_form',
        'disponible_alquiler_form',
        'isEdit',
    ]);

    // Limpia errores de validación
    $this->resetValidation();
    $this->resetErrorBag();

}


    public function clearFilters()
    {
        $this->reset(['search', 'sucursal_id', 'categoria_id', 'estado_stock', 'disponible_venta', 'disponible_alquiler']);
        $this->resetPage();
    }
    public $productoSeleccionadoId = null;
    public $productosExistentes = []; // Lista real de productos para el select

    protected $listeners = ['productoSeleccionado', 'productoSeleccionadoActualizado'];

    public function productoSeleccionado($selected)
    {
        if (is_numeric($selected)) {
            $this->productoSeleccionadoId = $selected;
            $this->productosExistentes = true;

            $producto = Producto::find($selected);
            if ($producto) {
                $this->nombre = $producto->nombre;
                $this->descripcion = $producto->descripcion;
                $this->categoria_id_form = $producto->categoria_id;
                $this->talla = $producto->talla;
                $this->color = $producto->color;
                $this->material = $producto->material;
                // otros campos
            }
        } else {
            // Nuevo producto (texto libre)
            $this->productoSeleccionadoId = null;
            $this->productosExistentes = false;

            $this->nombre = $selected;
            $this->descripcion = '';
            $this->categoria_id_form = null;
            $this->talla = '';
            $this->color = '';
            $this->material = '';
            $this->mount();
        }
    }
    public function productoSeleccionadoActualizado($valor)
    {
        $this->productoSeleccionadoId = is_array($valor) ? $valor['id'] ?? null : null;
        $this->resetErrorBag('productoSeleccionadoId');
    }

}
