<?php

namespace App\Http\Livewire\Sucursal;

use App\Models\CategoriaProducto;
use App\Models\DetalleTransferencia;
use App\Models\MovimientoStockSucursal;
use App\Models\Producto;
use App\Models\StockPorSucursal;
use App\Models\Sucursal;
use App\Models\TransferenciaSucursal;
use DB;
use Livewire\Component;
use Livewire\WithPagination;

class SucursalTransferenciaController extends Component
{
    use WithPagination;

    // Filtros y búsqueda
    public $searchTerm = '';
    public $filterSucursal = '';
    public $filterEstado = '';

    // Datos para selects
    public $sucursales = [];
    public $productos = [];
    public $estados = ['SIN_STOCK', 'STOCK_BAJO', 'STOCK_OK'];

    // Datos de transferencia
    public $transferencias = [];
    public $productos_seleccionados = [];

    public $sucursal_origen_id, $sucursal_destino_id, $motivo, $observaciones;
    public $producto_id, $cantidad;

    // Estadísticas
    public $estadisticas = [
        'total_items' => 0,
        'sin_stock' => 0,
        'stock_bajo' => 0,
        'stock_ok' => 0,
        'valor_total' => 0,
    ];
    public $tabActivo = 'stock'; // o el ID del tab que quieres que abra por defecto

    public function cambiarTab($tab)
    {
        $this->tabActivo = $tab;
    }

    // Modal detalle
    public $transferenciaSeleccionada;
    public $verDetalleModal = false;

    protected $updatesQueryString = ['searchTerm', 'filterSucursal', 'filterEstado', 'page'];

    public function mount()
    {
        $this->resetForm();
        $this->sucursales = Sucursal::pluck('nombre', 'id')->toArray();
        $this->productos = Producto::pluck('nombre', 'id')->toArray();
        $this->cargarTransferencias();
    }

    // Reset de inputs
    private function resetForm()
    {
        $this->productos_seleccionados = [];
        $this->sucursal_origen_id = null;
        $this->sucursal_destino_id = null;
        $this->motivo = null;
        $this->observaciones = null;
    }

