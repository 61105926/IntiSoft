<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Venta;
use App\Models\Reserva;
use App\Models\Alquiler;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Caja;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardControllerSimple extends Component
{
    public $fechaInicio;
    public $fechaFin;
    public $sucursalId;
    
    public function mount()
    {
        $this->fechaInicio = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = Carbon::now()->format('Y-m-d');
        $this->sucursalId = auth()->user()->sucursal_id ?? null;
    }

    public function updatedFechaInicio()
    {
        // Se recarga automáticamente con Livewire
    }

    public function updatedFechaFin()
    {
        // Se recarga automáticamente con Livewire
    }

    public function updatedSucursalId()
    {
        // Se recarga automáticamente con Livewire
    }

    private function getMetricas()
    {
        $fechaInicio = Carbon::parse($this->fechaInicio)->startOfDay();
        $fechaFin = Carbon::parse($this->fechaFin)->endOfDay();
        $hoy = Carbon::today();
        $semanaAnterior = Carbon::now()->subWeek();
        $mesAnterior = Carbon::now()->subMonth();

        try {
            // Ventas
            $ventasHoy = 0;
            $ingresosVentasHoy = 0;
            $ventasPeriodo = 0;
            $ingresosVentasPeriodo = 0;
            $ventasCompletadas = 0;

            if (class_exists('App\Models\Venta')) {
                $queryVentasHoy = Venta::whereDate('fecha_venta', $hoy);
                if ($this->sucursalId) $queryVentasHoy->where('sucursal_id', $this->sucursalId);
                $ventasHoy = $queryVentasHoy->count();
                $ingresosVentasHoy = $queryVentasHoy->where('estado', 'COMPLETADA')->sum('total');

                $queryVentasPeriodo = Venta::whereBetween('created_at', [$fechaInicio, $fechaFin]);
                if ($this->sucursalId) $queryVentasPeriodo->where('sucursal_id', $this->sucursalId);
                $ventasPeriodo = $queryVentasPeriodo->count();

                $queryVentasCompletadas = Venta::whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->where('estado', 'COMPLETADA');
                if ($this->sucursalId) $queryVentasCompletadas->where('sucursal_id', $this->sucursalId);
                $ventasCompletadas = $queryVentasCompletadas->count();
                $ingresosVentasPeriodo = $queryVentasCompletadas->sum('total');
            }

            // Reservas
            $reservasPeriodo = 0;
            $reservasActivas = 0;
            $ingresosReservasHoy = 0;
            $ingresosReservasPeriodo = 0;
            $totalEstimadoReservas = 0;

            if (class_exists('App\Models\Reserva')) {
                $queryReservasHoy = Reserva::whereDate('created_at', $hoy);
                if ($this->sucursalId) $queryReservasHoy->where('sucursal_id', $this->sucursalId);
                $ingresosReservasHoy = $queryReservasHoy->sum('monto_efectivo');

                $queryReservasPeriodo = Reserva::whereBetween('created_at', [$fechaInicio, $fechaFin]);
                if ($this->sucursalId) $queryReservasPeriodo->where('sucursal_id', $this->sucursalId);
                $reservasPeriodo = $queryReservasPeriodo->count();
                $ingresosReservasPeriodo = $queryReservasPeriodo->sum('monto_efectivo');
                $totalEstimadoReservas = $queryReservasPeriodo->sum('total_estimado');

                $queryReservasActivas = Reserva::whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->where('estado', 'ACTIVA');
                if ($this->sucursalId) $queryReservasActivas->where('sucursal_id', $this->sucursalId);
                $reservasActivas = $queryReservasActivas->count();
            }

            // Alquileres
            $alquileresPeriodo = 0;
            $alquileresActivos = 0;
            $ingresosAlquileresHoy = 0;
            $ingresosAlquileresPeriodo = 0;
            $anticiposAlquileres = 0;
            $saldosPendientesAlquileres = 0;

            if (class_exists('App\Models\Alquiler')) {
                $queryAlquileresHoy = Alquiler::whereDate('created_at', $hoy);
                if ($this->sucursalId) $queryAlquileresHoy->where('sucursal_id', $this->sucursalId);
                $ingresosAlquileresHoy = $queryAlquileresHoy->sum('total');

                $queryAlquileresPeriodo = Alquiler::whereBetween('created_at', [$fechaInicio, $fechaFin]);
                if ($this->sucursalId) $queryAlquileresPeriodo->where('sucursal_id', $this->sucursalId);
                $alquileresPeriodo = $queryAlquileresPeriodo->count();
                $ingresosAlquileresPeriodo = $queryAlquileresPeriodo->sum('total');
                $anticiposAlquileres = $queryAlquileresPeriodo->sum('anticipo');
                $saldosPendientesAlquileres = $queryAlquileresPeriodo->sum('saldo_pendiente');

                $queryAlquileresActivos = Alquiler::whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->where('estado', 'ACTIVO');
                if ($this->sucursalId) $queryAlquileresActivos->where('sucursal_id', $this->sucursalId);
                $alquileresActivos = $queryAlquileresActivos->count();
            }

            // Clientes y Productos
            $totalClientes = 0;
            $clientesNuevos = 0;
            $totalProductos = 0;

            if (class_exists('App\Models\Cliente')) {
                $totalClientes = Cliente::count();
                $queryClientesNuevos = Cliente::whereBetween('created_at', [$fechaInicio, $fechaFin]);
                if ($this->sucursalId) $queryClientesNuevos->where('sucursal_id', $this->sucursalId);
                $clientesNuevos = $queryClientesNuevos->count();
            }

            if (class_exists('App\Models\Producto')) {
                $totalProductos = Producto::count();
            }

            // Totales
            $ingresosTotal = $ingresosVentasPeriodo + $ingresosReservasPeriodo + $ingresosAlquileresPeriodo;
            $ingresosTotalHoy = $ingresosVentasHoy + $ingresosReservasHoy + $ingresosAlquileresHoy;

            return [
                'ventas_hoy' => $ventasHoy,
                'ventas_periodo' => $ventasPeriodo,
                'ventas_completadas' => $ventasCompletadas,
                'ingresos_hoy' => $ingresosVentasHoy,
                'ingresos_periodo' => $ingresosVentasPeriodo,
                'reservas_periodo' => $reservasPeriodo,
                'reservas_activas' => $reservasActivas,
                'ingresos_reservas_hoy' => $ingresosReservasHoy,
                'ingresos_reservas_periodo' => $ingresosReservasPeriodo,
                'total_estimado_reservas' => $totalEstimadoReservas,
                'alquileres_periodo' => $alquileresPeriodo,
                'alquileres_activos' => $alquileresActivos,
                'ingresos_alquileres_hoy' => $ingresosAlquileresHoy,
                'ingresos_alquileres_periodo' => $ingresosAlquileresPeriodo,
                'anticipos_alquileres' => $anticiposAlquileres,
                'saldos_pendientes_alquileres' => $saldosPendientesAlquileres,
                'ingresos_total' => $ingresosTotal,
                'ingresos_total_hoy' => $ingresosTotalHoy,
                'clientes_nuevos' => $clientesNuevos,
                'total_productos' => $totalProductos,
                'total_clientes' => $totalClientes,
            ];
        } catch (\Exception $e) {
            // En caso de error, devolver valores por defecto
            return [
                'ventas_hoy' => 0, 'ventas_periodo' => 0, 'ventas_completadas' => 0,
                'ingresos_hoy' => 0, 'ingresos_periodo' => 0,
                'reservas_periodo' => 0, 'reservas_activas' => 0,
                'ingresos_reservas_hoy' => 0, 'ingresos_reservas_periodo' => 0, 'total_estimado_reservas' => 0,
                'alquileres_periodo' => 0, 'alquileres_activos' => 0,
                'ingresos_alquileres_hoy' => 0, 'ingresos_alquileres_periodo' => 0,
                'anticipos_alquileres' => 0, 'saldos_pendientes_alquileres' => 0,
                'ingresos_total' => 0, 'ingresos_total_hoy' => 0,
                'clientes_nuevos' => 0, 'total_productos' => 0, 'total_clientes' => 0,
            ];
        }
    }

    private function getVentasRecientes()
    {
        try {
            if (!class_exists('App\Models\Venta')) {
                return collect([]);
            }

            $query = Venta::with(['cliente', 'sucursal', 'usuario'])
                ->orderBy('created_at', 'desc')
                ->limit(10);

            if ($this->sucursalId) {
                $query->where('sucursal_id', $this->sucursalId);
            }

            return $query->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    private function getEstadoCajas()
    {
        try {
            if (!class_exists('App\Models\Caja')) {
                return collect([]);
            }

            $query = Caja::with('sucursal');
            
            if ($this->sucursalId) {
                $query->where('sucursal_id', $this->sucursalId);
            }

            return $query->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    private function getProductosPopulares()
    {
        try {
            $fechaInicio = Carbon::parse($this->fechaInicio)->startOfDay();
            $fechaFin = Carbon::parse($this->fechaFin)->endOfDay();

            $query = DB::table('venta_detalles')
                ->join('productos', 'venta_detalles.producto_id', '=', 'productos.id')
                ->join('ventas', 'venta_detalles.venta_id', '=', 'ventas.id')
                ->whereBetween('ventas.created_at', [$fechaInicio, $fechaFin])
                ->select(
                    'productos.nombre',
                    'productos.codigo',
                    DB::raw('SUM(venta_detalles.cantidad) as total_vendido'),
                    DB::raw('SUM(venta_detalles.subtotal) as ingresos_totales')
                )
                ->groupBy('productos.id', 'productos.nombre', 'productos.codigo')
                ->orderBy('total_vendido', 'desc')
                ->limit(10);

            if ($this->sucursalId) {
                $query->where('ventas.sucursal_id', $this->sucursalId);
            }

            return $query->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function render()
    {
        $metricas = $this->getMetricas();
        $ventasRecientes = $this->getVentasRecientes();
        $estadoCajas = $this->getEstadoCajas();
        $productosPopulares = $this->getProductosPopulares();
        
        // Datos simplificados para gráficos
        $chartData = [
            'ventas_por_dia' => [],
            'ventas_por_estado' => []
        ];

        return view('livewire.dashboard.dashboard-improved', [
            'metricas' => $metricas,
            'ventasRecientes' => $ventasRecientes,
            'productosPopulares' => $productosPopulares,
            'estadoCajas' => $estadoCajas,
            'chartData' => $chartData
        ]);
    }
}