<?php

namespace App\Http\Livewire\Venta;

use App\Models\Caja;
use App\Models\CajaEntrada;
use App\Models\CajaOperaciones;
use App\Models\CajaSalida;
use App\Models\Client;
use App\Models\Company;
use App\Models\DetalleVenta;
use App\Models\Product;
use App\Models\StockHistories;
use App\Models\Ventas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
class VentaController extends Component
{
    use WithPagination;

    // public $componentName = 'Venta',
    //     $pageTitle = 'Listado',
    //     $selected_id,
    //     $search,
    //     $client_id;
    // public $cart = [],
    //     $selected_product_id,
    //     $quantity = 1,
    //     $price,
    //     $subtotal = 0,
    //     $price_total_product,
    //     $caja_id="",
    //     $total = 0;
    // public $payment_method = 'Efectivo',
    //     $discount = 0;
    // public $localStock = [];

    // public $pagination = 10;
    // public $startDate, $endDate;
    // public $type_product;

    // public $created_at;
    // public function updatedPagination($value)
    // {
    //     $this->pagination = $value;
    // }

    // public function resetUI()
    // {
    //     $this->reset();
    //     $this->componentName = 'Venta';
    // }
    // public function mount()
    // {
    //     $this->localStock = Product::pluck('stock', 'id')->toArray();
    //     $this->created_at = \Carbon\Carbon::now()->format('Y-m-d');

    // }

    // public function paginationView()
    // {
    //     return 'vendor.livewire.bootstrap';
    // }
    // // Variables para gestionar el carrito
    // // Actualiza el precio total cuando se cambia el producto o la cantidad
    // public function updatedSelectedProductId()
    // {
    //     $this->loadProductDetails();
    //     $this->calculatePriceTotal();
    // }

    // public function updatedQuantity()
    // {
    //     $this->calculatePriceTotal();
    // }

    // private function loadProductDetails()
    // {
    //     $product = Product::find($this->selected_product_id);
    //     if ($product) {
    //         $this->price = $product->precio;
    //         $this->type_product = $product->categoria;

    //         $this->localStock[$product->id] = $this->localStock[$product->id] ?? $product->stock;
    //         $this->price = $product->precio;
    //     }
    // }

    // public function calculatePriceTotal()
    // {
    //     // Asegúrate de que price y quantity sean numéricos, convierte si es necesario
    //     $price = is_numeric($this->price) ? (float) $this->price : 0;
    //     $quantity = is_numeric($this->quantity) ? (int) $this->quantity : 0;

    //     // Realiza la multiplicación
    //     $this->price_total_product = $price * $quantity;
    // }

    // // Añadir producto al carrito
    // public function addProduct()
    // {
    //     $product = Product::find($this->selected_product_id);

    //     if ($product && $this->isStockAvailable($product)) {
    //         $this->cart[] = [
    //             'unique_id' => uniqid(),
    //             'id' => $product->id,
    //             'code' => $product->codigo_producto,
    //             'description' => $product->nombre_producto,
    //             'quantity' => $this->quantity,
    //             'price' => $this->price,
    //             'total' => $this->price_total_product,
    //         ];

    //         $this->localStock[$product->id] -= $this->quantity;
    //         $this->resetProductSelection();
    //         $this->calculateTotals();
    //         $this->calculatePriceTotal();
    //     }
    // }

    // private function isStockAvailable($product)
    // {
    //     if ($this->localStock[$product->id] < $this->quantity) {
    //         $this->emit('mostrarAlertaFaild', 'No hay suficiente stock disponible.');

    //         return false;
    //     }
    //     return true;
    // }

    // private function resetProductSelection()
    // {
    //     $this->quantity = 1;
    //     $this->price_total_product = null;
    // }

