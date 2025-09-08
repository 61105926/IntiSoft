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

class DashboardController extends Component
{
    // Filtros de fecha
    public $fechaInicio;
    public $fechaFin;
    public $sucursalId;
    
    // Métricas
    public $metricas = [];
    public $ventasRecientes = [];
    public $productosPopulares = [];
    public $estadoCajas = [];
    public $chartData = [];

    public function mount()
    {
        $this->fechaInicio = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = Carbon::now()->format('Y-m-d');
        $this->sucursalId = auth()->user()->sucursal_id ?? null;
        
        $this->cargarDatos();
    }

    public function updatedFechaInicio()
    {
        $this->cargarDatos();
    }

    public function updatedFechaFin()
    {
        $this->cargarDatos();
    }

    public function updatedSucursalId()
    {
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $this->cargarMetricas();
        $this->cargarVentasRecientes();
        $this->cargarProductosPopulares();
        $this->cargarEstadoCajas();
        $this->cargarDatosGraficos();
    }

    private function cargarMetricas()
    {
        $fechaInicio = Carbon::parse($this->fechaInicio);
        $fechaFin = Carbon::parse($this->fechaFin)->endOfDay();

        // Ventas
        $ventasHoy = Venta::whereDate('fecha_venta', Carbon::today());
        if ($this->sucursalId) $ventasHoy->where('sucursal_id', $this->sucursalId);
        
        $ventasPeriodo = Venta::whereBetween('created_at', [$fechaInicio, $fechaFin]);
        if ($this->sucursalId) $ventasPeriodo->where('sucursal_id', $this->sucursalId);
        
        $ventasCompletadas = Venta::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('estado', 'COMPLETADA');
        if ($this->sucursalId) $ventasCompletadas->where('sucursal_id', $this->sucursalId);

        // Reservas
        $reservasPeriodo = Reserva::whereBetween('created_at', [$fechaInicio, $fechaFin]);
        if ($this->sucursalId) $reservasPeriodo->where('sucursal_id', $this->sucursalId);
        
        $reservasActivas = Reserva::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('estado', 'ACTIVA');
        if ($this->sucursalId) $reservasActivas->where('sucursal_id', $this->sucursalId);

        // Alquileres
        $alquileresPeriodo = Alquiler::whereBetween('created_at', [$fechaInicio, $fechaFin]);
        if ($this->sucursalId) $alquileresPeriodo->where('sucursal_id', $this->sucursalId);
        
        $alquileresActivos = Alquiler::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('estado', 'ACTIVO');
        if ($this->sucursalId) $alquileresActivos->where('sucursal_id', $this->sucursalId);

        // Clientes
        $clientesNuevos = Cliente::whereBetween('created_at', [$fechaInicio, $fechaFin]);
        if ($this->sucursalId) $clientesNuevos->where('sucursal_id', $this->sucursalId);

        // Productos
        $totalProductos = Producto::count();

        // Ingresos de Ventas
        $ingresos = $ventasCompletadas->sum('total');
        $ingresosHoy = $ventasHoy->where('estado', 'COMPLETADA')->sum('total');

        // Ingresos de Reservas
        $reservasQuery = Reserva::whereBetween('created_at', [$fechaInicio, $fechaFin]);
        if ($this->sucursalId) $reservasQuery->where('sucursal_id', $this->sucursalId);
        $ingresosReservas = $reservasQuery->sum('monto_efectivo');
        $totalEstimadoReservas = $reservasQuery->sum('total_estimado');

        $reservasHoy = Reserva::whereDate('created_at', Carbon::today());
        if ($this->sucursalId) $reservasHoy->where('sucursal_id', $this->sucursalId);
        $ingresosReservasHoy = $reservasHoy->sum('monto_efectivo');

        // Ingresos de Alquileres
        $alquileresQuery = Alquiler::whereBetween('created_at', [$fechaInicio, $fechaFin]);
        if ($this->sucursalId) $alquileresQuery->where('sucursal_id', $this->sucursalId);
        $ingresosAlquileres = $alquileresQuery->sum('total');
        $anticiposAlquileres = $alquileresQuery->sum('anticipo');
        $saldosPendientesAlquileres = $alquileresQuery->sum('saldo_pendiente');

        $alquileresHoy = Alquiler::whereDate('created_at', Carbon::today());
        if ($this->sucursalId) $alquileresHoy->where('sucursal_id', $this->sucursalId);
        $ingresosAlquileresHoy = $alquileresHoy->sum('total');

        // Totales combinados
        $ingresosTotal = $ingresos + $ingresosReservas + $ingresosAlquileres;
        $ingresosTotalHoy = $ingresosHoy + $ingresosReservasHoy + $ingresosAlquileresHoy;

        $this->metricas = [
            'ventas_hoy' => $ventasHoy->count(),
            'ventas_periodo' => $ventasPeriodo->count(),
            'ventas_completadas' => $ventasCompletadas->count(),
            'ingresos_hoy' => $ingresosHoy,
            'ingresos_periodo' => $ingresos,
            'reservas_periodo' => $reservasPeriodo->count(),
            'reservas_activas' => $reservasActivas->count(),
            'ingresos_reservas_hoy' => $ingresosReservasHoy,
            'ingresos_reservas_periodo' => $ingresosReservas,
            'total_estimado_reservas' => $totalEstimadoReservas,
            'alquileres_periodo' => $alquileresPeriodo->count(),
            'alquileres_activos' => $alquileresActivos->count(),
            'ingresos_alquileres_hoy' => $ingresosAlquileresHoy,
            'ingresos_alquileres_periodo' => $ingresosAlquileres,
            'anticipos_alquileres' => $anticiposAlquileres,
            'saldos_pendientes_alquileres' => $saldosPendientesAlquileres,
            'ingresos_total' => $ingresosTotal,
            'ingresos_total_hoy' => $ingresosTotalHoy,
            'clientes_nuevos' => $clientesNuevos->count(),
            'total_productos' => $totalProductos,
            'total_clientes' => Cliente::count(),
        ];
    }

