<?php

namespace App\Http\Livewire\Sucursal;

use App\Models\DetalleTransferencia;
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

    public $searchTerm = '';
    public $filterSucursal = '';
    public $filterEstado = '';

    public $sucursales = [];
    public $productos = [];
    public $transferencias = [];
    public $estados = ['SIN_STOCK', 'STOCK_BAJO', 'STOCK_OK'];

    public $sucursal_origen_id, $sucursal_destino_id, $motivo, $observaciones;
    public $producto_id, $cantidad;

    public $estadisticas = [
        'total_items' => 0,
        'sin_stock' => 0,
        'stock_bajo' => 0,
        'stock_ok' => 0,
        'valor_total' => 0,
    ];
    public $productos_seleccionados = [];

    protected $updatesQueryString = ['searchTerm', 'filterSucursal', 'filterEstado', 'page'];

    public function mount()
    {
        $this->productos_seleccionados = [];

        $this->sucursales = Sucursal::pluck('nombre', 'id')->toArray();
        $this->productos = Producto::pluck('nombre', 'id')->toArray();
        $this->cargarTransferencias(); // ejemplo
    }

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

    public function agregarProducto()
    {
        $this->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $productoYaAgregado = collect($this->productos_seleccionados)->firstWhere('producto_id', $this->producto_id);

        if ($productoYaAgregado) {
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

            foreach ($this->productos_seleccionados as $detalle) {
                DetalleTransferencia::create([
                    'transferencia_id' => $transferencia->id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad_solicitada' => $detalle['cantidad'],
                ]);
            }

            DB::commit();

            $this->reset(['sucursal_origen_id', 'sucursal_destino_id', 'motivo', 'observaciones', 'productos_seleccionados']);

            $this->dispatchBrowserEvent('cerrar-modal-transferencia');
            session()->flash('success', 'Transferencia registrada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            session()->flash('error', 'Error al registrar transferencia: ' . $e->getMessage());
        }
    }
    public function cargarTransferencias()
    {
        $this->transferencias = TransferenciaSucursal::with(['sucursalOrigen:id,nombre', 'sucursalDestino:id,nombre', 'usuarioSolicita:id,username', 'detalleTransferencias.producto:id,nombre'])
            ->orderByDesc('created_at')
            ->get();
        //  dd($this->transferencias);
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

        return '<span class="badge bg-' . $color . '">' . ucfirst(strtolower($estado)) . '</span>';
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
            $query->whereHas('producto', function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")->orWhere('codigo', 'like', "%$search%");
            });
        }

        $filteredStock = $query->orderBy('id', 'desc')->paginate(10);
        $this->calcularEstadisticas($query->get());

        return view('livewire.sucursal.sucursal-transferencia', [
            'filteredStock' => $filteredStock,
            'sucursales' => $this->sucursales,
            'estados' => $this->estados,
            'estadisticas' => $this->estadisticas,
            'transferencias' => $this->transferencias,
        ])
            ->extends('layouts.theme.app')
            ->section('content');
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
            $estado = $stock->estado_stock;
            $this->estadisticas['valor_total'] += $stock->valor_stock;

            match ($estado) {
                'SIN_STOCK' => $this->estadisticas['sin_stock']++,
                'STOCK_BAJO' => $this->estadisticas['stock_bajo']++,
                'STOCK_OK' => $this->estadisticas['stock_ok']++,
            };
        }
    }

    public function editarStock($id)
    {
        $this->emit('editarStock', $id);
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
    public $transferenciaSeleccionada;
    public $verDetalleModal = false;

    public function verDetalles($id)
    {
        $this->transferenciaSeleccionada = TransferenciaSucursal::with(['sucursalOrigen:id,nombre', 'sucursalDestino:id,nombre', 'usuarioSolicita:id,username', 'detalleTransferencias.producto:id,nombre'])->findOrFail($id);

        $this->verDetalleModal = true;
    }
}