    // // Remover producto del carrito
    // public function removeProduct($uniqueId)
    // {
    //     // Encuentra el producto que se está eliminando
    //     $productToRemove = collect($this->cart)->firstWhere('unique_id', $uniqueId);
    //     // dd($productToRemove);
    //     // Si existe el producto, incrementa el stock disponible
    //     if ($productToRemove) {
    //         $productId = $productToRemove['id']; // Asumiendo que tienes 'product_id' en el carrito
    //         $quantity = $productToRemove['quantity']; // Asumiendo que tienes 'quantity' en el carrito

    //         // Recupera el stock del producto en localStock y lo incrementa
    //         if (isset($this->localStock[$productId])) {
    //             $this->localStock[$productId] += $quantity;
    //         }
    //     }

    //     // Filtra el carrito para eliminar el producto
    //     $this->cart = array_filter($this->cart, function ($item) use ($uniqueId) {
    //         return $item['unique_id'] !== $uniqueId;
    //     });

    //     $this->cart = array_values($this->cart); // Reindexar el array

    //     // Recalcular los totales después de eliminar el producto
    //     $this->calculateTotals();
    // }

    // // Calcular subtotales y totales
    // private function calculateTotals()
    // {
    //     $this->subtotal = collect($this->cart)->sum('total');
    //     $this->total = floatval($this->subtotal) - floatval($this->discount);
    // }

    // public function updatedDiscount()
    // {
    //     $this->calculateTotals();
    // }

    // // Guardar la venta y sus detalles
    // public $efectivo;
    // public $debito;
    // public $transferencia;
    // // Dentro de la clase VentaController
    // public $total_paid = 0; // Total pagado por el cliente
    // public $change = 0; // Cambio devuelto

    // public function store()
    // {
    //     if ($this->isCartValid() && $this->isClientSelected() && $this->isCajaSelected()) {
    //         //  dd($this->cash_amount);
    //         $totalPagado = $this->cash_amount + $this->deposit_amount + $this->transfer_amount;

    //         if ($totalPagado < $this->total) {
    //             $this->emit('mostrarAlertaFaild', 'El monto total no cubre el total a pagar.');
    //             return;
    //         }

    //         DB::beginTransaction();
    //         try {
    //             $venta = $this->createSale();
    //             // dd($venta);
    //             $this->createSaleDetails($venta->id);

    //             DB::commit();
    //             $this->resetCart();
    //             $this->emit('person-added', 'Venta Registrada');
    //             $this->emit('mostrarAlertaSuccess', 'Venta Registrada');
    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             //dd($e);
    //             $this->emit('mostrarAlertaFaild', $e);
    //         }
    //     }
    // }

    // private function isCartValid()
    // {
    //     if (empty($this->cart)) {
    //         $this->emit('mostrarAlertaFaild', 'Debe agregar al menos un producto al carrito');

    //         return false;
    //     }
    //     return true;
    // }
    // private function isCajaSelected()
    // {
    //     if (!$this->caja_id) {
    //         $this->emit('mostrarAlertaFaild', 'Debe seleccionar una Caja.');

    //         return false;
    //     }
    //     return true;
    // }
    // private function isClientSelected()
    // {
    //     if (!$this->client_id) {
    //         $this->emit('mostrarAlertaFaild', 'Debe seleccionar un cliente.');

    //         return false;
    //     }
    //     return true;
    // }
    // // Dentro de la clase VentaController
    // public $cash_amount = 0;
    // public $deposit_amount = 0;
    // public $transfer_amount = 0;

    // private function createSale()
    // {
    //     // Supongamos que el cliente paga en efectivo y el monto ya fue capturado en $this->cash_amount
    //     $this->total_paid = (float) $this->cash_amount + (float) $this->deposit_amount + (float) $this->transfer_amount;