    private function cargarVentasRecientes()
    {
        $query = Venta::with(['cliente', 'sucursal', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->limit(10);

        if ($this->sucursalId) {
            $query->where('sucursal_id', $this->sucursalId);
        }

        $this->ventasRecientes = $query->get();
    }

    private function cargarProductosPopulares()
    {
        $fechaInicio = Carbon::parse($this->fechaInicio);
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

        $this->productosPopulares = $query->get();
    }

    private function cargarEstadoCajas()
    {
        $query = Caja::with('sucursal');
        
        if ($this->sucursalId) {
            $query->where('sucursal_id', $this->sucursalId);
        }

        $this->estadoCajas = $query->get();
    }

    private function cargarDatosGraficos()
    {
        try {
            $fechaInicio = Carbon::parse($this->fechaInicio);
            $fechaFin = Carbon::parse($this->fechaFin);
            
            // Datos para gráfico de ventas por día - simplificado
            $ventasPorDia = [];
            $current = $fechaInicio->copy();
            $maxDays = 30; // Limitar a 30 días para evitar problemas de rendimiento
            $dayCount = 0;
            
            while ($current <= $fechaFin && $dayCount < $maxDays) {
                $query = Venta::where('fecha_venta', $current->format('Y-m-d'))
                    ->where('estado', 'COMPLETADA');
                    
                if ($this->sucursalId) {
                    $query->where('sucursal_id', $this->sucursalId);
                }
                
                $ventasPorDia[] = [
                    'fecha' => $current->format('Y-m-d'),
                    'fecha_display' => $current->format('d/m'),
                    'ventas' => $query->count(),
                    'ingresos' => (float) $query->sum('total')
                ];
                
                $current->addDay();
                $dayCount++;
            }

            // Datos para gráfico de distribución por estado - simplificado
            $query = Venta::select(DB::raw('estado, COUNT(*) as total'))
                ->whereBetween('created_at', [$fechaInicio, $fechaFin->endOfDay()]);
                
            if ($this->sucursalId) {
                $query->where('sucursal_id', $this->sucursalId);
            }
            
            $ventasPorEstado = $query->groupBy('estado')->get();

            $this->chartData = [
                'ventas_por_dia' => $ventasPorDia,
                'ventas_por_estado' => $ventasPorEstado->toArray()
            ];
        } catch (\Exception $e) {
            // En caso de error, usar datos vacíos
            $this->chartData = [
                'ventas_por_dia' => [],
                'ventas_por_estado' => []
            ];
        }
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard', [
            'metricas' => $this->metricas,
            'ventasRecientes' => $this->ventasRecientes,
            'productosPopulares' => $this->productosPopulares,
            'estadoCajas' => $this->estadoCajas,
            'chartData' => $this->chartData
        ]);
    }
}