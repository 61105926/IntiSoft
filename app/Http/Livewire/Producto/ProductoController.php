<?php

namespace App\Http\Livewire\Producto;

use App\Models\{CategoriaProducto, MovimientoStockSucursal, Producto, StockPorSucursal, Sucursal};
use Illuminate\Support\Facades\{Auth, Validator, Storage};
use Livewire\{Component, WithPagination, WithFileUploads};

class ProductoController extends Component
{
    use WithPagination, WithFileUploads;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    protected $listeners = ['productoSeleccionado', 'productoSeleccionadoActualizado'];

    // Filters
    public $search = '';
    public $sucursal_id = '';
    public $categoria_id = '';
    public $estado_stock = '';
    public $disponible_venta = '';
    public $disponible_alquiler = '';
    public $perPage = 10;

    // Modal states
    public $showModal = false;
    public $showStockModal = false;
    public $showDetailModal = false;
    public $isEdit = false;

    // Form fields
    public $productoSeleccionadoId = null;
    public $producto_id = null;
    public $nombre;
    public $descripcion;
    public $talla;
    public $color;
    public $material;
    public $precio_venta;
    public $precio_alquiler;
    public $stock_actual;
    public $stock_minimo;
    public $categoria_id_form;
    public $sucursal_id_form;
    public $disponible_venta_form = false;
    public $disponible_alquiler_form = false;
    public $stockAdd = 0;
    public $stockProductoId = null;
    public $detailProducto = null;
    public $productosExistentes = false;
    
    // Image handling
    public $imagen_principal;
    public $imagenes_adicionales = [];
    public $codigo_barras;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingSucursalId() { $this->resetPage(); }
    public function updatingCategoriaId() { $this->resetPage(); }

  public function render()
    {
        return view('livewire.producto.producto', [
            'productosAll' => Producto::all(),
            'productos' => $this->getFilteredProductos()->paginate($this->perPage),
            'sucursales' => Sucursal::activo()->get(),
            'categorias' => CategoriaProducto::all(),
            'estadisticas' => $this->getEstadisticas(), // Add statistics
        ]);
    }

    private function getEstadisticas()
    {
        $query = StockPorSucursal::query()
            ->join('productos', 'stock_por_sucursals.producto_id', '=', 'productos.id')
            ->when($this->sucursal_id, fn($q) => $q->where('stock_por_sucursals.sucursal_id', $this->sucursal_id))
            ->when($this->categoria_id, fn($q) => $q->where('productos.categoria_id', $this->categoria_id))
            ->when($this->search, fn($q) => $q->where('productos.nombre', 'like', "%{$this->search}%")
                ->orWhere('productos.descripcion', 'like', "%{$this->search}%"))
            ->when($this->disponible_venta !== '', fn($q) => $q->where('productos.disponible_venta', $this->disponible_venta))
            ->when($this->disponible_alquiler !== '', fn($q) => $q->where('productos.disponible_alquiler', $this->disponible_alquiler));

        return [
            'total_items' => $query->count(),
            'sin_stock' => $query->clone()->where('stock_por_sucursals.stock_actual', '<=', 0)->count(),
            'stock_bajo' => $query->clone()->where('stock_por_sucursals.stock_actual', '>', 0)
                ->whereColumn('stock_por_sucursals.stock_actual', '<=', 'stock_por_sucursals.stock_minimo')
                ->count(),
            'stock_ok' => $query->clone()->whereColumn('stock_por_sucursals.stock_actual', '>', 'stock_por_sucursals.stock_minimo')
                ->count(),
        ];
    }
    private function getFilteredProductos()
    {
        $query = Producto::query()
            ->select('productos.*', 'sps.stock_actual', 'sps.stock_minimo', 'sps.precio_venta_sucursal', 'sps.precio_alquiler_sucursal', 'sps.sucursal_id')
            ->join('stock_por_sucursals as sps', 'productos.id', '=', 'sps.producto_id')
            ->with('categoria')
            ->when($this->sucursal_id, fn($q) => $q->where('sps.sucursal_id', $this->sucursal_id))
            ->when($this->search, fn($q) => $q->where('productos.nombre', 'like', "%{$this->search}%")
                ->orWhere('productos.descripcion', 'like', "%{$this->search}%"))
            ->when($this->categoria_id, fn($q) => $q->where('productos.categoria_id', $this->categoria_id))
            ->when($this->estado_stock === 'sin_stock', fn($q) => $q->where('sps.stock_actual', '<=', 0))
            ->when($this->estado_stock === 'stock_bajo', fn($q) => $q->whereColumn('sps.stock_actual', '<=', 'sps.stock_minimo'))
            ->when($this->disponible_venta !== '', fn($q) => $q->where('productos.disponible_venta', $this->disponible_venta))
            ->when($this->disponible_alquiler !== '', fn($q) => $q->where('productos.disponible_alquiler', $this->disponible_alquiler))
            ->orderBy('productos.nombre');

        return $query;
    }

