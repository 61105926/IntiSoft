<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use App\Models\StockHistories;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\StockHistory;

class ProductsController extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $nombre, $categoria, $stock, $precio=0, $image, $selected_id, $monto_comprado=0, $lote;
    public $componentName = 'Productos',
        $pageTitle = 'Listado de Productos',
        $search;
    private $pagination = 10;
    public $showingHistorial = false;
    public $selectedProductHistory = null;
    public $dateFrom, $dateTo;
    public $perPage = 10; // Valor por defecto

    public function mount()
    {
        $this->pageTitle = 'Listado de Productos';
        $this->componentName = 'Productos';
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    public $productFilter = 1; // Puede ser true, false, 'active', o 'inactive'
    public function searchProducts()
    {
        $query = Product::query();

        if (strlen($this->search) > 0) {
            $query->where(function ($query) {
                $query->where('nombre_producto', 'like', '%' . $this->search . '%')
                      ->orWhere('codigo_producto', 'like', '%' . $this->search . '%');
            });
            $this->resetPage();
        } else {
            $query->orderBy('id', 'desc');
        }

        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('created_at', [
                $this->dateFrom . ' 00:00:00',
                $this->dateTo . ' 23:59:59'
            ]);
        }
        if ($this->productFilter !== null) { // Verifica explícitamente si no es null
            $query->where('state', $this->productFilter);
        }
        

        $products = $query->paginate($this->perPage); // Usar perPage en lugar de pagination

        foreach ($products as $product) {
            $cantidadVendida = $product->detalles()->sum('quantity');
            $product->vendido = $cantidadVendida;
            $product->restante = $product->stock - $cantidadVendida;
        }

        return $products;
    }

    public $fecha_vencimiento; // Nueva propiedad para la fecha de vencimiento
    public function rules()
    {
        return [
            'nombre' => 'required',
            'categoria' => 'required',
            'stock' => 'required|integer',
            'precio' => 'required|numeric',
            // 'fecha_vencimiento' => 'required|date', // Requerido y validado como fecha
            // 'lote' => 'required|string|max:255', // Requerido y validado como texto
            'monto_comprado' => 'required|numeric|min:0', // Requerido y validado como número
        ];
    }
    public function codigo()
    {
        // Aseguramos que la categoría esté en minúsculas para una comparación más confiable
        $categoriaLowerCase = strtolower($this->categoria);
    
        // Obtener el último producto registrado con la misma categoría (case-insensitive)
        $lastProduct = Product::whereRaw('LOWER(categoria) = ?', [$categoriaLowerCase])
            ->orderBy('id', 'desc')
            ->first();
    
        // Inicializamos el número a 0 si no hay productos anteriores de esta categoría
        $lastNumber = 0;
    
        if ($lastProduct && !empty($lastProduct->codigo_producto)) {
            // Utilizamos una expresión regular para extraer el número después del prefijo
            if (preg_match('/[A-Z]+-(\d+)/', $lastProduct->codigo_producto, $matches)) {
                $lastNumber = intval($matches[1]); // Extraemos el número del último código
            }
        }
    
        // Definir prefijos para cada categoría específica
        $prefixes = [
            'servicio' => 'S',
            'producto' => 'P',
            'desparacitación interna' => 'DI',
            'desparacitación externa' => 'DE',
            'internación' => 'IN',
            'farmacia' => 'F',
            'baño y peluquería' => 'BP',
            'vacunas' => 'V',
        ];
    
        // Verificar si la categoría tiene un prefijo definido
        if (array_key_exists($categoriaLowerCase, $prefixes)) {
            // Retornar el código con el prefijo correspondiente y el número consecutivo
            return $prefixes[$categoriaLowerCase] . '-' . ($lastNumber + 1);
        }
    
        // En caso de una categoría no reconocida, devolver un valor genérico
        return 'GEN-' . uniqid();
    }
    public function store()
    {
        $this->validate();

        $product = new Product();
        $product->codigo_producto = $this->codigo();
        $product->nombre_producto = $this->nombre;
        $product->categoria = $this->categoria;
        $product->stock = $this->stock;
        $product->precio = $this->precio;
        $product->fecha_vencimiento = $this->fecha_vencimiento;
        $product->lote = $this->lote; // Asigna el lote
        $product->monto_comprado = $this->monto_comprado; // Asigna el monto de compra

        if ($this->image) {
            $customFileName = uniqid() . '.' . $this->image->extension();
            $filePath = 'public/Product/' . $customFileName;
            $image = Image::read($this->image)->encode();
            Storage::put($filePath, (string) $image);
        
            // Asegúrate de que el archivo tenga permisos 777
            $fullPath = storage_path('app/' . $filePath);
            chmod($fullPath, 0777);
        
            $product->foto_producto = $customFileName;
        }
        

        $product->save();

        // Registrar en el historial de stock
        StockHistories::create([
            'product_id' => $product->id,
            'cantidad' => $product->stock,
            'stock_anterior' => 0,
            'stock_nuevo' => $product->stock,
            'tipo_movimiento' => 'entrada',
            'referencia' => 'registro_inicial',
            'referencia_id' => $product->id,
            'observacion' => 'Registro inicial del producto'
        ]);

        $this->resetUI();
        $this->emit('person-added', 'Producto Registrado');
    }

    public function edit(Product $product)
    {
        $registroInicial = StockHistories::where('product_id', $product->id)
        ->where('referencia', 'registro_inicial')
        ->first();

        $this->stock = $registroInicial ? $registroInicial->stock_nuevo : 0;

        $this->selected_id = $product->id;
        $this->nombre = $product->nombre_producto;
        $this->categoria = $product->categoria;
        $this->precio = $product->precio;
        $this->fecha_vencimiento = $product->fecha_vencimiento;
        $this->lote = $product->lote; // Carga el lote
        $this->monto_comprado = $product->monto_comprado; // Carga el monto de compra

        $this->emit('show-modal', 'show');
    }

    public function update()
    {
        $this->validate();
    
        if ($this->selected_id) {
            $product = Product::find($this->selected_id);
    
            // Obtener el registro inicial de stock
            $registroInicial = StockHistories::where('product_id', $product->id)
                ->where('referencia', 'registro_inicial')
                ->first();
    
            if ($registroInicial && $this->stock != $registroInicial->stock_nuevo) {
                $diferenciaStock = $this->stock - $registroInicial->stock_nuevo;
    
                // Actualizar el registro inicial
                $registroInicial->update([
                    'stock_nuevo' => $this->stock,
                    'cantidad' => $this->stock, // La cantidad debe reflejar el stock inicial
                ]);
    
                // Recalcular los movimientos posteriores
                $movimientos = StockHistories::where('product_id', $product->id)
                    ->where('id', '>', $registroInicial->id)
                    ->orderBy('id') // Asegurarnos de procesar los movimientos en orden
                    ->get();
    
                $stockAnterior = $this->stock;
    
                foreach ($movimientos as $movimiento) {
                    $stockNuevo = ($movimiento->tipo_movimiento === 'entrada') 
                        ? $stockAnterior + $movimiento->cantidad 
                        : $stockAnterior - $movimiento->cantidad;
    
                    $movimiento->update([
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $stockNuevo,
                    ]);
    
                    $stockAnterior = $stockNuevo; // Preparar para el siguiente movimiento
                }
    
                // Actualizar el stock del producto
                $product->stock = $stockAnterior;
            }
    
            // Actualizar otros datos del producto
            $product->nombre_producto = $this->nombre;
            $product->categoria = $this->categoria;
            $product->precio = $this->precio;
            $product->fecha_vencimiento = $this->fecha_vencimiento;
            $product->lote = $this->lote;
            $product->monto_comprado = $this->monto_comprado;
    
            if ($this->image) {
                $customFileName = uniqid() . '.' . $this->image->extension();
                $filePath = 'public/Product/' . $customFileName;
                $image = Image::read($this->image)->encode();
                Storage::put($filePath, (string) $image);
                $product->foto_producto = $customFileName;
            }
    
            $product->save();
    
            $this->resetUI();
            $this->emit('person-updated', 'Producto Actualizado');
        }
    }
    

    protected $listeners = [
        'deleteRow' => 'destroy',
    ];

    public function destroy(Product $Product)
    {
        $Product->state = $Product->state == 0 ? 1 : 0;
        $Product->save();
        $this->resetUI();
        $this->emit('Product-delete', 'Producto Eliminada');
    }

    public function resetUI()
    {
        $this->reset();
        $this->componentName = 'Producto';
    }

    public $client_data;

    public function updatedClientId($value)
    {
        $this->client_data = Product::find($value); // Ajusta esto según tu modelo
    }
    public function render()
    {
        $data = $this->searchProducts();
        $clients = Product::all(); // Cargar todos los clientes

        return view('livewire.products.products', ['data' => $data, 'clients' => $clients]) // Pasar los clientes a la vista
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function toggleHistorial($productId)
    {
        if ($this->selectedProductHistory === $productId) {
            $this->selectedProductHistory = null;
            $this->showingHistorial = false;
        } else {
            $this->selectedProductHistory = $productId;
            $this->showingHistorial = true;
            $this->emit('toggleHistorial');
        }
    }

    public function getProductHistoryProperty()
    {
        if (!$this->selectedProductHistory) {
            return collect();
        }

        $product = Product::find($this->selectedProductHistory);
        
        return [
            'product' => $product,
            'history' => StockHistories::where('product_id', $this->selectedProductHistory)
                ->orderBy('created_at', 'desc')
                ->get()
        ];
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function generateInventoryReport()
    {
        $query = Product::query()
                ->where('state', 1) // Filtrar productos con estado 1
            ->when($this->dateFrom && $this->dateTo, function ($query) {
                $query->whereBetween('created_at', [
                    $this->dateFrom . ' 00:00:00',
                    $this->dateTo . ' 23:59:59'
                ]);
            });

        $productos = $query->get()->map(function ($producto) {
            // Obtener movimientos del período
            $movimientos = StockHistories::where('product_id', $producto->id)
                ->when($this->dateFrom && $this->dateTo, function ($query) {
                    $query->whereBetween('created_at', [
                        $this->dateFrom . ' 00:00:00',
                        $this->dateTo . ' 23:59:59'
                    ]);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            // Calcular totales de movimientos
            $entradas = $movimientos->where('tipo_movimiento', 'entrada')->sum('cantidad');
            $salidas = $movimientos->where('tipo_movimiento', 'salida')->sum('cantidad');

            $producto->stock = $producto->stock - $entradas + $salidas;
            $producto->total_entradas = $entradas;
            $producto->total_salidas = $salidas;
            $producto->movimientos = $movimientos;

            return $producto;
        });

        $data = [
            'productos' => $productos,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'totalProductos' => $productos->count(),
            'totalEntradas' => $productos->sum('total_entradas'),
            'totalSalidas' => $productos->sum('total_salidas'),
            'stockTotal' => $productos->sum('stock'),
            'valorInventario' => $productos->sum(function ($producto) {
                return $producto->stock * $producto->precio;
            }),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.inventory_report', $data);
        return response()->stream(
            function () use ($pdf) {
                echo $pdf->output();
            },
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename=ReporteInventario.pdf',
            ]
        );
    }

    public function toggleState($productId)
    {
        try {
            $product = Product::find($productId);
            $product->state = !$product->state;
            $product->save();

            $this->emit('toast', 'success', $product->state ? 'Producto activado correctamente.' : 'Producto desactivado correctamente.');
        } catch (\Exception $e) {
            $this->emit('toast', 'error', 'Error al cambiar el estado del producto.');
        }
    }
}
