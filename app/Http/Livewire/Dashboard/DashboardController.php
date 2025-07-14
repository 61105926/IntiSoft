<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Caja;
use App\Models\CajaEntrada;
use App\Models\CajaOperaciones;
use App\Models\CajaSalida;
use App\Models\CajaSalidaOperaciones;
use App\Models\Cita;
use App\Models\Client;
use App\Models\DetalleVenta;
use App\Models\Product;
use App\Models\User;
use App\Models\Ventas;
use App\Models\Pet;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DashboardController extends Component
{
    // public $facturaData;
    // public $cotizacionData;
    // public $montosActual;
    // public $fechas;
    // public $montosAnterior;
    // public $facturasActual;
    // public $facturasAnterior;
    // public $montosAnterior2;
    // public $cantidadesActual;
    // public $cantidadesAnterior;
    // public $cantidadesAnterior2;
    // public $cantidadesActualCerti;
    // public $cantidadesAnteriorCerti;
    // public $cantidadesAnterior2Certi;
    // public $cotizacionesMetas;
    // public $certificadoMetas;
    // public $facturaMetas;
    // public $montosIngresos;
    // public $ingresoMetas;

    // public $series;
    // public function render()
    // {
    //     $facturaData = $this->handleFacturas();
    //     // Lógica para las cotizaciones
    //     // $ingresoData = $this->handleIngresos();
    //      $ticketData = $this->handleTickets();
    //     // // dd($ticketData);
    //     // dd($facturaData['montosActual']);
    //     $this->montosActual = $facturaData['montosActual'];
    //     $this->fechas = $facturaData['fechas'];
    //     $this->montosAnterior = $facturaData['montosAnterior'];
    //     $this->montosAnterior2 = $facturaData['montosAnterior2'];
    //     $this->facturasActual = $facturaData['facturasActual'];
    //     $this->facturasAnterior = $facturaData['facturasAnterior'];

    //      $this->series = $ticketData['series'];

    //     $dashboardStats = $this->getDashboardStats();

    //     return view('livewire.dashboard.dashboard', [
    //         'stats' => $dashboardStats
    //     ])->extends('layouts.theme.app')->section('content');
    // }
    // private function handleFacturas()
    // {
    //     // Obtener facturas de la gestión actual
    //     $facturasActual = Ventas::whereYear('created_at', now()->year)->where('estado', 1)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     // Obtener facturas del año anterior
    //     $facturasAnterior = Ventas::whereYear('created_at', now()->subYear()->year)->where('estado', 1)
    //         ->orderBy('created_at', 'desc')
    //         ->get();
    //     // Obtener facturas del segundo año anterior
    //     $facturasAnterior2 = Ventas::whereYear('created_at', now()->subYears(2)->year)->where('estado', 1)
    //         ->orderBy('created_at', 'desc')
    //         ->get();
    //     // Agrupar montos por mes y sumarlos para la gestión actual
    //     $montosPorMesActual = $facturasActual
    //         ->groupBy(function ($factura) {
    //             return $factura->created_at->format('m'); // Obtener el número del mes
    //         })
    //         ->map->sum('total')
    //         ->all(); // Convertir a array

    //     // Agrupar montos por mes y sumarlos para el año anterior
    //     $montosPorMesAnterior = $facturasAnterior
    //         ->groupBy(function ($factura) {
    //             return $factura->created_at->format('m'); // Obtener el número del mes
    //         })
    //         ->map->sum('total')
    //         ->all(); // Convertir a array
    //     // Agrupar montos por mes y sumarlos para el segundo año anterior
    //     $montosPorMesAnterior2 = $facturasAnterior2
    //         ->groupBy(function ($factura) {
    //             return $factura->created_at->format('m'); // Obtener el número del mes
    //         })
    //         ->map->sum('total')
    //         ->all(); // Convertir a array
    //     // Obtener todos los meses posibles en español
    //     $todosLosMeses = collect([
    //         '01' => 'enero',
    //         '02' => 'febrero',
    //         '03' => 'marzo',
    //         '04' => 'abril',
    //         '05' => 'mayo',
    //         '06' => 'junio',
    //         '07' => 'julio',
    //         '08' => 'agosto',
    //         '09' => 'septiembre',
    //         '10' => 'octubre',
    //         '11' => 'noviembre',
    //         '12' => 'diciembre',
    //     ]);

    //     // Llenar con ceros los meses sin datos para la gestión actual
    //     $montosPorMesActual = $todosLosMeses->map(fn($nombreMes, $numeroMes) => $montosPorMesActual[$numeroMes] ?? 0);

    //     // Llenar con ceros los meses sin datos para el año anterior
    //     $montosPorMesAnterior = $todosLosMeses->map(fn($nombreMes, $numeroMes) => $montosPorMesAnterior[$numeroMes] ?? 0);
    //     // Llenar con ceros los meses sin datos para el segundo año anterior
    //     $montosPorMesAnterior2 = $todosLosMeses->map(fn($nombreMes, $numeroMes) => $montosPorMesAnterior2[$numeroMes] ?? 0);

    //     // Obtener montos y fechas para la gestión actual
    //     $montosActual = array_values($montosPorMesActual->toArray()); // Convertir a un array de valores
    //     $fechas = array_values($todosLosMeses->toArray()); // Obtener todos los meses posibles en orden

    //     // Obtener montos y fechas para el año anterior
    //     $montosAnterior = array_values($montosPorMesAnterior->toArray()); // Convertir a un array de valores
    //     $fechas = array_values($todosLosMeses->toArray()); // Obtener todos los meses posibles en orden

    //     // Obtener montos y fechas para el segundo año anterior
    //     $montosAnterior2 = array_values($montosPorMesAnterior2->toArray()); // Convertir a un array de valores
    //     $fechas = array_values($todosLosMeses->toArray()); // Obtener todos los meses posibles en orden

    //     return [
    //         'montosActual' => $montosActual,
    //         'fechas' => $fechas,
    //         'montosAnterior' => $montosAnterior,
    //         'montosAnterior2' => $montosAnterior2,
    //         'facturasActual' => $facturasActual,
    //         'facturasAnterior' => $facturasAnterior,
    //         'facturasAnterior2' => $facturasAnterior2,
    //     ];
    // }

  
    // private function handleTickets()
    // {
    //     // Obtener tickets del año 2024
    //     $tickets2024 = Cita::whereYear('created_at', now()->year)
    //         ->orderBy('created_at')
    //         ->get();

    //     // Inicializar un arreglo para almacenar los tickets por estado y mes
    //     $ticketsPorEstadoYMes = [];

    //     // Iterar sobre los tickets y agruparlos por estado y mes
    //     foreach ($tickets2024 as $ticket) {
    //         $mes = $ticket->created_at->format('m');
    //         $estado = $ticket->estado;

    //         // Define un array que asocie los identificadores de los estados con sus nombres
    //         $estadoNombres = [
    //             0 => 'Baja',
    //             1 => 'En Espera',
    //             2 => 'Atendido',
    //         ];

    //         $estadoNombre = $estadoNombres[$estado]; // Obtiene el nombre del estado según su identificador
    //         if (!isset($ticketsPorEstadoYMes[$estadoNombre][$mes])) {
    //             $ticketsPorEstadoYMes[$estadoNombre][$mes] = 0;
    //         }
    //         $ticketsPorEstadoYMes[$estadoNombre][$mes]++;
    //     }

    //     // Obtener todos los meses posibles en español
    //     $todosLosMeses = collect([
    //         '01' => 'enero',
    //         '02' => 'febrero',
    //         '03' => 'marzo',
    //         '04' => 'abril',
    //         '05' => 'mayo',
    //         '06' => 'junio',
    //         '07' => 'julio',
    //         '08' => 'agosto',
    //         '09' => 'septiembre',
    //         '10' => 'octubre',
    //         '11' => 'noviembre',
    //         '12' => 'diciembre',
    //     ]);

    //     // Inicializar un arreglo para almacenar los datos del gráfico por estado
    //     $datosGraficoPorEstado = [];

    //     // Iterar sobre los estados y construir los datos del gráfico
    //     foreach ($ticketsPorEstadoYMes as $estado => $ticketsPorMes) {
    //         $dataEstado = [];
    //         foreach ($todosLosMeses as $numeroMes => $nombreMes) {
    //             $dataEstado[] = $ticketsPorMes[$numeroMes] ?? 0;
    //         }
    //         $datosGraficoPorEstado[] = [
    //             'name' => $estado, // Utiliza el nombre del estado en lugar de su identificador
    //             'data' => $dataEstado,
    //         ];
    //     }

    //     // Reorganizar los datos para que coincidan con la estructura requerida
    //     $series = [];
    //     foreach ($datosGraficoPorEstado as $estadoData) {
    //         $series[] = [
    //             'name' => $estadoData['name'],
    //             'data' => array_values($estadoData['data']),
    //         ];
    //     }
    //     //  dd($series);
     
    //     return [
    //         'series' => $series,
    //     ];
    // }

    // private function getDashboardStats()
    // {
    //     // Obtener estadísticas generales
    //     $totalClientes = Client::where('state', 1)->count();
    //     $clientesNuevos = Client::where('state', 1)
    //         ->whereMonth('created_at', now()->month)
    //         ->count();

    //     // Agregar estadísticas de mascotas
    //     $totalMascotas = Pet::where('state', 1)->count();
    //     $mascotasNuevas = Pet::where('state', 1)
    //         ->whereMonth('created_at', now()->month)
    //         ->count();

    //     $totalProductos = Product::where('state', 1)->count();
    //     $productosStockBajo = Product::where('state', 1)
    //     ->where('stock', '<=', 3)
    //     ->select('nombre_producto', 'codigo_producto', 'stock')
    //     ->orderBy('nombre_producto') // Ordenar alfabéticamente
    //     ->get(); // 10 productos por página

    
    // $productosPorVencer = Product::where('state', 1)
    //     ->whereNotNull('fecha_vencimiento')
    //     ->where('fecha_vencimiento', '>', now())
    //     ->where('fecha_vencimiento', '<=', now()->addDays(30))
    //     ->select('nombre_producto', 'codigo_producto', 'fecha_vencimiento')
    //     ->get(); // 10 productos por página


    //     // Calcular ventas del día
    //     $ventasHoy = Ventas::whereDate('created_at', today())
    //         ->where('estado', 1)
    //         ->sum('total');

    //     // Calcular ventas del mes
    //     $ventasMes = Ventas::whereYear('created_at', now()->year)
    //         ->whereMonth('created_at', now()->month)
    //         ->where('estado', 1)
    //         ->sum('total');

    //     // Productos más vendidos
    //     $productosPopulares = DetalleVenta::select('product_id', 
    //             DB::raw('SUM(quantity) as total_vendido'))
    //         ->with('producto:id,nombre_producto,codigo_producto,stock')
    //         ->groupBy('product_id')
    //         ->orderByDesc('total_vendido')
    //         ->limit(5)
    //         ->get();
        
    //     // Obtener saldo en caja
    //     $saldoCaja =23;

    //     // Productos próximos a vencer
    //     $productosVencer = Product::where('state', 1)
    //         ->whereNotNull('fecha_vencimiento')
    //         ->where('fecha_vencimiento', '>', now())
    //         ->where('fecha_vencimiento', '<=', now()->addDays(30))
    //         ->count();

    //     // Calcular tendencias
    //     $ventasMesAnterior = Ventas::whereYear('created_at', now()->subMonth()->year)
    //         ->whereMonth('created_at', now()->subMonth()->month)
    //         ->where('estado', 1)
    //         ->sum('total');

    //     $tendenciaVentas = $ventasMesAnterior > 0 
    //         ? (($ventasMes - $ventasMesAnterior) / $ventasMesAnterior) * 100 
    //         : 0;

    //     // Obtener la caja activa
    //     $cajaActual = Caja::where('state', 0)->first();
        
    //     $cajasStats = [
    //         'balance_actual' => 0,
    //         'entradas' => [
    //             'total' => 0,
    //             'hoy' => 0,
    //             'mes' => 0
    //         ],
    //         'salidas' => [
    //             'total' => 0,
    //             'hoy' => 0,
    //             'mes' => 0
    //         ],
    //         'operaciones' => [
    //             'total' => 0,
    //             'hoy' => 0,
    //             'mes' => 0
    //         ],
    //         'operaciones_salida' => [
    //             'total' => 0,
    //             'hoy' => 0,
    //             'mes' => 0
    //         ]
    //     ];

    //     if ($cajaActual) {
    //         $cajaActual->calculteEntradasSaldias(); // Actualizar los totales

    //         $balanceActual = ($cajaActual->total_entrada + $cajaActual->total_operacion) - 
    //             ($cajaActual->total_salida + $cajaActual->total_operacion_salida);

    //         $cajasStats = [
    //             'balance_actual' => $balanceActual,
    //             'entradas' => [
    //                 'total' => $cajaActual->total_entrada,
    //                 'hoy' => CajaEntrada::where('caja_id', $cajaActual->id)
    //                     ->whereDate('created_at', today())
    //                     ->where('estado', 1)
    //                     ->sum('monto'),
    //                 'mes' => CajaEntrada::where('caja_id', $cajaActual->id)
    //                     ->whereYear('created_at', now()->year)
    //                     ->whereMonth('created_at', now()->month)
    //                     ->where('estado', 1)
    //                     ->sum('monto')
    //             ],
    //             'salidas' => [
    //                 'total' => $cajaActual->total_salida,
    //                 'hoy' => CajaSalida::where('caja_id', $cajaActual->id)
    //                     ->whereDate('created_at', today())
    //                     ->where('estado', 1)
    //                     ->sum('monto'),
    //                 'mes' => CajaSalida::where('caja_id', $cajaActual->id)
    //                     ->whereYear('created_at', now()->year)
    //                     ->whereMonth('created_at', now()->month)
    //                     ->where('estado', 1)
    //                     ->sum('monto')
    //             ],
    //             'operaciones' => [
    //                 'total' => $cajaActual->total_operacion,
    //                 'hoy' => CajaOperaciones::where('caja_id', $cajaActual->id)
    //                     ->whereDate('created_at', today())
    //                     ->where('estado', 1)
    //                     ->sum('monto'),
    //                 'mes' => CajaOperaciones::where('caja_id', $cajaActual->id)
    //                     ->whereYear('created_at', now()->year)
    //                     ->whereMonth('created_at', now()->month)
    //                     ->where('estado', 1)
    //                     ->sum('monto')
    //             ],
    //             'operaciones_salida' => [
    //                 'total' => $cajaActual->total_operacion_salida,
    //                 'hoy' => CajaSalidaOperaciones::where('caja_id', $cajaActual->id)
    //                     ->whereDate('created_at', today())
    //                     ->where('estado', 1)
    //                     ->sum('monto'),
    //                 'mes' => CajaSalidaOperaciones::where('caja_id', $cajaActual->id)
    //                     ->whereYear('created_at', now()->year)
    //                     ->whereMonth('created_at', now()->month)
    //                     ->where('estado', 1)
    //                     ->sum('monto')
    //             ]
    //         ];
    //     }

    //     return [
    //         'clientes' => [
    //             'total' => $totalClientes,
    //             'nuevos' => $clientesNuevos
    //         ],
    //         'mascotas' => [
    //             'total' => $totalMascotas,
    //             'nuevas' => $mascotasNuevas
    //         ],
    //         'productos' => [
    //             'total' => $totalProductos,
    //             'stock_bajo' => $productosStockBajo->count(),
    //             'stock_bajo_detalles' => $productosStockBajo,
    //             'por_vencer' => $productosPorVencer->count(),
    //             'por_vencer_detalles' => $productosPorVencer
    //         ],
    //         'ventas' => [
    //             'hoy' => $ventasHoy,
    //             'mes' => $ventasMes,
    //             'tendencia' => $tendenciaVentas
    //         ],
    //         'caja' => [
    //             'stats' => $cajasStats
    //         ],
    //         'productos_populares' => $productosPopulares
    //     ];
    // }
}