    //     // Calcula el cambio devuelto
    //     if ($this->total_paid > $this->total) {
    //         $this->change = $this->total_paid - $this->total;
    //     } else {
    //         $this->change = 0; // No hay cambio si no pagó de más
    //     }
    //     $venta = Ventas::create([
    //         'client_id' => $this->client_id,
    //         'user_id' => Auth::id(),
    //         'subtotal' => $this->subtotal,
    //         'descuento' => $this->discount,
    //         'total' => $this->total,
    //         'cash_amount' => $this->cash_amount,
    //         'deposit_amount' => $this->deposit_amount,
    //         'transfer_amount' => $this->transfer_amount,
    //         'total_paid' => $this->total_paid,
    //         'change' => $this->change,
    //         'created_at' =>$this->created_at

    //     ]);

    //     // Registrar movimientos en las cajas
    //     $this->registerCajaEntries($venta);

    //     return $venta;
    // }

    // private function registerCajaEntries($venta)
    // {
    //     // Validar que las cantidades no sean nulas
    //     $this->cash_amount = $this->cash_amount ?? 0;
    //     $this->deposit_amount = $this->deposit_amount ?? 0;
    //     $this->transfer_amount = $this->transfer_amount ?? 0;

    //     $totalPago = $this->cash_amount + $this->deposit_amount + $this->transfer_amount;
    //     $cambio = $totalPago - $venta->total; // Calcula el cambio

    // // Registrar el cambio como salida si es mayor a 0
    // if ($cambio > 0) {
    //     $clienteNombre = $venta->cliente->nombre_completo ?? 'Cliente desconocido'; // Obtén el nombre del cliente

    //     $cajaCambio = new CajaSalida();
    //     $cajaCambio->caja_id = $this->caja_id; // ID de la caja asociada
    //     $cajaCambio->type = 'Cambio';
    //     $cajaCambio->monto = $cambio;
    //     $cajaCambio->description = 'Cambio a cliente: ' . $clienteNombre . ' - Nº Venta: ' . $venta->id;
    //     $cajaCambio->created_at = now();
    //     $cajaCambio->save();
    // }
    //     // Manejar el efectivo (Caja de Efectivo)
    //     if ($this->cash_amount > 0) {
    //         $cajaEfectivo = new CajaEntrada();
    //         $cajaEfectivo->caja_id = $this->caja_id; // Asegúrate de que este ID corresponde a la caja de efectivo
    //         $cajaEfectivo->type = 'Efectivo';
    //         $cajaEfectivo->monto = $this->cash_amount;
    //         $cajaEfectivo->description = 'Nº Venta: ' . $venta->id;
    //         $cajaEfectivo->created_at =  $this->created_at;
            
    //         $cajaEfectivo->save();

    //         // Actualizar saldo de la caja de efectivo
    //         // $this->updateCajaSaldo($cajaEfectivo->caja_id);
    //     }

    //     // Manejar depósito (Caja de Operaciones)
    //     if ($this->deposit_amount > 0) {
    //         $cajaDeposito = new CajaOperaciones(); // Asegúrate de usar CajaEntrada si la tabla de operaciones es la misma
    //         $cajaDeposito->caja_id = $this->caja_id; // ID de la caja de operaciones (puedes ajustarlo según corresponda)
    //         $cajaDeposito->type = 'Deposito';
    //         $cajaDeposito->monto = $this->deposit_amount;
    //         $cajaDeposito->description = 'Nº Venta: ' . $venta->id;
    //         $cajaDeposito->created_at = $this->created_at;
    //         $cajaDeposito->save();

    //         // Actualizar saldo de la caja de operaciones
    //         // $this->updateCajaSaldo($cajaDeposito->caja_id);
    //     }

    //     // Manejar transferencia (Caja de Operaciones)
    //     if ($this->transfer_amount > 0) {
    //         $cajaTransferencia = new CajaOperaciones(); // Mismo caso, usa CajaEntrada si se trata de la misma tabla
    //         $cajaTransferencia->caja_id = $this->caja_id; // ID de la caja de operaciones
    //         $cajaTransferencia->type = 'Transferencia';
    //         $cajaTransferencia->monto = $this->transfer_amount;
    //         $cajaTransferencia->description = 'Nº Venta: ' . $venta->id;
    //         $cajaTransferencia->created_at = $this->created_at;
    //         $cajaTransferencia->save();