    public function showCreateModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function showEditModal($productoId, $sucursalId)
    {
        $this->isEdit = true;
        $producto = Producto::findOrFail($productoId);
        $stockSucursal = StockPorSucursal::where('producto_id', $productoId)
            ->where('sucursal_id', $sucursalId)
            ->firstOrFail();

        $this->productoSeleccionadoId = $producto->id;
        $this->producto_id = $producto->id;
        $this->fill([
            'nombre' => $producto->nombre,
            'descripcion' => $producto->descripcion,
            'categoria_id_form' => $producto->categoria_id,
            'talla' => $producto->talla,
            'color' => $producto->color,
            'material' => $producto->material,
            'disponible_venta_form' => $producto->disponible_venta,
            'disponible_alquiler_form' => $producto->disponible_alquiler,
            'sucursal_id_form' => $sucursalId,
            'stock_actual' => $stockSucursal->stock_actual ?? 0,
            'stock_minimo' => $stockSucursal->stock_minimo ?? 0,
            'precio_venta' => $stockSucursal->precio_venta_sucursal ?? 0,
            'precio_alquiler' => $stockSucursal->precio_alquiler_sucursal ?? 0,
            'codigo_barras' => $producto->codigo_barras,
            'productosExistentes' => true,
        ]);

        $this->showModal = true;
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

        $stock = StockPorSucursal::where('producto_id', $this->stockProductoId)
            ->where('sucursal_id', $this->sucursal_id)
            ->first();

        if (!$stock) {
            return session()->flash('error', 'No se encontró stock para este producto en la sucursal seleccionada.');
        }

        $stock->increment('stock_actual', $this->stockAdd);
        $this->createStockMovement($stock, 'ENTRADA', $this->stockAdd, 'Stock agregado manualmente', 'AGREGAR-STOCK-');

        session()->flash('message', 'Stock actualizado.');
        $this->emit('hideStockModal');
    }

    public function showDetailModal($id)
    {
        $this->detailProducto = Producto::with('categoria')->findOrFail($id);
        $this->emit('showDetailModal');
    }

