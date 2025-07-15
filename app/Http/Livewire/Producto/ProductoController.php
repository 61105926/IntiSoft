<?php

namespace App\Http\Livewire\Producto;

use App\Models\CategoriaProducto;
use App\Models\MovimientoStockSucursal;
use App\Models\Producto;
use App\Models\StockPorSucursal;
use App\Models\Sucursal;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;
class ProductoController extends Component
{
    use WithPagination;

    // Filtros y paginación
    public $search = '',
        $sucursal_id = '',
        $categoria_id = '',
        $estado_stock = '',
        $disponible_venta = '',
        $disponible_alquiler = '',
        $perPage = 10;

    // Modal y edición
    public $showModal = false,
        $isEdit = false,
        $editingId = null;
    public $showStockModal = false,
        $stockAdd = 0,
        $stockProductoId = null;
    public $showDetailModal = false,
        $detailProducto = null;

    // Campos formulario producto
    public $nombre,
        $descripcion,
        $talla,
        $color,
        $material,
        $precio_venta,
        $precio_alquiler,
        $stock_actual,
        $stock_minimo,
        $categoria_id_form,
        $sucursal_id_form,
        $disponible_venta_form = false,
        $disponible_alquiler_form = false;

    protected $paginationTheme = 'bootstrap';

    // Reset pagina en filtros
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