    //         // Actualizar saldo de la caja de operaciones
    //         // $this->updateCajaSaldo($cajaTransferencia->caja_id);
    //     }
    // }

    // // Método para actualizar el saldo de la caja
    // private function updateCajaSaldo($cajaId)
    // {
    //     $cajaModelo = Caja::find($cajaId);
    //     if ($cajaModelo) {
    //         $cajaModelo->calculteEntradasSaldias();
    //     }
    // }
    // private function createSaleDetails($saleId)
    // {
    //     foreach ($this->cart as $item) {
    //         // Crear el detalle de venta
    //         DetalleVenta::create([
    //             'venta_id' => $saleId,
    //             'product_id' => $item['id'],
    //             'quantity' => $item['quantity'],
    //             'price' => $item['price'],
    //             'total' => $item['total'],
    //         ]);

    //         // Actualizar el stock del producto
    //         $product = Product::find($item['id']);
    //         if ($product) {
    //             // Registrar en el historial de stock
    //             StockHistories::create([
    //                 'product_id' => $product->id,
    //                 'cantidad' => $item['quantity'],
    //                 'stock_anterior' => $product->stock,
    //                 'stock_nuevo' => $product->stock - $item['quantity'],
    //                 'tipo_movimiento' => 'salida',
    //                 'referencia' => 'venta',
    //                 'referencia_id' => $saleId,
    //                 'observacion' => 'Venta #' . $saleId,
    //                 'created_at' =>$this->created_at

    //             ]);

    //             // Actualizar el stock del producto
    //             $product->stock -= $item['quantity'];
    //             $product->save();
    //         }
    //     }
    // }

    // // Limpiar el carrito y las variables relacionadas
    // private function resetCart()
    // {
    //     $this->cart = [];
    //     $this->subtotal = 0;
    //     $this->discount = 0;
    //     $this->total = 0;
    //     $this->client_id = null;
    //     $this->payment_method = 'Efectivo';
    // }

    // // Buscar ventas
    // public function searchData()
    // {
    //     $query = Ventas::with('cliente')
    //         ->when($this->startDate && $this->endDate, function ($q) {
    //             $q->whereBetween('created_at', [$this->startDate, $this->endDate]);
    //         })
    //         ->where(function ($q) {
    //             $q->where('id', 'like', '%' . $this->search . '%')
    //                 ->orWhere('total', 'like', '%' . $this->search . '%')
    //                 ->orWhereHas('cliente', function ($q) {
    //                     $q->where('nombre_completo', 'like', '%' . $this->search . '%');
    //                 });
    //         });

    //     return $query->orderBy('id', 'desc')->paginate($this->pagination);
    // }

    // public function generateSalesReportPdf()
    // {
    //     $ventas = Ventas::with('cliente')
    //         ->where('estado', 1) // Condición para que state sea 1
    //         ->when($this->startDate && $this->endDate, function ($query) {
    //             $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
    //         })
    //         ->when($this->search, function ($query) {
    //             $query->whereHas('cliente', function ($q) {
    //                 $q->where('nombre_completo', 'like', '%' . $this->search . '%');
    //             });
    //         })
    //         ->get();

    //     // Calcula los totales
    //     $totalVentas = $ventas->count();
    //     $totalProductosVendidos = $ventas->sum(function ($venta) {
    //         return $venta->detalles->sum('quantity');
    //     });
    //     $totalMontoCompra = $ventas->sum('monto_compra');

    //     $data = [
    //         'totalVentas' => $totalVentas,
    //         'totalProductosVendidos' => $totalProductosVendidos,
    //         'totalMontoCompra' => $totalMontoCompra,
    //         'fechaInicio' => $this->startDate,
    //         'fechaFin' => $this->endDate,
    //         'ventas' => $ventas,
    //     ];

