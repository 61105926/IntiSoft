<?php

namespace App\Http\Livewire\Stock;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StockPorSucursal;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\HistorialProducto;
use App\Services\StockService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockController extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    // Filtros y búsqueda
    public $searchTerm = '';
    public $filterSucursal = 'TODAS';
    public $filterCategoria = 'TODAS';
    public $filterEstado = 'TODOS';
    public $filterAlerta = 'TODAS';

    // Modal states
    public $showAjusteModal = false;
    public $showTransferenciaModal = false;
    public $showHistorialModal = false;
    public $showAlertasModal = false;

    // Ajuste de stock
    public $ajuste_producto_id = '';
    public $ajuste_sucursal_id = '';
    public $ajuste_cantidad_actual = 0;
    public $ajuste_nueva_cantidad = 0;
    public $ajuste_observaciones = '';

    // Transferencia de stock
    public $transferencia_producto_id = '';
    public $transferencia_sucursal_origen = '';
    public $transferencia_sucursal_destino = '';
    public $transferencia_cantidad = 0;
    public $transferencia_observaciones = '';

    // Historial
    public $historial_producto_id = '';
    public $historial_sucursal_id = '';

    // Objeto seleccionado
    public $selectedStock = null;
    public $selectedProducto = null;

    protected $stockService;

    public function boot()
    {
        $this->stockService = app(StockService::class);
    }

    public function mount()
    {
        $this->filterSucursal = Auth::user()->sucursal_id ?? 'TODAS';
    }

    public function render()
    {
        $stocks = $this->getFilteredStocks();
        $productos = Producto::where('activo', true)->orderBy('nombre')->get();
        $sucursales = Sucursal::orderBy('nombre')->get();
        $estadisticas = $this->getEstadisticas();
        $alertas = $this->getAlertas();

        return view('livewire.stock.stock', [
            'stocks' => $stocks,
            'productos' => $productos,
            'sucursales' => $sucursales,
            'estadisticas' => $estadisticas,
            'alertas' => $alertas,
        ])->extends('layouts.theme.modern-app')->section('content');
    }

    private function getFilteredStocks()
    {
        $query = StockPorSucursal::with(['producto', 'sucursal'])
                                ->whereHas('producto', function($q) {
                                    if ($this->searchTerm) {
                                        $q->where('nombre', 'like', '%' . $this->searchTerm . '%')
                                          ->orWhere('codigo', 'like', '%' . $this->searchTerm . '%');
                                    }
                                    if ($this->filterCategoria !== 'TODAS') {
                                        $q->where('categoria_id', $this->filterCategoria);
                                    }
                                    if ($this->filterEstado !== 'TODOS') {
                                        $q->where('estado', $this->filterEstado);
                                    }
                                });

        if ($this->filterSucursal !== 'TODAS') {
            $query->where('sucursal_id', $this->filterSucursal);
        }

        // Filtro por alertas
        if ($this->filterAlerta !== 'TODAS') {
            switch ($this->filterAlerta) {
                case 'AGOTADO':
                    $query->where('stock_disponible', '<=', 0);
                    break;
                case 'BAJO':
                    $query->whereColumn('stock_disponible', '<=', 'stock_minimo');
                    break;
                case 'EXCESIVO':
                    $query->whereColumn('stock_total', '>=', 'stock_maximo');
                    break;
            }
        }

        return $query->orderBy('stock_disponible', 'asc')
                    ->orderBy('stock_total', 'desc')
                    ->paginate(15);
    }

    private function getEstadisticas()
    {
        $query = StockPorSucursal::with('producto');

        if ($this->filterSucursal !== 'TODAS') {
            $query->where('sucursal_id', $this->filterSucursal);
        }

        $stocks = $query->get();

        return [
            'total_productos' => $stocks->count(),
            'stock_total' => $stocks->sum('stock_total'),
            'stock_disponible' => $stocks->sum('stock_disponible'),
            'stock_reservado' => $stocks->sum('stock_reservado'),
            'stock_alquilado' => $stocks->sum('stock_alquilado'),
            'stock_en_eventos' => $stocks->sum('stock_en_eventos'),
            'stock_mantenimiento' => $stocks->sum('stock_mantenimiento'),
            'productos_agotados' => $stocks->where('stock_disponible', '<=', 0)->count(),
            'productos_bajo_stock' => $stocks->filter(function($stock) {
                return $stock->stock_disponible <= $stock->stock_minimo && $stock->stock_disponible > 0;
            })->count(),
        ];
    }

    private function getAlertas()
    {
        $sucursalId = $this->filterSucursal !== 'TODAS' ? $this->filterSucursal : null;
        return $this->stockService->obtenerAlertasStock($sucursalId);
    }

    // Métodos para ajuste de stock
    public function openAjusteModal($stockId)
    {
        $this->selectedStock = StockPorSucursal::with(['producto', 'sucursal'])->find($stockId);
        $this->ajuste_producto_id = $this->selectedStock->producto_id;
        $this->ajuste_sucursal_id = $this->selectedStock->sucursal_id;
        $this->ajuste_cantidad_actual = $this->selectedStock->stock_total;
        $this->ajuste_nueva_cantidad = $this->selectedStock->stock_total;
        $this->ajuste_observaciones = '';
        $this->showAjusteModal = true;
    }

    public function closeAjusteModal()
    {
        $this->showAjusteModal = false;
        $this->selectedStock = null;
        $this->resetAjusteForm();
    }

    public function saveAjusteStock()
    {
        $this->validate([
            'ajuste_nueva_cantidad' => 'required|integer|min:0',
            'ajuste_observaciones' => 'required|string|max:255',
        ]);

        try {
            $this->stockService->actualizarStock([
                'operacion' => 'AJUSTE',
                'producto_id' => $this->ajuste_producto_id,
                'sucursal_id' => $this->ajuste_sucursal_id,
                'nueva_cantidad' => $this->ajuste_nueva_cantidad,
                'observaciones' => $this->ajuste_observaciones,
                'numero_operacion' => 'AJUSTE-' . now()->format('YmdHis'),
            ]);

            session()->flash('success', 'Stock ajustado exitosamente.');
            $this->closeAjusteModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Error al ajustar stock: ' . $e->getMessage());
        }
    }

    // Métodos para transferencia de stock
    public function openTransferenciaModal($stockId)
    {
        $this->selectedStock = StockPorSucursal::with(['producto', 'sucursal'])->find($stockId);
        $this->transferencia_producto_id = $this->selectedStock->producto_id;
        $this->transferencia_sucursal_origen = $this->selectedStock->sucursal_id;
        $this->transferencia_sucursal_destino = '';
        $this->transferencia_cantidad = 0;
        $this->transferencia_observaciones = '';
        $this->showTransferenciaModal = true;
    }

    public function closeTransferenciaModal()
    {
        $this->showTransferenciaModal = false;
        $this->selectedStock = null;
        $this->resetTransferenciaForm();
    }

    public function saveTransferenciaStock()
    {
        $this->validate([
            'transferencia_sucursal_destino' => 'required|exists:sucursals,id|different:transferencia_sucursal_origen',
            'transferencia_cantidad' => 'required|integer|min:1',
            'transferencia_observaciones' => 'required|string|max:255',
        ]);

        try {
            $this->stockService->transferirStock(
                $this->transferencia_producto_id,
                $this->transferencia_sucursal_origen,
                $this->transferencia_sucursal_destino,
                $this->transferencia_cantidad,
                $this->transferencia_observaciones
            );

            session()->flash('success', 'Transferencia de stock realizada exitosamente.');
            $this->closeTransferenciaModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Error al transferir stock: ' . $e->getMessage());
        }
    }

    // Métodos para historial
    public function openHistorialModal($stockId)
    {
        $this->selectedStock = StockPorSucursal::with(['producto', 'sucursal'])->find($stockId);
        $this->historial_producto_id = $this->selectedStock->producto_id;
        $this->historial_sucursal_id = $this->selectedStock->sucursal_id;
        $this->showHistorialModal = true;
    }

    public function closeHistorialModal()
    {
        $this->showHistorialModal = false;
        $this->selectedStock = null;
    }

    public function getHistorialMovimientos()
    {
        if (!$this->historial_producto_id) return collect();

        return HistorialProducto::obtenerHistorialProducto(
            $this->historial_producto_id,
            $this->historial_sucursal_id,
            50
        );
    }

    // Métodos para alertas
    public function openAlertasModal()
    {
        $this->showAlertasModal = true;
    }

    public function closeAlertasModal()
    {
        $this->showAlertasModal = false;
    }

    // Métodos de utilidad
    private function resetAjusteForm()
    {
        $this->ajuste_producto_id = '';
        $this->ajuste_sucursal_id = '';
        $this->ajuste_cantidad_actual = 0;
        $this->ajuste_nueva_cantidad = 0;
        $this->ajuste_observaciones = '';
    }

    private function resetTransferenciaForm()
    {
        $this->transferencia_producto_id = '';
        $this->transferencia_sucursal_origen = '';
        $this->transferencia_sucursal_destino = '';
        $this->transferencia_cantidad = 0;
        $this->transferencia_observaciones = '';
    }

    // Métodos para mantenimiento
    public function moverAMantenimiento($stockId)
    {
        try {
            $stock = StockPorSucursal::with('producto')->find($stockId);

            $this->stockService->actualizarStock([
                'operacion' => 'MANTENIMIENTO',
                'producto_id' => $stock->producto_id,
                'sucursal_id' => $stock->sucursal_id,
                'cantidad' => 1,
                'observaciones' => 'Movido a mantenimiento manualmente',
                'numero_operacion' => 'MANT-' . now()->format('YmdHis'),
            ]);

            session()->flash('success', 'Producto movido a mantenimiento.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function volverDeMantenimiento($stockId)
    {
        try {
            $stock = StockPorSucursal::with('producto')->find($stockId);

            if ($stock->stock_mantenimiento <= 0) {
                throw new \Exception('No hay productos en mantenimiento');
            }

            // Mover de mantenimiento a disponible
            $stock->decrement('stock_mantenimiento', 1);
            $stock->increment('stock_disponible', 1);

            // Registrar en historial
            HistorialProducto::registrarMovimiento([
                'producto_id' => $stock->producto_id,
                'tipo_movimiento' => HistorialProducto::TIPO_ENTRADA,
                'referencia_tipo' => HistorialProducto::REF_MANTENIMIENTO,
                'referencia_id' => 0,
                'sucursal_id' => $stock->sucursal_id,
                'cantidad_anterior' => $stock->stock_disponible - 1,
                'cantidad_movimiento' => 1,
                'cantidad_posterior' => $stock->stock_disponible,
                'usuario_id' => Auth::id(),
                'observaciones' => 'Vuelta de mantenimiento - Producto reparado',
            ]);

            session()->flash('success', 'Producto devuelto de mantenimiento.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    // Exportar datos
    public function exportarStock()
    {
        // Implementar exportación a Excel o CSV
        session()->flash('info', 'Funcionalidad de exportación en desarrollo.');
    }

    public function generarReporteRotacion()
    {
        // Implementar reporte de rotación
        session()->flash('info', 'Reporte de rotación en desarrollo.');
    }

    // Filtros
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedFilterSucursal()
    {
        $this->resetPage();
    }

    public function updatedFilterCategoria()
    {
        $this->resetPage();
    }

    public function updatedFilterEstado()
    {
        $this->resetPage();
    }

    public function updatedFilterAlerta()
    {
        $this->resetPage();
    }

    // Calcular diferencia para ajuste
    public function updatedAjusteNuevaCantidad()
    {
        // Se puede usar para mostrar la diferencia en tiempo real
    }

    public function getDiferenciaAjusteProperty()
    {
        return $this->ajuste_nueva_cantidad - $this->ajuste_cantidad_actual;
    }

    public function getStockDisponibleTransferenciaProperty()
    {
        if (!$this->selectedStock) return 0;
        return $this->selectedStock->stock_disponible;
    }

    // Obtener valor total del inventario
    public function getValorTotalInventarioProperty()
    {
        $query = StockPorSucursal::with('producto');

        if ($this->filterSucursal !== 'TODAS') {
            $query->where('sucursal_id', $this->filterSucursal);
        }

        return $query->get()->sum(function($stock) {
            return $stock->stock_total * $stock->producto->precio_compra;
        });
    }
}