    // Live updates
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }
    public function updatedFilterSucursal()
    {
        $this->resetPage();
    }
    public function updatedFilterEstado()
    {
        $this->resetPage();
    }

    // Lógica de selección de productos
    public function agregarProducto()
    {
        $this->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        if (collect($this->productos_seleccionados)->contains('producto_id', $this->producto_id)) {
            $this->dispatchBrowserEvent('error', ['message' => 'Producto ya agregado.']);
            return;
        }

        $producto = Producto::find($this->producto_id);
        $this->productos_seleccionados[] = [
            'producto_id' => $producto->id,
            'nombre' => $producto->nombre,
            'cantidad' => $this->cantidad,
        ];

        $this->producto_id = null;
        $this->cantidad = null;
    }

    public function eliminarProductoTransferencia($index)
    {
        unset($this->productos_seleccionados[$index]);
        $this->productos_seleccionados = array_values($this->productos_seleccionados);
    }
    public $mensajeExito;
    // Guardar nueva transferencia
 public function guardarTransferencia()
{
    $this->validate([
        'sucursal_origen_id' => 'required|exists:sucursals,id',
        'sucursal_destino_id' => 'required|exists:sucursals,id|different:sucursal_origen_id',
        'motivo' => 'required|string|min:3',
        'productos_seleccionados' => 'required|array|min:1',
    ]);

    DB::beginTransaction();

    try {
        $año = now()->year;
        $cantidad = TransferenciaSucursal::whereYear('fecha_solicitud', $año)->count() + 1;
        $numero_transferencia = 'TRANS-' . $año . '-' . str_pad($cantidad, 3, '0', STR_PAD_LEFT);

        // Crear la transferencia con estado 'SOLICITADA'
        $transferencia = TransferenciaSucursal::create([
            'numero_transferencia' => $numero_transferencia,
            'sucursal_origen_id' => $this->sucursal_origen_id,
            'sucursal_destino_id' => $this->sucursal_destino_id,
            'usuario_solicita_id' => auth()->id(),
            'fecha_solicitud' => now(),
            'estado' => 'SOLICITADA',
            'observaciones' => $this->observaciones,
            'motivo' => $this->motivo,
        ]);

        // Crear detalles de la transferencia sin modificar stock
        foreach ($this->productos_seleccionados as $detalle) {
            DetalleTransferencia::create([
                'transferencia_id' => $transferencia->id,
                'producto_id' => $detalle['producto_id'],
                'cantidad_solicitada' => $detalle['cantidad'],
            ]);
        }

        DB::commit();

        // Limpiar estado y cerrar modal
        $this->reset(['sucursal_origen_id', 'sucursal_destino_id', 'motivo', 'observaciones', 'productos_seleccionados']);

        $this->mensajeExito = 'Solicitud de transferencia creada con éxito.';
        $this->dispatchBrowserEvent('cerrar-modal-transferencia');
        $this->mount(); // Recargar listas o estados si usas mount()
    } catch (\Exception $e) {
        DB::rollBack();
        $this->dispatchBrowserEvent('transferencia-error', [
            'message' => 'Error al registrar transferencia: ' . $e->getMessage(),
        ]);
    }
}

  public function autorizarTransferencia($id)
{
    DB::beginTransaction();

    try {
        $transferencia = TransferenciaSucursal::with('detalleTransferencias.producto')->findOrFail($id);

        foreach ($transferencia->detalleTransferencias as $detalle) {
            $productoId = $detalle->producto_id;
            $cantidad = $detalle->cantidad_solicitada;

            // Producto original
            $productoOriginal = Producto::find($productoId);

            if (!$productoOriginal) {
                throw new \Exception("Producto original no encontrado.");
            }

            // Verificamos si el producto tiene stock en sucursal destino
            $stockDestino = StockPorSucursal::where('producto_id', $productoId)
                ->where('sucursal_id', $transferencia->sucursal_destino_id)
                ->first();
      
            if (!$stockDestino) {
                // No existe stock para ese producto en sucursal destino: 
                // Crearemos un nuevo producto para esa sucursal (variante) y su stock

                $categoriaId = $productoOriginal->categoria_id;
                $sucursalDestinoId = $transferencia->sucursal_destino_id;

                // Generar nuevo código para el producto variante en la sucursal destino
                $codigoGenerado = $this->generateCode($categoriaId, $sucursalDestinoId);

                // Crear nuevo producto
                $productoNuevo = Producto::create([
                    'categoria_id' => $categoriaId,
                    'codigo' => $codigoGenerado,
                    'nombre' => $productoOriginal->nombre,
                    'descripcion' => $productoOriginal->descripcion,
                    'talla' => $productoOriginal->talla,
                    'color' => $productoOriginal->color,
                    'material' => $productoOriginal->material,
                    'disponible_venta' => $productoOriginal->disponible_venta,
                    'disponible_alquiler' => $productoOriginal->disponible_alquiler,
                    'usuario_creacion' => auth()->id(),
                ]);

                $productoDestinoId = $productoNuevo->id;

                // Crear stock inicial para el nuevo producto en la sucursal destino
                $stockDestino = StockPorSucursal::create([
                    'producto_id' => $productoDestinoId,
                    'sucursal_id' => $sucursalDestinoId,
                    'stock_actual' => 0,
                    'stock_minimo' => 0,
                    'stock_reservado' => 0,
                    'stock_alquilado' => 0,
                    'stock_vendido' => 0,
                    'precio_venta_sucursal' => 0,
                    'precio_alquiler_sucursal' => 0,
                    'activo' => true,
                ]);
            } else {
                // Ya existe stock para el producto original en sucursal destino
                $productoDestinoId = $productoId;
            }

            // === STOCK EN ORIGEN ===
            $stockOrigen = StockPorSucursal::where('producto_id', $productoId)
                ->where('sucursal_id', $transferencia->sucursal_origen_id)
                ->first();

            if (!$stockOrigen || $stockOrigen->stock_actual < $cantidad) {
                throw new \Exception("Stock insuficiente para el producto {$productoOriginal->nombre} en la sucursal origen.");
            }

            $stockAnteriorOrigen = $stockOrigen->stock_actual;
            $stockOrigen->stock_actual -= $cantidad;
            $stockOrigen->save();

            MovimientoStockSucursal::create([
                'producto_id' => $productoId,
                'sucursal_id' => $transferencia->sucursal_origen_id,
                'tipo_movimiento' => 'SALIDA',
                'cantidad' => $cantidad,
                'stock_anterior' => $stockAnteriorOrigen,
                'stock_nuevo' => $stockOrigen->stock_actual,
                'referencia' => $transferencia->numero_transferencia,
                'motivo' => 'Transferencia a sucursal destino',
                'usuario_id' => auth()->id(),
                'fecha_movimiento' => now(),
            ]);

            // === STOCK EN DESTINO ===
            $stockAnteriorDestino = $stockDestino->stock_actual;
            $stockDestino->stock_actual += $cantidad;
            $stockDestino->save();

            MovimientoStockSucursal::create([
                'producto_id' => $productoDestinoId,
                'sucursal_id' => $transferencia->sucursal_destino_id,
                'tipo_movimiento' => 'ENTRADA',
                'cantidad' => $cantidad,
                'stock_anterior' => $stockAnteriorDestino,
                'stock_nuevo' => $stockDestino->stock_actual,
                'referencia' => $transferencia->numero_transferencia,
                'motivo' => 'Transferencia desde sucursal origen',
                'usuario_id' => auth()->id(),
                'fecha_movimiento' => now(),
            ]);
        }

        // Actualizar estado transferencia
        $transferencia->estado = 'AUTORIZADA';
        $transferencia->fecha_autorizacion = now();
        $transferencia->usuario_autoriza_id = auth()->id();
        $transferencia->save();

        DB::commit();

        session()->flash('message', 'Transferencia autorizada y stocks actualizados correctamente.');
    } catch (\Exception $e) {
        dd($e);
        DB::rollBack();
        $this->addError('transferencia', 'Error: ' . $e->getMessage());
    }
}
    private function generarNumeroTransferencia()
    {
        $año = now()->year;
        $cantidad = TransferenciaSucursal::whereYear('fecha_solicitud', $año)->count() + 1;
        return 'TRANS-' . $año . '-' . str_pad($cantidad, 3, '0', STR_PAD_LEFT);
    }

    // Cargar todas las transferencias para el histórico
    public function cargarTransferencias()
    {
        $this->transferencias = TransferenciaSucursal::with(['sucursalOrigen:id,nombre', 'sucursalDestino:id,nombre', 'usuarioSolicita:id,username', 'detalleTransferencias.producto:id,nombre'])
            ->orderByDesc('created_at')
            ->get();
    }
    private function generateCode($catId, $sucId)
    {
        $cat = CategoriaProducto::find($catId);
        $suc = Sucursal::find($sucId);
        $pref = strtoupper(substr($cat->nombre, 0, 3)) . '-' . strtoupper(substr($suc->nombre, 0, 3));
        // dd($pref);
        $year = now()->format('Y');
        $num = Producto::where('codigo', 'like', "{$pref}-{$year}-%")->count() + 1;
        return sprintf('%s-%s-%04d', $pref, $year, $num);
    }

 


    public function verDetalles($id)
    {
        $this->transferenciaSeleccionada = TransferenciaSucursal::with(['sucursalOrigen:id,nombre', 'sucursalDestino:id,nombre', 'usuarioSolicita:id,username', 'detalleTransferencias.producto:id,nombre'])->findOrFail($id);

        // Emitir evento para abrir modal
        $this->dispatchBrowserEvent('abrir-modal-detalle');
    }

    public function getEstadoTransferenciaBadge($estado)
    {
        $colores = [
            'SOLICITADA' => 'secondary',
            'AUTORIZADA' => 'primary',
            'ENVIADA' => 'warning',
            'RECIBIDA' => 'success',
            'CANCELADA' => 'danger',
        ];

        $color = $colores[$estado] ?? 'dark';
        return "<span class='badge bg-{$color}'>" . ucfirst(strtolower($estado)) . '</span>';
    }

    public function getEstadoBadge($estado)
    {
        return match ($estado) {
            'SIN_STOCK' => '<span class="badge bg-danger">Sin Stock</span>',
            'STOCK_BAJO' => '<span class="badge bg-warning text-dark">Stock Bajo</span>',
            'STOCK_OK' => '<span class="badge bg-success">Stock OK</span>',
            default => '<span class="badge bg-secondary">Desconocido</span>',
        };
    }

    public function editarStock($id)
    {
        $this->emit('editarStock', $id);
    }

    private function calcularEstadisticas($stocks)
    {
        $this->estadisticas = [
            'total_items' => $stocks->count(),
            'sin_stock' => 0,
            'stock_bajo' => 0,
            'stock_ok' => 0,
            'valor_total' => 0,
        ];

        foreach ($stocks as $stock) {
            $this->estadisticas['valor_total'] += $stock->valor_stock;
            match ($stock->estado_stock) {
                'SIN_STOCK' => $this->estadisticas['sin_stock']++,
                'STOCK_BAJO' => $this->estadisticas['stock_bajo']++,
                'STOCK_OK' => $this->estadisticas['stock_ok']++,
            };
        }
    }

    public function render()
    {
        $query = StockPorSucursal::with(['producto.categoria', 'sucursal'])->activos();

        if ($this->filterSucursal) {
            $query->where('sucursal_id', $this->filterSucursal);
        }

        if ($this->filterEstado) {
            $query->where(function ($q) {
                switch ($this->filterEstado) {
                    case 'SIN_STOCK':
                        $q->where('stock_actual', '<=', 0);
                        break;
                    case 'STOCK_BAJO':
                        $q->whereColumn('stock_actual', '<=', 'stock_minimo')->where('stock_actual', '>', 0);
                        break;
                    case 'STOCK_OK':
                        $q->where('stock_actual', '>', 'stock_minimo');
                        break;
                }
            });
        }

        if ($this->searchTerm) {
            $search = $this->searchTerm;
            $query->whereHas('producto', fn($q) => $q->where('nombre', 'like', "%$search%")->orWhere('codigo', 'like', "%$search%"));
        }

        $filteredStock = $query->orderByDesc('id')->paginate(10);
        $this->calcularEstadisticas($query->get());

        return view('livewire.sucursal.sucursal-transferencia', [
            'filteredStock' => $filteredStock,
            'sucursales' => $this->sucursales,
            'estados' => $this->estados,
            'estadisticas' => $this->estadisticas,
            'transferencias' => $this->transferencias,
        ])
            ->extends('layouts.theme.modern-app')
            ->section('content');
    }
}