    //     $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sales_report', $data);
    //     return response()->stream(
    //         function () use ($pdf) {
    //             echo $pdf->output();
    //         },
    //         200,
    //         [
    //             'Content-Type' => 'application/pdf',
    //             'Content-Disposition' => 'inline; filename=ReporteVentas.pdf',
    //         ],
    //     );
    // }

    // // Generar PDF
    // public function generatePdf($id)
    // {
    //     $venta = Ventas::with('cliente', 'detalles.producto')->findOrFail($id);
    //     $data = [
    //         'venta' => $venta,
    //         'subtotal' => $venta->subtotal,
    //         'discount' => $venta->descuento,
    //         'total_price' => $venta->subtotal - $venta->descuento,
    //         'company' => Company::first(),
    //     ];

    //     $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.venta', $data);
    //     return $pdf->stream('DetalleVenta.pdf');
    // }
    // protected $listeners = [
    //     'deleteRow' => 'destroy',
    // ];
    // public function destroy(Ventas $venta)
    // {
    //     DB::beginTransaction();
    //     try {
    //         // Devolver el stock de los productos
    //         foreach ($venta->detalles as $detalle) {
    //             $product = Product::find($detalle->product_id);
    //             if ($product) {
    //                 // Registrar en el historial de stock
    //                 StockHistories::create([
    //                     'product_id' => $product->id,
    //                     'cantidad' => $detalle->quantity,
    //                     'stock_anterior' => $product->stock,
    //                     'stock_nuevo' => $product->stock + $detalle->quantity,
    //                     'tipo_movimiento' => 'entrada',
    //                     'referencia' => 'anulación_venta',
    //                     'referencia_id' => $venta->id,
    //                     'observacion' => 'Anulación de Venta #' . $venta->id
    //                 ]);

    //                 // Actualizar el stock del producto
    //                 $product->stock += $detalle->quantity;
    //                 $product->save();
    //             }
    //         }

    //         // Cambiar el estado de la venta (0 = anulada, 1 = activa)
    //         $venta->estado = $venta->estado == 0 ? 1 : 0;
    //         $venta->save();

    //         // Si la venta ha sido anulada (estado = 0), también actualizar el estado de los movimientos en caja
    //         if ($venta->estado == 0) {
    //             $this->anularMovimientosCaja($venta);
    //         }

    //         DB::commit();
            
    //         // Restablecer la interfaz de usuario si es necesario
    //         $this->resetUI();
    //         $this->emit('mostrarAlertaSuccess', 'Venta Anulada y Stock Restaurado');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         $this->emit('mostrarAlertaFaild', 'Error al anular la venta: ' . $e->getMessage());
    //     }
    // }

    // // Método para anular los movimientos de caja
    // private function anularMovimientosCaja(Ventas $venta)
    // {
    //     // Anular el movimiento de efectivo si existe
    //     CajaEntrada::where('description', 'like', '%Nº Venta: ' . $venta->id . '%')->update(['estado' => 0]); // Cambiar el estado a 0 (inactivo/anulado)

    //     // Anular el movimiento de transferencia o depósito si existen
    //     CajaOperaciones::where('description', 'like', '%Nº Venta: ' . $venta->id . '%')->update(['estado' => 0]); // Cambiar el estado a 0 (inactivo/anulado)

    //         CajaSalida::where('description', 'like', '%Nº Venta: ' . $venta->id . '%')->update(['estado' => 0]);

    // }
    // public function render()
    // {
    //     $clients = Client::where('state', 1)->get();
    //     $products = Product::where('state', 1)->get();
    //     $user = Auth::user(); // O auth()->user();

    //     $atm = Caja::where('user_id', $user->id)
    //         ->where('state', 0)
    //         ->get(); // Asumiendo que tienes un campo user_id en la tabla Caja

    //     $data = $this->searchData();

    //     return view('livewire.venta.venta', compact('clients', 'products', 'data', 'atm'))->extends('layouts.theme.app')->section('content');
    // }
}
