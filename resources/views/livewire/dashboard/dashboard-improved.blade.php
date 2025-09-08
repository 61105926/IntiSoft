<div>
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="text-dark fw-bold mb-1">
                        <i class="bi bi-speedometer2 text-warning me-2"></i>
                        Dashboard Folklore Trajes Típicos
                    </h2>
                    <p class="text-muted mb-0">Panel de control empresarial</p>
                </div>
                <div class="text-end">
                    <div class="text-muted small">Actualizado</div>
                    <div class="fw-semibold">{{ now()->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros Mejorados -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label for="fechaInicio" class="form-label fw-semibold">
                        <i class="bi bi-calendar3 me-1"></i>Fecha Inicio
                    </label>
                    <input type="date" wire:model.live="fechaInicio" id="fechaInicio" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="fechaFin" class="form-label fw-semibold">
                        <i class="bi bi-calendar3 me-1"></i>Fecha Fin
                    </label>
                    <input type="date" wire:model.live="fechaFin" id="fechaFin" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="sucursalId" class="form-label fw-semibold">
                        <i class="bi bi-building me-1"></i>Sucursal
                    </label>
                    <select wire:model.live="sucursalId" id="sucursalId" class="form-select">
                        <option value="">🏢 Todas las sucursales</option>
                        @foreach(\App\Models\Sucursal::all() as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="setToday()">Hoy</button>
                        <button class="btn btn-outline-info btn-sm" onclick="setThisMonth()">Este Mes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen Financiero Principal -->
    <div class="card border-0 shadow-lg mb-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body text-white p-4">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="text-white mb-3">
                        <i class="bi bi-cash-stack me-2"></i>
                        Resumen Financiero del Período
                    </h3>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-30 rounded-circle p-2 me-3">
                                        <i class="bi bi-cart-check text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-white-50 small">Ventas</div>
                                        <div class="fw-bold fs-6">Bs. {{ number_format($metricas['ingresos_periodo'] ?? 0, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-30 rounded-circle p-2 me-3">
                                        <i class="bi bi-calendar-heart text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-white-50 small">Reservas</div>
                                        <div class="fw-bold fs-6">Bs. {{ number_format($metricas['ingresos_reservas_periodo'] ?? 0, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-30 rounded-circle p-2 me-3">
                                        <i class="bi bi-house-door text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-white-50 small">Alquileres</div>
                                        <div class="fw-bold fs-6">Bs. {{ number_format($metricas['ingresos_alquileres_periodo'] ?? 0, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="bg-white bg-opacity-20 rounded-3 p-4 h-100 d-flex flex-column justify-content-center">
                        <h6 class="text-white mb-2">💰 Total de Ingresos</h6>
                        <h2 class="text-white fw-bold mb-2">Bs. {{ number_format($metricas['ingresos_total'] ?? 0, 2) }}</h2>
                        <div class="text-white-50 small">
                            <i class="bi bi-arrow-up-circle me-1"></i>
                            Hoy: Bs. {{ number_format($metricas['ingresos_total_hoy'] ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs Principales -->
    <div class="row g-4 mb-5">
        <!-- Ventas Hoy -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hover-lift h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-warning bg-opacity-15 rounded-circle p-2 me-3">
                                    <i class="bi bi-graph-up-arrow text-warning"></i>
                                </div>
                                <h6 class="text-muted mb-0">Ventas Hoy</h6>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">{{ $metricas['ventas_hoy'] ?? 0 }}</h3>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success-soft text-success">
                                    <i class="bi bi-currency-dollar me-1"></i>
                                    Bs. {{ number_format($metricas['ingresos_hoy'] ?? 0, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="text-warning">
                            <i class="bi bi-cart-plus fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clientes Totales -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hover-lift h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-info bg-opacity-15 rounded-circle p-2 me-3">
                                    <i class="bi bi-people-fill text-info"></i>
                                </div>
                                <h6 class="text-muted mb-0">Clientes</h6>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">{{ $metricas['total_clientes'] ?? 0 }}</h3>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-info-soft text-info">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    +{{ $metricas['clientes_nuevos'] ?? 0 }} nuevos
                                </span>
                            </div>
                        </div>
                        <div class="text-info">
                            <i class="bi bi-person-hearts fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos Total -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hover-lift h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-success bg-opacity-15 rounded-circle p-2 me-3">
                                    <i class="bi bi-box-seam text-success"></i>
                                </div>
                                <h6 class="text-muted mb-0">Inventario</h6>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">{{ $metricas['total_productos'] ?? 0 }}</h3>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success-soft text-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Productos disponibles
                                </span>
                            </div>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-boxes fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado Cajas -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hover-lift h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary bg-opacity-15 rounded-circle p-2 me-3">
                                    <i class="bi bi-cash-stack text-primary"></i>
                                </div>
                                <h6 class="text-muted mb-0">Cajas Abiertas</h6>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">{{ $estadoCajas->where('estado', 'ABIERTA')->count() ?? 0 }}</h3>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary-soft text-primary">
                                    <i class="bi bi-safe me-1"></i>
                                    de {{ $estadoCajas->count() ?? 0 }} total
                                </span>
                            </div>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-safe2 fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Desglose Detallado por Categorías -->
    <div class="row g-4 mb-5">
        <!-- Reservas Detalle -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(45deg, #6f42c1, #9c27b0) !important;">
                    <h6 class="mb-0">
                        <i class="bi bi-calendar-event me-2"></i>
                        Reservas del Período
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 class="text-purple fw-bold">{{ $metricas['reservas_periodo'] ?? 0 }}</h4>
                        <small class="text-muted">reservas registradas</small>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-2 text-center">
                                <div class="fw-bold text-success">{{ $metricas['reservas_activas'] ?? 0 }}</div>
                                <small class="text-success">Activas</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-2 text-center">
                                <div class="fw-bold text-info">Bs. {{ number_format($metricas['ingresos_reservas_hoy'] ?? 0, 2) }}</div>
                                <small class="text-info">Hoy</small>
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Efectivo recibido:</span>
                            <span class="fw-semibold">Bs. {{ number_format($metricas['ingresos_reservas_periodo'] ?? 0, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Total estimado:</span>
                            <span class="fw-semibold text-success">Bs. {{ number_format($metricas['total_estimado_reservas'] ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alquileres Detalle -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(45deg, #ff9800, #f57c00) !important;">
                    <h6 class="mb-0">
                        <i class="bi bi-house-heart me-2"></i>
                        Alquileres del Período
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 class="text-warning fw-bold">{{ $metricas['alquileres_periodo'] ?? 0 }}</h4>
                        <small class="text-muted">alquileres registrados</small>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-2 text-center">
                                <div class="fw-bold text-success">{{ $metricas['alquileres_activos'] ?? 0 }}</div>
                                <small class="text-success">Activos</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-2 text-center">
                                <div class="fw-bold text-info">Bs. {{ number_format($metricas['ingresos_alquileres_hoy'] ?? 0, 2) }}</div>
                                <small class="text-info">Hoy</small>
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Anticipos:</span>
                            <span class="fw-semibold text-success">Bs. {{ number_format($metricas['anticipos_alquileres'] ?? 0, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Saldos pendientes:</span>
                            <span class="fw-semibold text-danger">Bs. {{ number_format($metricas['saldos_pendientes_alquileres'] ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas Detalle -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(45deg, #28a745, #20c997) !important;">
                    <h6 class="mb-0">
                        <i class="bi bi-bag-check me-2"></i>
                        Ventas del Período
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 class="text-success fw-bold">{{ $metricas['ventas_periodo'] ?? 0 }}</h4>
                        <small class="text-muted">ventas registradas</small>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-2 text-center">
                                <div class="fw-bold text-success">{{ $metricas['ventas_completadas'] ?? 0 }}</div>
                                <small class="text-success">Completadas</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-2 text-center">
                                <div class="fw-bold text-info">{{ $metricas['ventas_hoy'] ?? 0 }}</div>
                                <small class="text-info">Hoy</small>
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Ingresos período:</span>
                            <span class="fw-semibold">Bs. {{ number_format($metricas['ingresos_periodo'] ?? 0, 2) }}</span>
                        </div>
                        @php
                            $ventasCompletadas = $metricas['ventas_completadas'] ?? 0;
                            $ingresosPeriodo = $metricas['ingresos_periodo'] ?? 0;
                            $promedio = $ventasCompletadas > 0 ? $ingresosPeriodo / $ventasCompletadas : 0;
                        @endphp
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Promedio por venta:</span>
                            <span class="fw-semibold text-success">Bs. {{ number_format($promedio, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tablas de Información -->
    <div class="row g-4">
        <!-- Ventas Recientes -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2 text-primary"></i>
                        Actividad Reciente
                    </h5>
                    <a href="/venta" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye me-1"></i>Ver todas
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-semibold">Número</th>
                                    <th class="border-0 fw-semibold">Cliente</th>
                                    <th class="border-0 fw-semibold">Fecha</th>
                                    <th class="border-0 fw-semibold">Estado</th>
                                    <th class="border-0 fw-semibold text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ventasRecientes as $venta)
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                    <i class="bi bi-receipt text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $venta->numero_venta }}</div>
                                                    <small class="text-muted">{{ $venta->sucursal->nombre }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="fw-medium">{{ $venta->cliente->nombre }}</div>
                                        </td>
                                        <td class="align-middle">
                                            <div>{{ $venta->fecha_venta->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $venta->fecha_venta->format('H:i') }}</small>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge {{ $venta->estado_badge_class }}">
                                                {{ $venta->estado_display }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="fw-bold text-success">Bs. {{ number_format($venta->total, 2) }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox fs-1"></i>
                                                <p class="mb-0 mt-2">No hay ventas recientes</p>
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

        <!-- Productos Más Vendidos -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-trophy me-2 text-warning"></i>
                        Top Productos
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($productosPopulares as $index => $producto)
                        <div class="d-flex align-items-center mb-3 p-2 rounded {{ $index < 3 ? 'bg-light' : '' }}">
                            <div class="flex-shrink-0">
                                <div class="position-relative">
                                    @if($index == 0)
                                        <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="bi bi-trophy-fill text-white"></i>
                                        </div>
                                    @elseif($index == 1)
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="bi bi-award-fill text-white"></i>
                                        </div>
                                    @elseif($index == 2)
                                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="bi bi-star-fill text-white"></i>
                                        </div>
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <span class="fw-bold text-muted">{{ $index + 1 }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-medium">{{ $producto->nombre }}</div>
                                <small class="text-muted">{{ $producto->codigo }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary">{{ $producto->total_vendido }}</div>
                                <small class="text-muted">Bs. {{ number_format($producto->ingresos_totales, 0) }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-box-seam fs-1 text-muted"></i>
                            <p class="text-muted mb-0 mt-2">No hay datos disponibles</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Estado de Cajas Expandido -->
    @if($estadoCajas->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-safe me-2 text-success"></i>
                            Estado de Cajas por Sucursal
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            @foreach($estadoCajas as $caja)
                                <div class="col-lg-4 col-md-6">
                                    <div class="card border {{ $caja->estado === 'ABIERTA' ? 'border-success' : 'border-secondary' }} h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-1">{{ $caja->nombre }}</h6>
                                                    <p class="text-muted mb-0 small">{{ $caja->sucursal->nombre }}</p>
                                                </div>
                                                <span class="badge {{ $caja->estado === 'ABIERTA' ? 'bg-success' : 'bg-secondary' }}">
                                                    <i class="bi bi-{{ $caja->estado === 'ABIERTA' ? 'unlock' : 'lock' }} me-1"></i>
                                                    {{ $caja->estado }}
                                                </span>
                                            </div>
                                            
                                            <div class="bg-light rounded p-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted small">💰 Saldo Actual:</span>
                                                    <span class="fw-bold fs-6 {{ $caja->saldo_actual > 0 ? 'text-success' : 'text-muted' }}">
                                                        Bs. {{ number_format($caja->saldo_actual, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            @if($caja->estado === 'ABIERTA')
                                                <div class="mt-2">
                                                    <small class="text-success">
                                                        <i class="bi bi-check-circle-fill me-1"></i>
                                                        Operativa y disponible
                                                    </small>
                                                </div>
                                            @else
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <i class="bi bi-x-circle-fill me-1"></i>
                                                        Cerrada - No disponible
                                                    </small>
                                                </div>
                                            @endif
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

    <!-- Bootstrap Icons y estilos personalizados -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .hover-lift {
            transition: all 0.2s ease-in-out;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .bg-success-soft { background-color: #d1e7dd !important; }
        .bg-info-soft { background-color: #d1ecf1 !important; }
        .bg-primary-soft { background-color: #cfe2ff !important; }
        
        .text-purple { color: #6f42c1 !important; }
        
        .card-header.bg-gradient {
            border: none;
        }
        
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        .badge {
            font-weight: 600;
            padding: 0.5em 0.75em;
        }
    </style>

    <!-- Scripts para filtros rápidos -->
    <script>
        function setToday() {
            const today = new Date().toISOString().split('T')[0];
            @this.set('fechaInicio', today);
            @this.set('fechaFin', today);
        }
        
        function setThisMonth() {
            const now = new Date();
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
            const today = now.toISOString().split('T')[0];
            @this.set('fechaInicio', firstDay);
            @this.set('fechaFin', today);
        }
    </script>
</div>