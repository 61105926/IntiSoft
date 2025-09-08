<div>
    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-3">
            <label for="fechaInicio" class="form-label">Fecha Inicio</label>
            <input type="date" wire:model.live="fechaInicio" id="fechaInicio" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="fechaFin" class="form-label">Fecha Fin</label>
            <input type="date" wire:model.live="fechaFin" id="fechaFin" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="sucursalId" class="form-label">Sucursal</label>
            <select wire:model.live="sucursalId" id="sucursalId" class="form-select">
                <option value="">Todas las sucursales</option>
                @foreach(\App\Models\Sucursal::all() as $sucursal)
                    <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Métricas principales -->
    <div class="row g-3 mb-5">
        <!-- Ventas Hoy -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-cart text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Ventas Hoy</h6>
                            <h3 class="mb-0 fw-bold">{{ $metricas['ventas_hoy'] ?? 0 }}</h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> Bs. {{ number_format($metricas['ingresos_hoy'] ?? 0, 2) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas del Período -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-graph-up text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Ventas Período</h6>
                            <h3 class="mb-0 fw-bold">{{ $metricas['ventas_periodo'] ?? 0 }}</h3>
                            <small class="text-primary">
                                {{ $metricas['ventas_completadas'] ?? 0 }} completadas
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingresos Totales del Período -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-currency-dollar text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Ingresos Total</h6>
                            <h3 class="mb-0 fw-bold">Bs. {{ number_format($metricas['ingresos_total'] ?? 0, 2) }}</h3>
                            <small class="text-success">
                                Hoy: Bs. {{ number_format($metricas['ingresos_total_hoy'] ?? 0, 2) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clientes -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-people text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Clientes</h6>
                            <h3 class="mb-0 fw-bold">{{ $metricas['total_clientes'] ?? 0 }}</h3>
                            <small class="text-info">
                                +{{ $metricas['clientes_nuevos'] ?? 0 }} nuevos
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métricas adicionales -->
    <div class="row g-3 mb-5">
        <!-- Ingresos Reservas -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-purple bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-calendar-check" style="color: #6f42c1; font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="mt-3 mb-1 fw-bold">Bs. {{ number_format($metricas['ingresos_reservas_periodo'] ?? 0, 2) }}</h4>
                    <p class="text-muted mb-1">Reservas</p>
                    <small class="text-muted">{{ $metricas['reservas_activas'] ?? 0 }} activas</small>
                </div>
            </div>
        </div>

        <!-- Ingresos Alquileres -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-orange bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-house-door" style="color: #fd7e14; font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="mt-3 mb-1 fw-bold">Bs. {{ number_format($metricas['ingresos_alquileres_periodo'] ?? 0, 2) }}</h4>
                    <p class="text-muted mb-1">Alquileres</p>
                    <small class="text-muted">{{ $metricas['alquileres_activos'] ?? 0 }} activos</small>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-teal bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-box-seam" style="color: #20c997; font-size: 1.5rem;"></i>
                    </div>
                    <h4 class="mt-3 mb-1 fw-bold">{{ $metricas['total_productos'] ?? 0 }}</h4>
                    <p class="text-muted mb-1">Productos</p>
                    <small class="text-muted">Total inventario</small>
                </div>
            </div>
        </div>

        <!-- Estado Cajas -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-dark bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-cash-stack text-dark fs-4"></i>
                    </div>
                    <h4 class="mt-3 mb-1 fw-bold">{{ $estadoCajas->where('estado', 'ABIERTA')->count() }}</h4>
                    <p class="text-muted mb-1">Cajas Abiertas</p>
                    <small class="text-muted">de {{ $estadoCajas->count() }} total</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Desglose de Ingresos -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart me-2"></i>
                        Desglose de Ingresos por Categoría
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Ventas -->
                        <div class="col-md-4">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-4 text-center">
                                <i class="bi bi-cart fs-1 text-primary mb-3"></i>
                                <h5 class="text-primary">Ventas</h5>
                                <h4 class="fw-bold mb-2">Bs. {{ number_format($metricas['ingresos_periodo'] ?? 0, 2) }}</h4>
                                <div class="d-flex justify-content-between small">
                                    <span>Hoy:</span>
                                    <span class="fw-semibold">Bs. {{ number_format($metricas['ingresos_hoy'] ?? 0, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span>Cantidad:</span>
                                    <span class="fw-semibold">{{ $metricas['ventas_completadas'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Reservas -->
                        <div class="col-md-4">
                            <div class="bg-purple bg-opacity-10 rounded-3 p-4 text-center" style="--bs-bg-opacity: 0.1; background-color: #6f42c1 !important;">
                                <i class="bi bi-calendar-check fs-1 mb-3" style="color: #6f42c1;"></i>
                                <h5 style="color: #6f42c1;">Reservas</h5>
                                <h4 class="fw-bold mb-2">Bs. {{ number_format($metricas['ingresos_reservas_periodo'] ?? 0, 2) }}</h4>
                                <div class="d-flex justify-content-between small">
                                    <span>Hoy:</span>
                                    <span class="fw-semibold">Bs. {{ number_format($metricas['ingresos_reservas_hoy'] ?? 0, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span>Estimado:</span>
                                    <span class="fw-semibold">Bs. {{ number_format($metricas['total_estimado_reservas'] ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Alquileres -->
                        <div class="col-md-4">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-4 text-center">
                                <i class="bi bi-house-door fs-1 text-warning mb-3"></i>
                                <h5 class="text-warning">Alquileres</h5>
                                <h4 class="fw-bold mb-2">Bs. {{ number_format($metricas['ingresos_alquileres_periodo'] ?? 0, 2) }}</h4>
                                <div class="d-flex justify-content-between small">
                                    <span>Anticipos:</span>
                                    <span class="fw-semibold">Bs. {{ number_format($metricas['anticipos_alquileres'] ?? 0, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span>Pendientes:</span>
                                    <span class="fw-semibold text-danger">Bs. {{ number_format($metricas['saldos_pendientes_alquileres'] ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total General -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="text-success mb-1">
                                            <i class="bi bi-currency-dollar me-2"></i>
                                            Total de Ingresos del Período
                                        </h5>
                                        <small class="text-muted">
                                            Ventas + Reservas + Alquileres
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <h3 class="text-success fw-bold mb-0">
                                            Bs. {{ number_format($metricas['ingresos_total'] ?? 0, 2) }}
                                        </h3>
                                        <small class="text-success">
                                            Hoy: Bs. {{ number_format($metricas['ingresos_total_hoy'] ?? 0, 2) }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y Tablas -->
    <div class="row g-4">
        <!-- Gráfico de Ventas por Día -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart me-2"></i>
                        Ventas por Día
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="ventasChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribución por Estado -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart me-2"></i>
                        Estados de Venta
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="estadosChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-0">
        <!-- Ventas Recientes -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Ventas Recientes
                    </h5>
                    <a href="/venta" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Número</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ventasRecientes as $venta)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ $venta->numero_venta }}</span><br>
                                            <small class="text-muted">{{ $venta->sucursal->nombre }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $venta->cliente->nombre }}</div>
                                        </td>
                                        <td>
                                            <div>{{ $venta->fecha_venta->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $venta->fecha_venta->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $venta->estado_badge_class }}">
                                                {{ $venta->estado_display }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-semibold">
                                            Bs. {{ number_format($venta->total, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No hay ventas recientes
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-trophy me-2"></i>
                        Productos Populares
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($productosPopulares as $index => $producto)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : ($index == 2 ? 'success' : 'light')) }} rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <span class="fw-bold {{ $index < 3 ? 'text-white' : 'text-muted' }}">{{ $index + 1 }}</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-medium">{{ $producto->nombre }}</div>
                                <small class="text-muted">{{ $producto->codigo }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">{{ $producto->total_vendido }}</div>
                                <small class="text-muted">Bs. {{ number_format($producto->ingresos_totales, 0) }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-box-seam fs-1"></i>
                            <p class="mb-0">No hay datos disponibles</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Estado de Cajas -->
    @if($estadoCajas->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-cash-stack me-2"></i>
                            Estado de Cajas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($estadoCajas as $caja)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="fw-bold mb-0">{{ $caja->nombre }}</h6>
                                                <span class="badge {{ $caja->estado === 'ABIERTA' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $caja->estado }}
                                                </span>
                                            </div>
                                            <p class="text-muted mb-2">{{ $caja->sucursal->nombre }}</p>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Saldo:</span>
                                                <span class="fw-bold">Bs. {{ number_format($caja->saldo_actual, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de Ventas por Día
            const ventasCtx = document.getElementById('ventasChart');
            if (ventasCtx) {
                const ventasData = @json($chartData['ventas_por_dia'] ?? []);
                
                new Chart(ventasCtx, {
                    type: 'line',
                    data: {
                        labels: ventasData.map(item => item.fecha_display),
                        datasets: [
                            {
                                label: 'Ventas',
                                data: ventasData.map(item => item.ventas),
                                borderColor: '#ffc107',
                                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                                tension: 0.4,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Ingresos (Bs.)',
                                data: ventasData.map(item => item.ingresos),
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                                tension: 0.4,
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Cantidad de Ventas'
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Ingresos (Bs.)'
                                },
                                grid: {
                                    drawOnChartArea: false,
                                }
                            }
                        }
                    }
                });
            }

            // Gráfico de Estados
            const estadosCtx = document.getElementById('estadosChart');
            if (estadosCtx) {
                const estadosData = @json($chartData['ventas_por_estado'] ?? []);
                
                const colores = {
                    'PENDIENTE': '#ffc107',
                    'COMPLETADA': '#198754',
                    'CANCELADA': '#dc3545',
                    'DEVUELTA': '#6c757d'
                };

                new Chart(estadosCtx, {
                    type: 'doughnut',
                    data: {
                        labels: estadosData.map(item => item.estado),
                        datasets: [{
                            data: estadosData.map(item => item.total),
                            backgroundColor: estadosData.map(item => colores[item.estado] || '#6c757d'),
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        });
    </script>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</div>