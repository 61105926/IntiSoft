<?php

namespace App\Http\Livewire\Venta;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\Caja;
use App\Models\StockPorSucursal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Component
{
    use WithPagination;

    // Propiedades para filtros y búsqueda
    public $busqueda = '';
    public $filtroEstado = '';
    public $filtroEstadoPago = '';
    public $fechaDesde = '';
    public $fechaHasta = '';
    public $clienteSeleccionado = '';

    // Propiedades para modales
    public $mostrarModalVenta = false;
    public $mostrarModalDetalle = false;
    public $mostrarModalPago = false;
    public $mostrarModalComprobante = false;

    // Propiedades para venta
    public $ventaSeleccionada = null;
    public $numero_venta = '';
    public $cliente_id = '';
    public $sucursal_id = '';
    public $fecha_venta = '';
    public $fecha_entrega = '';
    public $estado = 'PENDIENTE';
    public $estado_pago = 'PENDIENTE';
    public $metodo_pago = 'EFECTIVO';
    public $observaciones = '';
    public $documento_referencia = '';
    public $descuento = 0;
    public $impuestos = 0;

    // Propiedades para productos en la venta
    public $productosEnVenta = [];
    public $productoSeleccionado = '';
    public $cantidadProducto = 1;
    public $precioProducto = 0;
    public $descuentoProducto = 0;

    // Propiedades para pago
    public $montoPago = 0;
    public $cajaParaPago = '';
    public $metodoPagoVenta = 'EFECTIVO';
    public $pago_inicial = 0;
    public $caja_id = '';

    // Colecciones
    public $clientes;
    public $productos;
    public $sucursales;
    public $cajas;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->fecha_venta = Carbon::now()->format('Y-m-d');
        $this->fechaDesde = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->fechaHasta = Carbon::now()->format('Y-m-d');
        $this->sucursal_id = auth()->user()->sucursal_id ?? '';
        
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $this->clientes = Cliente::orderBy('nombres')->get();
        $this->productos = Producto::with('stockPorSucursal')->orderBy('nombre')->get();
        $this->sucursales = Sucursal::orderBy('nombre')->get();
        $this->cajas = Caja::where('estado', 'ABIERTA')->orderBy('nombre')->get();
    }

    public function updatedBusqueda()
    {
        $this->resetPage();
    }

    public function updatedFiltroEstado()
    {
        $this->resetPage();
    }

    public function updatedFiltroEstadoPago()
    {
        $this->resetPage();
    }

    // Modal de nueva venta
    public function abrirModalVenta($ventaId = null)
    {
        $this->resetearFormulario();
        
        if ($ventaId) {
            $this->ventaSeleccionada = Venta::with('detalles.producto')->find($ventaId);
            if ($this->ventaSeleccionada) {
                $this->cargarDatosVenta();
            }
        }
        
        $this->mostrarModalVenta = true;
    }

    public function cerrarModalVenta()
    {
        $this->mostrarModalVenta = false;
        $this->resetearFormulario();
    }

    public function resetearFormulario()
    {
        $this->ventaSeleccionada = null;
        $this->numero_venta = '';
        $this->cliente_id = '';
        $this->fecha_venta = Carbon::now()->format('Y-m-d');
        $this->fecha_entrega = '';
        $this->estado = 'PENDIENTE';
        $this->estado_pago = 'PENDIENTE';
        $this->metodo_pago = 'EFECTIVO';
        $this->observaciones = '';
        $this->documento_referencia = '';
        $this->descuento = 0;
        $this->impuestos = 0;
        $this->productosEnVenta = [];
        $this->productoSeleccionado = '';
        $this->cantidadProducto = 1;
        $this->precioProducto = 0;
        $this->descuentoProducto = 0;
        $this->pago_inicial = 0;
        $this->caja_id = '';
    }

    public function cargarDatosVenta()
    {
        if (!$this->ventaSeleccionada) return;

        $venta = $this->ventaSeleccionada;
        $this->numero_venta = $venta->numero_venta;
        $this->cliente_id = $venta->cliente_id;
        $this->sucursal_id = $venta->sucursal_id;
        $this->fecha_venta = $venta->fecha_venta->format('Y-m-d\TH:i');
        $this->fecha_entrega = $venta->fecha_entrega ? $venta->fecha_entrega->format('Y-m-d\TH:i') : '';
        $this->estado = $venta->estado;
        $this->estado_pago = $venta->estado_pago;
        $this->metodo_pago = $venta->metodo_pago;
        $this->observaciones = $venta->observaciones;
        $this->documento_referencia = $venta->documento_referencia;
        $this->descuento = $venta->descuento;
        $this->impuestos = $venta->impuestos;

        // Cargar productos de la venta
        $this->productosEnVenta = [];
        foreach ($venta->detalles as $detalle) {
            $this->productosEnVenta[] = [
                'id' => $detalle->id,
                'producto_id' => $detalle->producto_id,
                'nombre' => $detalle->nombre_producto,
                'codigo' => $detalle->codigo_producto,
                'cantidad' => $detalle->cantidad,
                'precio_unitario' => $detalle->precio_unitario,
                'descuento_unitario' => $detalle->descuento_unitario,
                'subtotal' => $detalle->subtotal,
                'estado' => $detalle->estado
            ];
        }
    }

    // Manejo de productos
    public function updatedProductoSeleccionado()
    {
        if ($this->productoSeleccionado) {
            $producto = Producto::find($this->productoSeleccionado);
            if ($producto) {
                $this->precioProducto = $producto->precio_venta;
            }
        }
    }

    public function agregarProducto()
    {
        $this->validate([
            'productoSeleccionado' => 'required|exists:productos,id',
            'cantidadProducto' => 'required|numeric|min:1',
            'precioProducto' => 'required|numeric|min:0'
        ]);

        $producto = Producto::find($this->productoSeleccionado);
        
        // Verificar stock disponible
        $stock = StockPorSucursal::where('producto_id', $this->productoSeleccionado)
            ->where('sucursal_id', $this->sucursal_id)
            ->first();
            
        if (!$stock || $stock->stock_actual < $this->cantidadProducto) {
            session()->flash('error', 'Stock insuficiente para el producto seleccionado');
            return;
        }

        // Verificar si el producto ya está en la lista
        $productoExistente = false;
        foreach ($this->productosEnVenta as $key => $item) {
            if ($item['producto_id'] == $this->productoSeleccionado) {
                $this->productosEnVenta[$key]['cantidad'] += $this->cantidadProducto;
                $this->productosEnVenta[$key]['subtotal'] = 
                    ($this->productosEnVenta[$key]['precio_unitario'] - $this->productosEnVenta[$key]['descuento_unitario']) 
                    * $this->productosEnVenta[$key]['cantidad'];
                $productoExistente = true;
                break;
            }
        }

        if (!$productoExistente) {
            $subtotal = ($this->precioProducto - $this->descuentoProducto) * $this->cantidadProducto;
            
            $this->productosEnVenta[] = [
                'producto_id' => $this->productoSeleccionado,
                'nombre' => $producto->nombre,
                'codigo' => $producto->codigo,
                'cantidad' => $this->cantidadProducto,
                'precio_unitario' => $this->precioProducto,
                'descuento_unitario' => $this->descuentoProducto,
                'subtotal' => $subtotal,
                'estado' => 'ACTIVO'
            ];
        }

        $this->productoSeleccionado = '';
        $this->cantidadProducto = 1;
        $this->precioProducto = 0;
        $this->descuentoProducto = 0;
    }

    public function eliminarProducto($index)
    {
        unset($this->productosEnVenta[$index]);
        $this->productosEnVenta = array_values($this->productosEnVenta);
    }

    public function calcularTotalVenta()
    {
        $subtotal = collect($this->productosEnVenta)->sum('subtotal');
        return $subtotal - $this->descuento + $this->impuestos;
    }

    // Guardar venta
    public function guardarVenta()
    {
        $rules = [
            'cliente_id' => 'required|exists:clientes,id',
            'sucursal_id' => 'required|exists:sucursals,id',
            'fecha_venta' => 'required|date',
            'metodo_pago' => 'required|string',
            'productosEnVenta' => 'required|array|min:1',
            'pago_inicial' => 'nullable|numeric|min:0'
        ];

        // Si hay pago inicial, la caja es obligatoria
        if ($this->pago_inicial > 0) {
            $rules['caja_id'] = 'required|exists:cajas,id';
        }

        $this->validate($rules);

        DB::beginTransaction();
        try {
            if ($this->ventaSeleccionada) {
                // Actualizar venta existente
                $venta = $this->ventaSeleccionada;
                $venta->update([
                    'cliente_id' => $this->cliente_id,
                    'sucursal_id' => $this->sucursal_id,
                    'fecha_venta' => $this->fecha_venta,
                    'fecha_entrega' => $this->fecha_entrega ?: null,
                    'estado' => $this->estado,
                    'metodo_pago' => $this->metodo_pago,
                    'observaciones' => $this->observaciones,
                    'documento_referencia' => $this->documento_referencia,
                    'descuento' => $this->descuento,
                    'impuestos' => $this->impuestos
                ]);
                
                // Eliminar detalles existentes y crear nuevos
                $venta->detalles()->delete();
            } else {
                // Crear nueva venta
                $venta = new Venta([
                    'cliente_id' => $this->cliente_id,
                    'sucursal_id' => $this->sucursal_id,
                    'usuario_id' => Auth::id(),
                    'fecha_venta' => $this->fecha_venta,
                    'fecha_entrega' => $this->fecha_entrega ?: null,
                    'estado' => $this->estado,
                    'estado_pago' => 'PENDIENTE',
                    'metodo_pago' => $this->metodo_pago,
                    'observaciones' => $this->observaciones,
                    'documento_referencia' => $this->documento_referencia,
                    'descuento' => $this->descuento,
                    'impuestos' => $this->impuestos,
                    'subtotal' => 0,
                    'total' => 0,
                    'monto_pagado' => 0,
                    'saldo_pendiente' => 0
                ]);
                
                $venta->numero_venta = $venta->generarNumeroVenta();
                $venta->save();
            }

            // Crear detalles
            foreach ($this->productosEnVenta as $item) {
                $producto = Producto::find($item['producto_id']);
                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'descuento_unitario' => $item['descuento_unitario'],
                    'subtotal' => $item['subtotal'],
                    'nombre_producto' => $producto->nombre,
                    'codigo_producto' => $producto->codigo,
                    'estado' => 'ACTIVO'
                ]);
            }

            // Recalcular totales
            $venta->calcularTotales();
            $venta->save();

            // Procesar pago inicial si hay uno
            if ($this->pago_inicial > 0 && $this->caja_id) {
                $venta->procesarPago($this->pago_inicial, $this->caja_id);
            }

            DB::commit();
            
            session()->flash('message', $this->ventaSeleccionada ? 'Venta actualizada correctamente' : 'Venta creada correctamente');
            $this->cerrarModalVenta();
            
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al guardar la venta: ' . $e->getMessage());
        }
    }

    // Modal de pago
    public function abrirModalPago($ventaId)
    {
        $this->ventaSeleccionada = Venta::find($ventaId);
        $this->montoPago = $this->ventaSeleccionada->saldo_pendiente;
        $this->metodoPagoVenta = $this->ventaSeleccionada->metodo_pago;
        $this->cajaParaPago = '';
        $this->mostrarModalPago = true;
    }

    public function cerrarModalPago()
    {
        $this->mostrarModalPago = false;
        $this->montoPago = 0;
        $this->cajaParaPago = '';
        $this->metodoPagoVenta = 'EFECTIVO';
    }

    public function procesarPago()
    {
        $this->validate([
            'montoPago' => 'required|numeric|min:0.01',
            'cajaParaPago' => 'required|exists:cajas,id',
            'metodoPagoVenta' => 'required|string'
        ]);

        try {
            $this->ventaSeleccionada->metodo_pago = $this->metodoPagoVenta;
            $this->ventaSeleccionada->procesarPago($this->montoPago, $this->cajaParaPago);
            
            session()->flash('message', 'Pago procesado correctamente');
            $this->cerrarModalPago();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    // Acciones de venta
    public function completarVenta($ventaId)
    {
        $venta = Venta::find($ventaId);
        if ($venta && $venta->estado === 'PENDIENTE') {
            try {
                $venta->completar();
                session()->flash('message', 'Venta completada correctamente');
            } catch (\Exception $e) {
                session()->flash('error', 'Error al completar la venta: ' . $e->getMessage());
            }
        }
    }

    public function cancelarVenta($ventaId)
    {
        $venta = Venta::find($ventaId);
        if ($venta && $venta->estado !== 'COMPLETADA') {
            $venta->estado = 'CANCELADA';
            $venta->save();
            session()->flash('message', 'Venta cancelada correctamente');
        }
    }

    public function eliminarVenta($ventaId)
    {
        $venta = Venta::find($ventaId);
        if ($venta && $venta->estado === 'PENDIENTE') {
            $venta->delete();
            session()->flash('message', 'Venta eliminada correctamente');
        }
    }

    // Modal detalle
    public function verDetalle($ventaId)
    {
        $this->ventaSeleccionada = Venta::with(['detalles.producto', 'cliente', 'sucursal', 'usuario', 'caja'])
            ->find($ventaId);
        $this->mostrarModalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->mostrarModalDetalle = false;
        $this->ventaSeleccionada = null;
    }

    // Modal comprobante
    public function verComprobante($ventaId)
    {
        $this->ventaSeleccionada = Venta::with(['detalles.producto', 'cliente', 'sucursal', 'usuario'])
            ->find($ventaId);
        $this->mostrarModalComprobante = true;
    }

    public function cerrarModalComprobante()
    {
        $this->mostrarModalComprobante = false;
        $this->ventaSeleccionada = null;
    }

    public function getVentasFiltradas()
    {
        $query = Venta::with(['cliente', 'sucursal', 'usuario'])
            ->orderBy('fecha_venta', 'desc');

        // Aplicar filtros
        if ($this->busqueda) {
            $query->where(function($q) {
                $q->where('numero_venta', 'like', '%' . $this->busqueda . '%')
                  ->orWhereHas('cliente', function($subq) {
                      $subq->where('nombre', 'like', '%' . $this->busqueda . '%');
                  });
            });
        }

        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }

        if ($this->filtroEstadoPago) {
            $query->where('estado_pago', $this->filtroEstadoPago);
        }

        if ($this->fechaDesde) {
            $query->whereDate('fecha_venta', '>=', $this->fechaDesde);
        }

        if ($this->fechaHasta) {
            $query->whereDate('fecha_venta', '<=', $this->fechaHasta);
        }

        if ($this->clienteSeleccionado) {
            $query->where('cliente_id', $this->clienteSeleccionado);
        }

        return $query->paginate(15);
    }

    public function render()
    {
        $ventas = $this->getVentasFiltradas();
        $resumenHoy = Venta::resumenVentasHoy($this->sucursal_id);
        
        return view('livewire.venta.venta', compact('ventas', 'resumenHoy'));
    }
}
