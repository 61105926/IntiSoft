<?php

namespace App\Http\Livewire\HistorialProducto;

use App\Models\Producto;
use App\Models\MovimientoStockSucursal;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class HistorialProductoController extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    // Filtros
    public $searchTerm = '';
    public $filterProducto = '';
    public $filterTipoMovimiento = 'TODOS';
    public $filterSucursal = 'TODAS';
    public $filterUsuario = 'TODOS';
    public $filterFechaDesde = '';
    public $filterFechaHasta = '';
    public $perPage = 10;

    // Modal de detalles
    public $showDetailsModal = false;
    public $selectedMovimiento = null;

    public function mount()
    {
        $this->filterFechaDesde = Carbon::now()->subMonth()->format('Y-m-d');
        $this->filterFechaHasta = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $movimientos = $this->getFilteredMovimientos();
        $productos = Producto::activos()->orderBy('nombre')->get();
        $sucursales = Sucursal::orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();
        $estadisticas = $this->getEstadisticas();

        return view('livewire.historial-producto.historial-producto', [
            'movimientos' => $movimientos,
            'productos' => $productos,
            'sucursales' => $sucursales,
            'usuarios' => $usuarios,
            'estadisticas' => $estadisticas,
        ])->extends('layouts.theme.modern-app')->section('content');
    }

    private function getFilteredMovimientos()
    {
        $query = MovimientoStockSucursal::with(['producto.categoria', 'sucursal', 'usuario'])
            ->orderBy('fecha_movimiento', 'desc');

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('referencia', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('observaciones', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereHas('producto', function ($productoQuery) {
                      $productoQuery->where('nombre', 'like', '%' . $this->searchTerm . '%')
                                   ->orWhere('codigo', 'like', '%' . $this->searchTerm . '%');
                  });
            });
        }

        if ($this->filterProducto) {
            $query->where('producto_id', $this->filterProducto);
        }

        if ($this->filterTipoMovimiento !== 'TODOS') {
            $query->where('tipo_movimiento', $this->filterTipoMovimiento);
        }

        if ($this->filterSucursal !== 'TODAS') {
            $query->where('sucursal_id', $this->filterSucursal);
        }

        if ($this->filterUsuario !== 'TODOS') {
            $query->where('user_id', $this->filterUsuario);
        }

        if ($this->filterFechaDesde) {
            $query->where('fecha_movimiento', '>=', $this->filterFechaDesde);
        }

        if ($this->filterFechaHasta) {
            $query->where('fecha_movimiento', '<=', $this->filterFechaHasta . ' 23:59:59');
        }

        return $query->paginate($this->perPage);
    }

    private function getEstadisticas()
    {
        $baseQuery = MovimientoStockSucursal::query();

        if ($this->filterFechaDesde) {
            $baseQuery->where('fecha_movimiento', '>=', $this->filterFechaDesde);
        }

        if ($this->filterFechaHasta) {
            $baseQuery->where('fecha_movimiento', '<=', $this->filterFechaHasta . ' 23:59:59');
        }

        $total = (clone $baseQuery)->count();
        $hoy = (clone $baseQuery)->whereDate('fecha_movimiento', Carbon::today())->count();

        $alquileres = (clone $baseQuery)->where('tipo_movimiento', 'ALQUILER')->count();
        $ventas = (clone $baseQuery)->where('tipo_movimiento', 'VENTA')->count();
        $devoluciones = (clone $baseQuery)->where('tipo_movimiento', 'DEVOLUCION')->count();
        $entradas = (clone $baseQuery)->where('tipo_movimiento', 'ENTRADA')->count();

        $valorTotal = (clone $baseQuery)->sum('valor_unitario');
        $productosActivos = Producto::activos()->count();

        return [
            'total' => $total,
            'hoy' => $hoy,
            'alquileres' => $alquileres,
            'alquileresPercent' => $total > 0 ? round(($alquileres / $total) * 100, 1) : 0,
            'ventas' => $ventas,
            'ventasPercent' => $total > 0 ? round(($ventas / $total) * 100, 1) : 0,
            'devoluciones' => $devoluciones,
            'devolucionesPercent' => $total > 0 ? round(($devoluciones / $total) * 100, 1) : 0,
            'entradas' => $entradas,
            'entradasPercent' => $total > 0 ? round(($entradas / $total) * 100, 1) : 0,
            'valorTotal' => $valorTotal,
            'productosActivos' => $productosActivos,
        ];
    }

    // Filtros
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedFilterProducto()
    {
        $this->resetPage();
    }

    public function updatedFilterTipoMovimiento()
    {
        $this->resetPage();
    }

    public function updatedFilterSucursal()
    {
        $this->resetPage();
    }

    public function updatedFilterUsuario()
    {
        $this->resetPage();
    }

    public function updatedFilterFechaDesde()
    {
        $this->resetPage();
    }

    public function updatedFilterFechaHasta()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // Limpiar filtros
    public function limpiarFiltros()
    {
        $this->searchTerm = '';
        $this->filterProducto = '';
        $this->filterTipoMovimiento = 'TODOS';
        $this->filterSucursal = 'TODAS';
        $this->filterUsuario = 'TODOS';
        $this->filterFechaDesde = Carbon::now()->subMonth()->format('Y-m-d');
        $this->filterFechaHasta = Carbon::now()->format('Y-m-d');
        $this->perPage = 10;
        $this->resetPage();
    }

    // Ver detalles del movimiento
    public function verDetalles($movimientoId)
    {
        $this->selectedMovimiento = MovimientoStockSucursal::with([
            'producto.categoria',
            'sucursal',
            'usuario'
        ])->find($movimientoId);

        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedMovimiento = null;
    }

    // Exportar datos
    public function exportar()
    {
        $this->dispatchBrowserEvent('swal', [
            'title' => 'Función de Exportar',
            'text' => 'Esta función estará disponible próximamente.',
            'icon' => 'info'
        ]);
    }
}