    public function render()
    {
        $productos = Producto::with(['sucursal', 'categoria'])
            ->when($this->search, fn($q) => $q->where('nombre', 'like', '%' . $this->search . '%')->orWhere('descripcion', 'like', '%' . $this->search . '%'))
            ->when($this->sucursal_id, fn($q) => $q->where('sucursal_id', $this->sucursal_id))
            ->when($this->categoria_id, fn($q) => $q->where('categoria_id', $this->categoria_id))
            ->when($this->estado_stock === 'sin_stock', fn($q) => $q->where('stock_actual', '<=', 0))
            ->when($this->estado_stock === 'stock_bajo', fn($q) => $q->whereColumn('stock_actual', '<=', 'stock_minimo'))
            ->when($this->disponible_venta !== '', fn($q) => $q->where('disponible_venta', $this->disponible_venta))
            ->when($this->disponible_alquiler !== '', fn($q) => $q->where('disponible_alquiler', $this->disponible_alquiler))
            ->orderBy('nombre')
            ->paginate($this->perPage);

        return view('livewire.producto.producto', [
            'productos' => $productos,
            'sucursales' => Sucursal::activo()->get(),
            'categorias' => CategoriaProducto::all(),
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    // Mostrar modal crear
    public function showCreateModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->emit('showModal');
    }

    // Mostrar modal editar
 

    // Mostrar modal agregar stock
    public function showStockModal($id)
    {
        $this->stockProductoId = $id;
        $this->stockAdd = 0;
        $this->emit('showStockModal');
    }

    // Añadir stock
    public function showEditModal($id)
{
    $prod = Producto::findOrFail($id);

    $this->editingId          = $id;
    $this->isEdit             = true;
    $this->nombre             = $prod->nombre;
    $this->descripcion        = $prod->descripcion;
    $this->talla              = $prod->talla;
    $this->color              = $prod->color;
    $this->material           = $prod->material;
    $this->precio_venta       = $prod->precio_venta;
    $this->precio_alquiler    = $prod->precio_alquiler;
    $this->stock_actual       = $prod->stock_actual;
    $this->stock_minimo       = $prod->stock_minimo;
    $this->categoria_id_form  = $prod->categoria_id;
    $this->sucursal_id_form   = $prod->sucursal_id;
    $this->disponible_venta_form    = $prod->disponible_venta;
    $this->disponible_alquiler_form = $prod->disponible_alquiler;

    $this->emit('showModal');
}

    public function addStock()
    {
        $this->validate(['stockAdd' => 'required|integer|min:1']);
        $prod = Producto::findOrFail($this->stockProductoId);
        $prod->increment('stock_actual', $this->stockAdd);
        session()->flash('message', 'Stock actualizado.');
        $this->emit('hideStockModal');
    }

    // Mostrar detalle
    public function showDetailModal($id)
    {
        $this->detailProducto = Producto::with(['sucursal', 'categoria'])->findOrFail($id);
        $this->emit('showDetailModal');
    }

    // Guardar o actualizar
    public function save()
    {
        // Validación
        $rules = [
            'nombre' => 'required|string|max:100',
            'sucursal_id_form' => 'required|exists:sucursals,id',
            'categoria_id_form' => 'required|exists:categoria_productos,id',
            'precio_venta' => 'nullable|numeric|min:0',
            'precio_alquiler' => 'nullable|numeric|min:0',
            'stock_actual' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
        ];
        $validated = $this->validate($rules);

        // Generar código
        $codigo = $this->generateCode($validated['categoria_id_form'], $validated['sucursal_id_form']);

        // Armar datos correctos para la tabla productos
        $data = [
            'nombre' => $validated['nombre'],
            'descripcion' => $this->descripcion,
            'talla' => $this->talla,
            'color' => $this->color,
            'material' => $this->material,
            'precio_venta' => $validated['precio_venta'],
            'precio_alquiler' => $validated['precio_alquiler'],
            'stock_actual' => $this->stock_actual,
            'stock_minimo' => $this->stock_minimo,
            'disponible_venta' => $this->disponible_venta_form,
            'disponible_alquiler' => $this->disponible_alquiler_form,
            'codigo' => $codigo,
            'usuario_creacion' => Auth::id(),
            // **Aquí los nombres reales de columnas**:
            'sucursal_id' => $validated['sucursal_id_form'],
            'categoria_id' => $validated['categoria_id_form'],
        ];

        if ($this->isEdit && $this->editingId) {
            // Actualizar
            Producto::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Producto actualizado correctamente.');
        } else {
            // Crear nuevo
            $producto = Producto::create($data);
            session()->flash('message', 'Producto creado correctamente.');

            // Registrar stock y movimiento (igual que antes)…
            StockPorSucursal::create([
                'producto_id' => $producto->id,
                'sucursal_id' => $data['sucursal_id'],
                'stock_actual' => $data['stock_actual'] ?? 0,
                'stock_minimo' => $data['stock_minimo'] ?? 0,
                'stock_reservado' => 0,
                'stock_alquilado' => 0,
                'stock_vendido' => 0,
                'precio_venta_sucursal' => $data['precio_venta'],
                'precio_alquiler_sucursal' => $data['precio_alquiler'],
                'activo' => true,
            ]);

            MovimientoStockSucursal::create([
                'producto_id' => $producto->id,
                'sucursal_id' => $data['sucursal_id'],
                'tipo_movimiento' => 'ENTRADA',
                'cantidad' => $data['stock_actual'] ?? 0,
                'stock_anterior' => 0,
                'stock_nuevo' => $data['stock_actual'] ?? 0,
                'referencia' => 'INI-' . now()->format('Ymd'),
                'motivo' => 'Stock inicial al crear producto',
                'usuario_id' => Auth::id(),
                'fecha_movimiento' => now(),
            ]);
        }

        $this->emit('hideModal');
        $this->resetForm();
    }

    // Genera código
    private function generateCode($catId, $sucId)
    {
        $cat = CategoriaProducto::find($catId);
        $suc = Sucursal::find($sucId);
        $pref = strtoupper(substr($cat->nombre, 0, 3)) . '-' . strtoupper(substr($suc->nombre, 0, 3));
        $year = now()->format('Y');
        $num = Producto::where('codigo', 'like', "{$pref}-{$year}-%")->count() + 1;
        return sprintf('%s-%s-%04d', $pref, $year, $num);
    }

    private function resetForm()
    {
        $this->reset(['nombre', 'descripcion', 'talla', 'color', 'material', 'precio_venta', 'precio_alquiler', 'stock_actual', 'stock_minimo', 'categoria_id_form', 'sucursal_id_form', 'disponible_venta_form', 'disponible_alquiler_form', 'editingId']);
        $this->isEdit = false;
    }

    public function clearFilters()
    {
        $this->reset(['search', 'sucursal_id', 'categoria_id', 'estado_stock', 'disponible_venta', 'disponible_alquiler']);
        $this->resetPage();
    }
}