    public function save()
    {
        $validated = $this->validateForm();

        if ($this->isEdit) {
            $this->updateProducto($validated);
        } else {
            $this->createProducto($validated);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    private function validateForm()
    {
        $validator = Validator::make($this->getFormData(), [
            'nombre' => 'required|string|max:100',
            'sucursal_id_form' => 'required|exists:sucursals,id',
            'categoria_id_form' => 'required|exists:categoria_productos,id',
            'precio_venta' => 'nullable|numeric|min:0',
            'precio_alquiler' => 'nullable|numeric|min:0',
            'stock_actual' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
            'imagen_principal' => 'nullable|image|max:2048',
            'imagenes_adicionales.*' => 'nullable|image|max:2048',
            'codigo_barras' => 'nullable|string|max:100',
        ]);

        if (!$this->isEdit && $this->productoSeleccionadoId) {
            $validator->after(function ($validator) {
                if (StockPorSucursal::where('producto_id', $this->productoSeleccionadoId)
                    ->where('sucursal_id', $this->sucursal_id_form)
                    ->exists()) {
                    $validator->errors()->add('productoSeleccionadoId', 'El producto ya existe en esta sucursal.');
                }
            });
        }

        if ($validator->fails()) {
            $this->setErrorBag($validator->errors());
            return;
        }

        return $validator->validated();
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
            'imagen_principal' => $this->imagen_principal,
            'imagenes_adicionales' => $this->imagenes_adicionales,
            'codigo_barras' => $this->codigo_barras,
        ];
    }

    private function updateProducto($validated)
    {
        $producto = Producto::findOrFail($this->productoSeleccionadoId);
        
        $updateData = [
            'descripcion' => $this->descripcion,
            'categoria_id' => $validated['categoria_id_form'],
            'talla' => $this->talla,
            'color' => $this->color,
            'material' => $this->material,
            'disponible_venta' => $this->disponible_venta_form,
            'disponible_alquiler' => $this->disponible_alquiler_form,
            'codigo_barras' => $this->codigo_barras,
        ];
        
        // Handle image upload
        if ($this->imagen_principal) {
            // Delete old image if exists
            if ($producto->imagen_principal) {
                Storage::disk('public')->delete($producto->imagen_principal);
            }
            $updateData['imagen_principal'] = $this->imagen_principal->store('productos', 'public');
        }
        
        // Handle additional images
        if (!empty($this->imagenes_adicionales)) {
            $imagenesUrls = [];
            foreach ($this->imagenes_adicionales as $imagen) {
                if ($imagen) {
                    $imagenesUrls[] = $imagen->store('productos', 'public');
                }
            }
            if (!empty($imagenesUrls)) {
                $updateData['imagenes_adicionales'] = $imagenesUrls;
            }
        }
        
        $producto->update($updateData);

        $stock = StockPorSucursal::where('producto_id', $producto->id)
            ->where('sucursal_id', $validated['sucursal_id_form'])
            ->firstOrFail();

        $stockAnterior = $stock->stock_actual;
        $stock->update([
            'stock_actual' => $this->stock_actual ?? 0,
            'stock_minimo' => $this->stock_minimo ?? 0,
            'precio_venta_sucursal' => $this->precio_venta,
            'precio_alquiler_sucursal' => $this->precio_alquiler,
        ]);

        if ($stockAnterior != ($this->stock_actual ?? 0)) {
            $this->createStockMovement($stock, 'AJUSTE', ($this->stock_actual ?? 0) - $stockAnterior, 'Ajuste por edición de producto', 'EDT-');
        }

        session()->flash('message', 'Producto actualizado correctamente.');
    }

    private function createProducto($validated)
    {
        $createData = [
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
            'codigo_barras' => $this->codigo_barras,
        ];
        
        // Handle image upload
        if ($this->imagen_principal) {
            $createData['imagen_principal'] = $this->imagen_principal->store('productos', 'public');
        }
        
        // Handle additional images
        if (!empty($this->imagenes_adicionales)) {
            $imagenesUrls = [];
            foreach ($this->imagenes_adicionales as $imagen) {
                if ($imagen) {
                    $imagenesUrls[] = $imagen->store('productos', 'public');
                }
            }
            if (!empty($imagenesUrls)) {
                $createData['imagenes_adicionales'] = $imagenesUrls;
            }
        }
        
        $producto = Producto::where('nombre', $validated['nombre'])->first() ?? Producto::create($createData);

        $stock = StockPorSucursal::create([
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

        $this->createStockMovement($stock, 'ENTRADA', $this->stock_actual ?? 0, 'Stock inicial al registrar en otra sucursal', 'INI-');

        session()->flash('message', 'Producto registrado en la sucursal correctamente.');
    }

    private function createStockMovement($stock, $tipo, $cantidad, $motivo, $referenciaPrefix)
    {
        MovimientoStockSucursal::create([
            'producto_id' => $stock->producto_id,
            'sucursal_id' => $stock->sucursal_id,
            'tipo_movimiento' => $tipo,
            'cantidad' => $cantidad,
            'stock_anterior' => $tipo === 'ENTRADA' ? $stock->stock_actual - $cantidad : $stock->stock_actual,
            'stock_nuevo' => $stock->stock_actual,
            'referencia' => $referenciaPrefix . now()->format('YmdHis'),
            'motivo' => $motivo,
            'usuario_id' => Auth::id(),
            'fecha_movimiento' => now(),
        ]);
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
            'producto_id',
            'nombre',
            'descripcion',
            'categoria_id_form',
            'sucursal_id_form',
            'precio_venta',
            'precio_alquiler',
            'stock_actual',
            'imagen_principal',
            'imagenes_adicionales',
            'codigo_barras',
            'stock_minimo',
            'talla',
            'color',
            'material',
            'disponible_venta_form',
            'disponible_alquiler_form',
            'isEdit',
            'productosExistentes',
        ]);
        $this->showModal = false;
        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'sucursal_id', 'categoria_id', 'estado_stock', 'disponible_venta', 'disponible_alquiler']);
        $this->resetPage();
    }

    public function productoSeleccionado($selected)
    {
        if (is_numeric($selected)) {
            $producto = Producto::find($selected);
            if ($producto) {
                $this->fill([
                    'productoSeleccionadoId' => $selected,
                    'nombre' => $producto->nombre,
                    'descripcion' => $producto->descripcion,
                    'categoria_id_form' => $producto->categoria_id,
                    'talla' => $producto->talla,
                    'color' => $producto->color,
                    'material' => $producto->material,
                    'productosExistentes' => true,
                ]);
            }
        } else {
            $this->fill([
                'productoSeleccionadoId' => null,
                'nombre' => $selected,
                'descripcion' => '',
                'categoria_id_form' => null,
                'talla' => '',
                'color' => '',
                'material' => '',
                'productosExistentes' => false,
            ]);
        }
    }

    public function productoSeleccionadoActualizado($valor)
    {
        $this->productoSeleccionadoId = is_array($valor) ? $valor['id'] ?? null : null;
        $this->resetErrorBag('productoSeleccionadoId');
    }
}