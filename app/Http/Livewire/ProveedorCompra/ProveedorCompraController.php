<?php

namespace App\Http\Livewire\ProveedorCompra;

use App\Models\Caja;
use App\Models\CajaSalidaOperaciones;
use App\Models\Product;
use App\Models\Proveedor;
use App\Models\ProveedorTransacion;
use App\Models\ProveedorTransacionDetalle;
use App\Models\StockHistories;
use App\Models\StockHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ProveedorCompraController extends Component
{
    public $clients, $selectedClientId, $selectedClient;
    public $selectedProduct,
        $quantity,
        $price,
        $cart = [];
    public $total = 0,
        $caja_id;

    public $componentName = 'Compras';
    public $selected_id;

    public $products;
    public $fechaVencimiento;


    public $lote;
    public $topProvider;

    public $startDate, $endDate;
    public $search;
    public $pagination = 10;
    public $created_at;

    public function mount()
    {
        $this->topProvider = $this->getTopProvider();
        $this->created_at = \Carbon\Carbon::now()->format('Y-m-d');

        // dd($this->topProvider);
    }
    private function getTopProvider()
    {
        // Obtener el proveedor con el monto total más alto
        $topProviderData = ProveedorTransacion::select('proveedor_id', DB::raw('SUM(monto_pagado) as total'))->groupBy('proveedor_id')->orderBy('total', 'desc')->first(); // Obtener el proveedor que tiene el mayor monto total

        // Si hay un proveedor, cargar su información
        if ($topProviderData) {
            // Cargar el proveedor completo
            $provider = Proveedor::find($topProviderData->proveedor_id);

            // Retornar un arreglo con el proveedor y el total
            return [
                'provider' => $provider,
                'total' => $topProviderData->total,
            ];
        }

        return null; // Si no hay proveedor, retornar null
    }

    public function updatedSelectedClientId()
    {
        $this->selectedClient = Proveedor::find($this->selectedClientId);
    }

    public function addProduct()
    {
        try {
            $this->validate([
                'selectedProduct' => 'required',
                'quantity' => 'required|numeric|min:1',
                'price' => 'required|numeric|min:0',
                'lote' => 'required|string',
                // 'fechaVencimiento' => 'required|date'
            ], [
                'selectedProduct.required' => 'Debe seleccionar un producto',
                'quantity.required' => 'La cantidad es requerida',
                'quantity.numeric' => 'La cantidad debe ser un número',
                'quantity.min' => 'La cantidad debe ser mayor a 0',
                'price.required' => 'El precio es requerido',
                'price.numeric' => 'El precio debe ser un número',
                'price.min' => 'El precio debe ser mayor o igual a 0',
                'lote.required' => 'El lote es requerido',
                // 'fechaVencimiento.required' => 'La fecha de vencimiento es requerida',
                // 'fechaVencimiento.date' => 'La fecha de vencimiento debe ser una fecha válida'
            ]);

            $product = Product::find($this->selectedProduct);
            if (!$product) {
                throw new \Exception('Producto no encontrado');
            }

            $this->cart[] = [
                'code' => $this->selectedProduct,
                'quantity' => $this->quantity,
                'price' => $this->price,
                'total' => $this->quantity * $this->price,
                'lote' => $this->lote,
                'fecha_vencimiento' => $this->fechaVencimiento,
            ];

            $this->total += $this->quantity * $this->price;

            // Limpiar los campos después de agregar
            $this->selectedProduct = '';
            $this->quantity = null;
            $this->price = null;
            $this->lote = '';
            $this->fechaVencimiento = null;

            // Emitir evento para resetear el select de TomSelect
            $this->emit('productAdded');

            $this->emit('show-alert', [
                'title' => 'Producto agregado correctamente',
                'type' => 'success',
                'timer' => 2000
            ]);

        } catch (\Exception $e) {
            $this->emit('show-alert', [
                'title' => $e->getMessage(),
                'type' => 'error',
                'timer' => 3000
            ]);
        }
    }
    
    public function removeProduct($index)
    {
        $this->total -= $this->cart[$index]['total'];
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function store()
    {
        $this->validate([
            'selectedClientId' => 'required',
            'cart' => 'required|array|min:1',
            'caja_id' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $transaction = ProveedorTransacion::create([
                'proveedor_id' => $this->selectedClientId,
                'fecha_compra' => now(),
                'monto_pagado' => $this->total,
                'metodo_pago' => 'Efectivo',
                'estado_pago' => 'Pendiente',
                'created_at' =>$this->created_at

            ]);

            foreach ($this->cart as $item) {
                $product = Product::where('id', $item['code'])->first();
                $stockAnterior = $product->stock;
                

                $product->lote = $item['lote'];
                $product->fecha_vencimiento = $item['fecha_vencimiento'];
                $product->monto_comprado = $item['price']; // Suponiendo que tienes un campo `precio_compra`
                $product->stock += $item['quantity']; // Actualizar el stock
                $product->save();
                // Crear el detalle de la transacción
                ProveedorTransacionDetalle::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'cantidad' => $item['quantity'],
                    'precio_compra' => $item['price'],
                    'lote' => $item['lote'],
                    'fecha_vencimiento' => $item['fecha_vencimiento'],
                    'created_at' =>$this->created_at
                ]);

                // Actualizar el stock del producto
                $product->stock += $item['quantity'];
                $product->save();

                // Registrar en el historial
                StockHistories::create([
                    'product_id' => $product->id,
                    'cantidad' => $item['quantity'],
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $product->stock,
                    'tipo_movimiento' => 'entrada',
                    'referencia' => 'compra_proveedor',
                    'referencia_id' => $transaction->id,
                    'observacion' => "Compra a proveedor ID: {$this->selectedClientId}, Lote: {$item['lote']}",
                    'created_at'=>$this->created_at,
                ]);
            }

            CajaSalidaOperaciones::create([
                'caja_id' => $this->caja_id,
                'type' => 'Transferencia',
                'monto' => $this->total,
                'description' => 'Pago a proveedor - ID Compra: ' . $transaction->id,
                'created_at' => $this->created_at,
            ]);

            DB::commit();

            $this->emit('user-added', 'Proveedor Registrada');
            $this->emit('mostrarAlertaSuccess', 'Gasto Registrado');
            $this->reset(['selectedClientId', 'selectedClient', 'cart', 'total', 'caja_id']);
        } catch (\Exception $e) {
            DB::rollback();
            $this->emit('show-alert', [
                'title' => $e->getMessage(),
                'type' => 'error',
                'timer' => false,
                'showCancelButton' => false,
                'showConfirmButton' => true
            ]);
            $this->emit('error-message', 'Error al procesar la compra');
        }
    }
    protected $listeners = [
        'anularCompra' => 'anularCompra'
    ];
    public function anularCompra($transactionId)
    {
        try {
            DB::beginTransaction();

            $transaction = ProveedorTransacion::with('details')->find($transactionId);
            
            if (!$transaction || $transaction->estado_pago === 'Anulado') {
                throw new \Exception('La transacción no existe o ya fue anulada');
            }

            // Revertir el stock de cada producto
            foreach ($transaction->details as $detail) {
                $product = Product::find($detail->product_id);
                $stockAnterior = $product->stock;
                
                // Verificar que haya suficiente stock para anular
                if ($product->stock < $detail->cantidad) {
                    throw new \Exception("No hay suficiente stock para anular el producto: {$product->nombre_producto}");
                }

                // Actualizar el stock del producto
                $product->stock -= $detail->cantidad;
                $product->save();

                // Registrar en el historial
                StockHistories::create([
                    'product_id' => $product->id,
                    'cantidad' => $detail->cantidad,
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $product->stock,
                    'tipo_movimiento' => 'salida',
                    'referencia' => 'anulacion_compra',
                    'referencia_id' => $transaction->id,
                    'observacion' => "Anulación de compra ID: {$transaction->id}",
                ]);
            }

            // Anular los movimientos de caja
            $this->anularMovimientosCajaCompra($transaction);

            // Actualizar estado de la transacción
            $transaction->update(['estado_pago' => 'Anulado']);

            DB::commit();
            $this->emit('mostrarAlertaSuccess', 'Compra anulada correctamente');
        } catch (\Exception $e) {
            DB::rollback();
            
            $this->emit('show-alert', [
                'title' => $e->getMessage(),
                'type' => 'error',
                'timer' => false,
                'showCancelButton' => false,
                'showConfirmButton' => true
            ]);
        }
    }

    private function anularMovimientosCajaCompra(ProveedorTransacion $transaction)
    {
        // Anular la salida de dinero
        CajaSalidaOperaciones::where('description', 'like', '%ID Compra: ' . $transaction->id . '%')
            ->update(['estado' => 0]);

        // Anular cualquier devolución relacionada
        CajaSalidaOperaciones::where('description', 'like', '%Devolución por anulación de compra - ID: ' . $transaction->id . '%')
            ->update(['estado' => 0]);
    }

    public function resetUI()
    {
        $this->reset(['topProvider']);
        $this->componentName = 'Compras';
    }
    public $transactions;
    public function render()
    {
        $this->clients = Proveedor::where('estado',1)->get();
        $this->products = Product::where('state',1)->get(); 

        $user = Auth::user(); // O auth()->user();

        $atm = Caja::where('user_id', $user->id)
            ->where('state', 0)
            ->get(); // Asumiendo que tienes un campo user_id en la tabla Caja
        $this->transactions = ProveedorTransacion::with(['details', 'provider'])
            ->orderBy('id', 'desc')
            ->get();
        return view('livewire.proveedor-compra.proveedor-compra', ['atm' => $atm, 'transactions' => $this->transactions, 'clients' => $this->clients, 'products' => $this->products])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function verHistorialStock($productId)
    {
        $historial = StockHistories::where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('livewire.stock-history', [
            'historial' => $historial
        ]);
    }

    public function generatePurchaseReport()
    {
        try {
            // Construir la consulta base con relaciones
            $query = ProveedorTransacion::with(['details.product', 'provider'])
                ->where('estado_pago', 'Pendiente'); // Filtrar por estado de pago Pendiente
    
            // Agregar rango de fechas si están definidos
            if ($this->startDate && $this->endDate) {
                $query->whereBetween('fecha_compra', [$this->startDate, $this->endDate]);
            }
    
            // Obtener las transacciones
            $transactions = $query->get();
    
            // Verificar si hay transacciones
            if ($transactions->isEmpty()) {
                $this->emit('error-message', 'No hay datos para generar el reporte');
                return;
            }
    
            // Preparar los datos para el reporte
            $data = [
                'transactions' => $transactions,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'totalCompras' => $transactions->sum('monto_pagado'),
            ];
    
            // Generar el PDF
            $pdf = PDF::loadView('pdf.purchase_report', $data);
            $pdf->setPaper('a4', 'portrait');
    
            // Retornar el PDF como descarga
            return response()->streamDownload(
                function () use ($pdf) {
                    echo $pdf->output();
                },
                'reporte_compras_' . now()->format('Y-m-d_H-i-s') . '.pdf'
            );
    
        } catch (\Exception $e) {
            // Registrar el error y emitir mensaje de error
            \Log::error('Error generando reporte: ' . $e->getMessage());
            $this->emit('error-message', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }
    
    public function updatedStartDate($value)
    {
        if($value && $this->endDate && $value > $this->endDate) {
            $this->endDate = $value;
        }
    }

    public function updatedEndDate($value)
    {
        if($value && $this->startDate && $value < $this->startDate) {
            $this->startDate = $value;
        }
    }
}
