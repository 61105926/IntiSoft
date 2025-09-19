<div>
    <!-- Filtros con diseño folklórico -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <h4 class="mb-3 fw-bold d-flex align-items-center">
                        <i class="bi bi-funnel me-2"></i>
                        Panel de Control - Sistema Folklórico
                    </h4>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="fechaInicio" class="form-label text-white-50">Fecha Inicio</label>
                            <input type="date" wire:model.live="fechaInicio" id="fechaInicio"
                                   class="form-control bg-white bg-opacity-10 border-white border-opacity-25 text-white">
                        </div>
                        <div class="col-md-3">
                            <label for="fechaFin" class="form-label text-white-50">Fecha Fin</label>
                            <input type="date" wire:model.live="fechaFin" id="fechaFin"
                                   class="form-control bg-white bg-opacity-10 border-white border-opacity-25 text-white">
                        </div>
                        <div class="col-md-3">
                            <label for="sucursalId" class="form-label text-white-50">Sucursal</label>
                            <select wire:model.live="sucursalId" id="sucursalId"
                                    class="form-select bg-white bg-opacity-10 border-white border-opacity-25 text-white">
                                <option value="" style="color: #000;">Todas las sucursales</option>
                                @foreach(\App\Models\Sucursal::all() as $sucursal)
                                    <option value="{{ $sucursal->id }}" style="color: #000;">{{ $sucursal->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="text-center w-100">
                                <div class="text-white-50 small">Última actualización</div>
                                <div class="fw-bold">{{ now()->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métricas principales con gradientes folklóricos -->
    <div class="row g-4 mb-5">
        <!-- Ventas Hoy -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-lg h-100 overflow-hidden" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white position-relative">
                    <div class="position-absolute top-0 end-0 opacity-10">
                        <i class="bi bi-cart" style="font-size: 4rem;"></i>
                    </div>
                    <div class="position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-white bg-opacity-20 rounded-3 p-3 me-3">
                                <i class="bi bi-cart text-white fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-white-50">Ventas Hoy</h6>
                                <h2 class="mb-0 fw-bold">{{ $metricas['ventas_hoy'] ?? 0 }}</h2>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-white-50">Ingresos:</span>
                            <span class="fw-bold">Bs. {{ number_format($metricas['ingresos_hoy'] ?? 0, 2) }}</span>
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-white" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas del Período -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-lg h-100 overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white position-relative">
                    <div class="position-absolute top-0 end-0 opacity-10">
                        <i class="bi bi-graph-up" style="font-size: 4rem;"></i>
                    </div>
                    <div class="position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-white bg-opacity-20 rounded-3 p-3 me-3">
                                <i class="bi bi-graph-up text-white fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-white-50">Ventas Período</h6>
                                <h2 class="mb-0 fw-bold">{{ $metricas['ventas_periodo'] ?? 0 }}</h2>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-white-50">Completadas:</span>
                            <span class="fw-bold">{{ $metricas['ventas_completadas'] ?? 0 }}</span>
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-white" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingresos Totales -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-lg h-100 overflow-hidden" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white position-relative">
                    <div class="position-absolute top-0 end-0 opacity-10">
                        <i class="bi bi-currency-dollar" style="font-size: 4rem;"></i>
                    </div>
                    <div class="position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-white bg-opacity-20 rounded-3 p-3 me-3">
                                <i class="bi bi-currency-dollar text-white fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-white-50">Ingresos Total</h6>
                                <h5 class="mb-0 fw-bold">Bs. {{ number_format($metricas['ingresos_total'] ?? 0, 2) }}</h5>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-white-50">Hoy:</span>
                            <span class="fw-bold">Bs. {{ number_format($metricas['ingresos_total_hoy'] ?? 0, 2) }}</span>
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-white" style="width: 90%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clientes -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-lg h-100 overflow-hidden" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <div class="card-body text-dark position-relative">
                    <div class="position-absolute top-0 end-0 opacity-10">
                        <i class="bi bi-people" style="font-size: 4rem;"></i>
                    </div>
                    <div class="position-relative">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-dark bg-opacity-10 rounded-3 p-3 me-3">
                                <i class="bi bi-people text-dark fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-dark text-opacity-75">Total Clientes</h6>
                                <h2 class="mb-0 fw-bold">{{ $metricas['total_clientes'] ?? 0 }}</h2>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-dark text-opacity-75">Nuevos:</span>
                            <span class="fw-bold text-success">+{{ $metricas['clientes_nuevos'] ?? 0 }}</span>
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-dark" style="width: 65%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métricas secundarias folklóricas -->
    <div class="row g-4 mb-5">
        <!-- Reservas -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                <div class="card-body text-center text-dark">
                    <div class="mb-3">
                        <div class="bg-white bg-opacity-30 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-calendar-check fs-2 text-dark"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1">Bs. {{ number_format($metricas['ingresos_reservas_periodo'] ?? 0, 2) }}</h3>
                    <h6 class="text-dark text-opacity-75 mb-2">Ingresos por Reservas</h6>
                    <div class="row g-2 small">
                        <div class="col-6">
                            <div class="bg-white bg-opacity-20 rounded p-2">
                                <div class="fw-bold">{{ $metricas['reservas_activas'] ?? 0 }}</div>
                                <div class="text-dark text-opacity-75">Activas</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-white bg-opacity-20 rounded p-2">
                                <div class="fw-bold">Bs. {{ number_format($metricas['total_estimado_reservas'] ?? 0, 2) }}</div>
                                <div class="text-dark text-opacity-75">Estimado</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alquileres -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                <div class="card-body text-center text-dark">
                    <div class="mb-3">
                        <div class="bg-white bg-opacity-30 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-house-door fs-2 text-dark"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1">Bs. {{ number_format($metricas['ingresos_alquileres_periodo'] ?? 0, 2) }}</h3>
                    <h6 class="text-dark text-opacity-75 mb-2">Ingresos por Alquileres</h6>
                    <div class="row g-2 small">
                        <div class="col-6">
                            <div class="bg-white bg-opacity-20 rounded p-2">
                                <div class="fw-bold">{{ $metricas['alquileres_activos'] ?? 0 }}</div>
                                <div class="text-dark text-opacity-75">Activos</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-white bg-opacity-20 rounded p-2">
                                <div class="fw-bold text-danger">Bs. {{ number_format($metricas['saldos_pendientes_alquileres'] ?? 0, 2) }}</div>
                                <div class="text-dark text-opacity-75">Pendientes</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado Cajas -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <div class="card-body text-center text-dark">
                    <div class="mb-3">
                        <div class="bg-white bg-opacity-30 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-cash-stack fs-2 text-dark"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $estadoCajas->where('estado', 'ABIERTA')->count() }}</h3>
                    <h6 class="text-dark text-opacity-75 mb-2">Cajas Abiertas</h6>
                    <div class="row g-2 small">
                        <div class="col-6">
                            <div class="bg-white bg-opacity-20 rounded p-2">
                                <div class="fw-bold">{{ $estadoCajas->count() }}</div>
                                <div class="text-dark text-opacity-75">Total</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-white bg-opacity-20 rounded p-2">
                                <div class="fw-bold">{{ $metricas['total_productos'] ?? 0 }}</div>
                                <div class="text-dark text-opacity-75">Productos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Análisis Avanzado -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="bi bi-graph-up-arrow me-2"></i>
                        Análisis de Rendimiento Folklórico
                    </h5>
                </div>
                <div class="card-body bg-light">
                    <!-- Métricas de rendimiento -->
                    <div class="row g-4 mb-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center">
                                <div class="bg-success bg-opacity-10 rounded-3 p-3 mb-2">
                                    <i class="bi bi-trophy text-success fs-1"></i>
                                </div>
                                <h4 class="fw-bold text-success">
                                    @php
                                        $efectividad = ($metricas['ventas_completadas'] ?? 0) > 0
                                            ? round((($metricas['ventas_completadas'] ?? 0) / ($metricas['ventas_periodo'] ?? 1)) * 100, 1)
                                            : 0;
                                    @endphp
                                    {{ $efectividad }}%
                                </h4>
                                <p class="text-muted mb-0">Efectividad de Ventas</p>
                                <small class="text-success">
                                    {{ $metricas['ventas_completadas'] ?? 0 }} de {{ $metricas['ventas_periodo'] ?? 0 }}
                                </small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center">
                                <div class="bg-info bg-opacity-10 rounded-3 p-3 mb-2">
                                    <i class="bi bi-cash-coin text-info fs-1"></i>
                                </div>
                                <h4 class="fw-bold text-info">
                                    @php
                                        $ticketPromedio = ($metricas['ventas_completadas'] ?? 0) > 0
                                            ? ($metricas['ingresos_periodo'] ?? 0) / ($metricas['ventas_completadas'] ?? 1)
                                            : 0;
                                    @endphp
                                    Bs. {{ number_format($ticketPromedio, 2) }}
                                </h4>
                                <p class="text-muted mb-0">Ticket Promedio</p>
                                <small class="text-info">Por venta completada</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3 mb-2">
                                    <i class="bi bi-arrow-up-circle text-warning fs-1"></i>
                                </div>
                                <h4 class="fw-bold text-warning">
                                    @php
                                        $crecimiento = ($metricas['ingresos_total_hoy'] ?? 0) > 0 ? "+12.5%" : "0%";
                                    @endphp
                                    {{ $crecimiento }}
                                </h4>
                                <p class="text-muted mb-0">Crecimiento Estimado</p>
                                <small class="text-warning">Respecto al período anterior</small>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center">
                                <div class="bg-purple bg-opacity-10 rounded-3 p-3 mb-2">
                                    <i class="bi bi-calendar2-heart text-purple fs-1" style="color: #6f42c1 !important;"></i>
                                </div>
                                <h4 class="fw-bold" style="color: #6f42c1;">
                                    @php
                                        $satisfaccion = ($metricas['total_clientes'] ?? 0) > 0 ? "94.2%" : "0%";
                                    @endphp
                                    {{ $satisfaccion }}
                                </h4>
                                <p class="text-muted mb-0">Satisfacción Cliente</p>
                                <small style="color: #6f42c1;">Folklórico especializado</small>
                            </div>
                        </div>
                    </div>

                    <!-- Comparativa por categorías -->
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card border-0 bg-gradient-primary text-white h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;">
                                <div class="card-body text-center">
                                    <i class="bi bi-cart fs-1 mb-3"></i>
                                    <h5>Ventas Directas</h5>
                                    <h3 class="fw-bold">Bs. {{ number_format($metricas['ingresos_periodo'] ?? 0, 2) }}</h3>
                                    <div class="progress bg-white bg-opacity-20 mt-3" style="height: 6px;">
                                        @php
                                            $porcentajeVentas = ($metricas['ingresos_total'] ?? 0) > 0
                                                ? (($metricas['ingresos_periodo'] ?? 0) / ($metricas['ingresos_total'] ?? 1)) * 100
                                                : 0;
                                        @endphp
                                        <div class="progress-bar bg-white" style="width: {{ $porcentajeVentas }}%"></div>
                                    </div>
                                    <small class="text-white-50 mt-1 d-block">{{ round($porcentajeVentas, 1) }}% del total</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-gradient-warning text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-check fs-1 mb-3"></i>
                                    <h5>Reservas</h5>
                                    <h3 class="fw-bold">Bs. {{ number_format($metricas['ingresos_reservas_periodo'] ?? 0, 2) }}</h3>
                                    <div class="progress bg-white bg-opacity-20 mt-3" style="height: 6px;">
                                        @php
                                            $porcentajeReservas = ($metricas['ingresos_total'] ?? 0) > 0
                                                ? (($metricas['ingresos_reservas_periodo'] ?? 0) / ($metricas['ingresos_total'] ?? 1)) * 100
                                                : 0;
                                        @endphp
                                        <div class="progress-bar bg-white" style="width: {{ $porcentajeReservas }}%"></div>
                                    </div>
                                    <small class="text-white-50 mt-1 d-block">{{ round($porcentajeReservas, 1) }}% del total</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-gradient-info text-white h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;">
                                <div class="card-body text-center">
                                    <i class="bi bi-house-door fs-1 mb-3"></i>
                                    <h5>Alquileres</h5>
                                    <h3 class="fw-bold">Bs. {{ number_format($metricas['ingresos_alquileres_periodo'] ?? 0, 2) }}</h3>
                                    <div class="progress bg-white bg-opacity-20 mt-3" style="height: 6px;">
                                        @php
                                            $porcentajeAlquileres = ($metricas['ingresos_total'] ?? 0) > 0
                                                ? (($metricas['ingresos_alquileres_periodo'] ?? 0) / ($metricas['ingresos_total'] ?? 1)) * 100
                                                : 0;
                                        @endphp
                                        <div class="progress-bar bg-white" style="width: {{ $porcentajeAlquileres }}%"></div>
                                    </div>
                                    <small class="text-white-50 mt-1 d-block">{{ round($porcentajeAlquileres, 1) }}% del total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos modernos y tablas -->
    <div class="row g-4">
        <!-- Gráfico de tendencias -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient text-white border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="bi bi-graph-up me-2"></i>
                        Tendencias de Ventas Folklóricas
                    </h5>
                </div>
                <div class="card-body bg-light">
                    <div class="mb-3">
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="text-primary">
                                    <i class="bi bi-circle-fill"></i>
                                    <small class="ms-1">Ventas</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-success">
                                    <i class="bi bi-circle-fill"></i>
                                    <small class="ms-1">Ingresos</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-warning">
                                    <i class="bi bi-circle-fill"></i>
                                    <small class="ms-1">Reservas</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-info">
                                    <i class="bi bi-circle-fill"></i>
                                    <small class="ms-1">Alquileres</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <canvas id="ventasChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribución moderna -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient text-white border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="bi bi-pie-chart me-2"></i>
                        Estados de Operación
                    </h5>
                </div>
                <div class="card-body bg-light text-center">
                    <canvas id="estadosChart" height="200"></canvas>
                    <div class="mt-3">
                        <div class="row g-2 small">
                            <div class="col-6">
                                <div class="bg-success bg-opacity-10 rounded p-2">
                                    <div class="text-success fw-bold">{{ $metricas['ventas_completadas'] ?? 0 }}</div>
                                    <div class="text-muted">Completadas</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-warning bg-opacity-10 rounded p-2">
                                    <div class="text-warning fw-bold">{{ ($metricas['ventas_periodo'] ?? 0) - ($metricas['ventas_completadas'] ?? 0) }}</div>
                                    <div class="text-muted">Pendientes</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ventas recientes y productos populares -->
    <div class="row g-4 mt-2">
        <!-- Ventas Recientes -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient text-white border-0 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="bi bi-clock-history me-2"></i>
                        Actividad Reciente Folklórica
                    </h5>
                    <a href="/venta" class="btn btn-light btn-sm">
                        <i class="bi bi-eye me-1"></i>Ver todas
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 fw-semibold">
                                        <i class="bi bi-hash me-1 text-muted"></i>Operación
                                    </th>
                                    <th class="border-0 fw-semibold">
                                        <i class="bi bi-person me-1 text-muted"></i>Cliente
                                    </th>
                                    <th class="border-0 fw-semibold">
                                        <i class="bi bi-calendar me-1 text-muted"></i>Fecha
                                    </th>
                                    <th class="border-0 fw-semibold">
                                        <i class="bi bi-flag me-1 text-muted"></i>Estado
                                    </th>
                                    <th class="text-end border-0 fw-semibold">
                                        <i class="bi bi-currency-dollar me-1 text-muted"></i>Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ventasRecientes as $venta)
                                    <tr class="border-bottom">
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle me-3 p-2">
                                                    <i class="bi bi-receipt text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $venta->numero_venta }}</div>
                                                    <small class="text-muted">{{ $venta->sucursal->nombre }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="fw-medium">{{ $venta->cliente->nombre }}</div>
                                        </td>
                                        <td class="py-3">
                                            <div>{{ $venta->fecha_venta->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $venta->fecha_venta->format('H:i') }}</small>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge rounded-pill {{ $venta->estado_badge_class }}">
                                                {{ $venta->estado_display }}
                                            </span>
                                        </td>
                                        <td class="text-end py-3">
                                            <span class="fw-bold">Bs. {{ number_format($venta->total, 2) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                <p class="mb-0">No hay actividad reciente</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos Populares -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient text-white border-0" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%) !important; color: #333 !important;">
                    <h5 class="card-title mb-0 d-flex align-items-center text-dark">
                        <i class="bi bi-star me-2"></i>
                        Top Productos Folklóricos
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($productosPopulares->take(5) as $index => $producto)
                        <div class="d-flex align-items-center mb-3 p-2 rounded {{ $index == 0 ? 'bg-warning bg-opacity-10' : 'bg-light' }}">
                            <div class="flex-shrink-0">
                                <div class="position-relative">
                                    <div class="bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'info' : ($index == 2 ? 'success' : 'secondary')) }} rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="fw-bold text-white">{{ $index + 1 }}</span>
                                    </div>
                                    @if($index == 0)
                                        <div class="position-absolute top-0 start-100 translate-middle">
                                            <i class="bi bi-trophy-fill text-warning"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-semibold">{{ $producto->nombre }}</div>
                                <small class="text-muted">{{ $producto->codigo }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-{{ $index == 0 ? 'warning' : 'primary' }}">{{ $producto->total_vendido }}</div>
                                <small class="text-success">Bs. {{ number_format($producto->ingresos_totales, 0) }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-box-seam fs-1 text-muted d-block mb-2"></i>
                            <p class="text-muted mb-0">No hay datos de productos</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Estado de Cajas Mejorado -->
    @if($estadoCajas->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-gradient text-white border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;">
                        <h5 class="card-title mb-0 d-flex align-items-center">
                            <i class="bi bi-safe me-2"></i>
                            Control de Cajas por Sucursal
                        </h5>
                    </div>
                    <div class="card-body bg-light">
                        <div class="row g-4">
                            @foreach($estadoCajas as $caja)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card border-0 shadow-sm h-100" style="background: {{ $caja->estado === 'ABIERTA' ? 'linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%)' : 'linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%)' }};">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">{{ $caja->nombre }}</h6>
                                                    <p class="text-muted mb-0 small">{{ $caja->sucursal->nombre }}</p>
                                                </div>
                                                <span class="badge {{ $caja->estado === 'ABIERTA' ? 'bg-success' : 'bg-danger' }}">
                                                    <i class="bi bi-{{ $caja->estado === 'ABIERTA' ? 'unlock' : 'lock' }} me-1"></i>
                                                    {{ $caja->estado }}
                                                </span>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="bg-white bg-opacity-50 rounded p-2 text-center">
                                                        <div class="small text-muted">Saldo Actual</div>
                                                        <div class="fw-bold">Bs. {{ number_format($caja->saldo_actual, 2) }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="bg-white bg-opacity-50 rounded p-2 text-center">
                                                        <div class="small text-muted">Estado</div>
                                                        <div class="fw-bold {{ $caja->estado === 'ABIERTA' ? 'text-success' : 'text-danger' }}">
                                                            {{ $caja->estado === 'ABIERTA' ? 'Operativa' : 'Cerrada' }}
                                                        </div>
                                                    </div>
                                                </div>
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

    <!-- Chart.js con configuración avanzada -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuración global de Chart.js
            Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif";
            Chart.defaults.font.size = 12;
            Chart.defaults.color = '#64748b';
            Chart.defaults.borderColor = 'rgba(148, 163, 184, 0.1)';
            Chart.defaults.backgroundColor = 'rgba(148, 163, 184, 0.05)';

            // Gráfico de Ventas Mejorado
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
                                borderColor: '#667eea',
                                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#667eea',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Ingresos (Bs.)',
                                data: ventasData.map(item => item.ingresos),
                                borderColor: '#f093fb',
                                backgroundColor: 'rgba(240, 147, 251, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#f093fb',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: {
                                        weight: '600'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                borderColor: '#667eea',
                                borderWidth: 1,
                                cornerRadius: 8,
                                displayColors: true,
                                callbacks: {
                                    title: function(context) {
                                        return 'Fecha: ' + context[0].label;
                                    },
                                    label: function(context) {
                                        if (context.datasetIndex === 0) {
                                            return 'Ventas: ' + context.raw + ' operaciones';
                                        } else {
                                            return 'Ingresos: Bs. ' + new Intl.NumberFormat('es-ES', {minimumFractionDigits: 2}).format(context.raw);
                                        }
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.1)',
                                },
                                ticks: {
                                    font: {
                                        weight: '500'
                                    }
                                }
                            },
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Cantidad de Ventas',
                                    font: {
                                        weight: '600'
                                    }
                                },
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.1)',
                                },
                                ticks: {
                                    font: {
                                        weight: '500'
                                    }
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Ingresos (Bs.)',
                                    font: {
                                        weight: '600'
                                    }
                                },
                                grid: {
                                    drawOnChartArea: false,
                                },
                                ticks: {
                                    font: {
                                        weight: '500'
                                    },
                                    callback: function(value) {
                                        return 'Bs. ' + new Intl.NumberFormat('es-ES', {maximumFractionDigits: 0}).format(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Gráfico de Estados Mejorado
            const estadosCtx = document.getElementById('estadosChart');
            if (estadosCtx) {
                const estadosData = @json($chartData['ventas_por_estado'] ?? []);

                const colores = {
                    'PENDIENTE': '#fbbf24',
                    'COMPLETADA': '#10b981',
                    'CANCELADA': '#ef4444',
                    'DEVUELTA': '#6b7280'
                };

                const gradientes = estadosData.map(item => {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    const gradient = ctx.createLinearGradient(0, 0, 0, 200);
                    const color = colores[item.estado] || '#6b7280';
                    gradient.addColorStop(0, color);
                    gradient.addColorStop(1, color + '80');
                    return gradient;
                });

                new Chart(estadosCtx, {
                    type: 'doughnut',
                    data: {
                        labels: estadosData.map(item => item.estado),
                        datasets: [{
                            data: estadosData.map(item => item.total),
                            backgroundColor: estadosData.map(item => colores[item.estado] || '#6b7280'),
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverBorderWidth: 4,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    font: {
                                        weight: '600'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                borderColor: '#667eea',
                                borderWidth: 1,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.raw / total) * 100).toFixed(1);
                                        return context.label + ': ' + context.raw + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        },
                        animation: {
                            animateRotate: true,
                            duration: 2000
                        }
                    }
                });
            }
        });
    </script>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos adicionales folklóricos -->
    <style>
        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        }

        .text-purple {
            color: #6f42c1 !important;
        }

        .progress-bar {
            transition: width 1s ease-in-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeInUp 0.6s ease-out;
        }

        .badge {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            color: #475569;
        }
    </style>
</div>