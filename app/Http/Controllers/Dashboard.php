<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Alquiler;
use App\Models\Reserva;
use App\Models\StockPorSucursal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{
    public function index()
    {
        $userSucursalId = Auth::user()->sucursal_id;

        // Datos de ventas del día
        $ventasHoy = Venta::whereDate('fecha_venta', Carbon::today())
            ->when($userSucursalId, function($query) use ($userSucursalId) {
                return $query->where('sucursal_id', $userSucursalId);
            })
            ->sum('total');

        $ventasAyer = Venta::whereDate('fecha_venta', Carbon::yesterday())
            ->when($userSucursalId, function($query) use ($userSucursalId) {
                return $query->where('sucursal_id', $userSucursalId);
            })
            ->sum('total');

        $porcentajeVentas = $ventasAyer > 0 ? (($ventasHoy - $ventasAyer) / $ventasAyer) * 100 : 0;

        // Alquileres activos
        $alquileresActivos = Alquiler::where('estado', 'ACTIVO')
            ->when($userSucursalId, function($query) use ($userSucursalId) {
                return $query->where('sucursal_id', $userSucursalId);
            })
            ->count();

        $alquileresPorVencer = Alquiler::where('estado', 'ACTIVO')
            ->whereDate('fecha_devolucion_programada', '<=', Carbon::now()->addDays(3))
            ->when($userSucursalId, function($query) use ($userSucursalId) {
                return $query->where('sucursal_id', $userSucursalId);
            })
            ->count();

        // Productos en stock
        $productosEnStock = StockPorSucursal::when($userSucursalId, function($query) use ($userSucursalId) {
                return $query->where('sucursal_id', $userSucursalId);
            })
            ->sum('stock_actual');

        $stockBajo = StockPorSucursal::where('stock_actual', '<=', DB::raw('stock_minimo'))
            ->when($userSucursalId, function($query) use ($userSucursalId) {
                return $query->where('sucursal_id', $userSucursalId);
            })
            ->count();

        // Ingresos del mes
        $ingresosMes = Venta::whereMonth('fecha_venta', Carbon::now()->month)
            ->whereYear('fecha_venta', Carbon::now()->year)
            ->when($userSucursalId, function($query) use ($userSucursalId) {
                return $query->where('sucursal_id', $userSucursalId);
            })
            ->sum('total');

        $ingresosMesAnterior = Venta::whereMonth('fecha_venta', Carbon::now()->subMonth()->month)
            ->whereYear('fecha_venta', Carbon::now()->subMonth()->year)
            ->when($userSucursalId, function($query) use ($userSucursalId) {
                return $query->where('sucursal_id', $userSucursalId);
            })
            ->sum('total');

        $porcentajeIngresos = $ingresosMesAnterior > 0 ? (($ingresosMes - $ingresosMesAnterior) / $ingresosMesAnterior) * 100 : 0;

        // Transacciones recientes
        $transaccionesRecientes = collect();

        // Agregar ventas recientes
        $ventasRecientes = Venta::with('cliente')
            ->when($userSucursalId, function($query) use ($userSucursalId) {
                return $query->where('sucursal_id', $userSucursalId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($venta) {
                return [
                    'tipo' => 'Venta',
                    'tipo_class' => 'success',
                    'tipo_icon' => 'fas fa-shopping-cart',
                    'cliente' => $venta->cliente->nombres ?? 'Cliente N/A',
                    'monto' => $venta->total,
                    'estado' => $venta->estado,
                    'fecha' => $venta->created_at
                ];
            });

        // Agregar alquileres recientes
        $alquileresRecientes = Alquiler::with('cliente')
            ->when($userSucursalId, function($query) use ($userSucursalId) {
                return $query->where('sucursal_id', $userSucursalId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get()
            ->map(function($alquiler) {
                return [
                    'tipo' => 'Alquiler',
                    'tipo_class' => 'info',
                    'tipo_icon' => 'fas fa-handshake',
                    'cliente' => $alquiler->cliente->nombres ?? 'Cliente N/A',
                    'monto' => $alquiler->total,
                    'estado' => $alquiler->estado,
                    'fecha' => $alquiler->created_at
                ];
            });

        // Agregar reservas recientes
        $reservasRecientes = Reserva::with('cliente')
            ->when($userSucursalId, function($query) use ($userSucursalId) {
                return $query->where('sucursal_id', $userSucursalId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get()
            ->map(function($reserva) {
                return [
                    'tipo' => 'Reserva',
                    'tipo_class' => 'primary',
                    'tipo_icon' => 'fas fa-calendar',
                    'cliente' => $reserva->cliente->nombres ?? 'Cliente N/A',
                    'monto' => $reserva->total,
                    'estado' => $reserva->estado,
                    'fecha' => $reserva->created_at
                ];
            });

        $transaccionesRecientes = $transaccionesRecientes
            ->merge($ventasRecientes)
            ->merge($alquileresRecientes)
            ->merge($reservasRecientes)
            ->sortByDesc('fecha')
            ->take(5);

        // Productos con stock bajo
        $productosStockBajo = StockPorSucursal::with('producto')
            ->where('stock_actual', '<=', DB::raw('stock_minimo'))
            ->when($userSucursalId, function($query) use ($userSucursalId) {
                return $query->where('sucursal_id', $userSucursalId);
            })
            ->orderBy('stock_actual')
            ->limit(5)
            ->get();

        return view('dashboard.modern-dashboard', compact(
            'ventasHoy',
            'porcentajeVentas',
            'alquileresActivos',
            'alquileresPorVencer',
            'productosEnStock',
            'stockBajo',
            'ingresosMes',
            'porcentajeIngresos',
            'transaccionesRecientes',
            'productosStockBajo'
        ));
    }
}
