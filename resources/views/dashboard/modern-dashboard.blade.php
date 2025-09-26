@extends('layouts.theme.modern-app')

@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4">
    <!-- Stats Cards -->
    <div class="col-12">
        <div class="row g-4">
            <!-- Ventas del Día -->
            <div class="col-lg-3 col-md-6">
                <div class="modern-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 fw-medium">Ventas del Día</p>
                                <h3 class="mb-1 fw-bold text-dark">Bs. {{ number_format($ventasHoy, 2) }}</h3>
                                <p class="mb-0 {{ $porcentajeVentas >= 0 ? 'text-success' : 'text-danger' }} fw-medium">
                                    <i class="fas fa-arrow-{{ $porcentajeVentas >= 0 ? 'up' : 'down' }} me-1"></i>
                                    {{ $porcentajeVentas >= 0 ? '+' : '' }}{{ number_format($porcentajeVentas, 1) }}% vs ayer
                                </p>
                            </div>
                            <div class="bg-light rounded-circle p-3">
                                <i class="fas fa-shopping-cart text-success" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alquileres Activos -->
            <div class="col-lg-3 col-md-6">
                <div class="modern-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 fw-medium">Alquileres Activos</p>
                                <h3 class="mb-1 fw-bold text-dark">{{ $alquileresActivos }}</h3>
                                <p class="mb-0 text-info fw-medium">
                                    <i class="fas fa-clock me-1"></i>{{ $alquileresPorVencer }} por vencer
                                </p>
                            </div>
                            <div class="bg-light rounded-circle p-3">
                                <i class="fas fa-handshake text-info" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Productos en Stock -->
            <div class="col-lg-3 col-md-6">
                <div class="modern-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 fw-medium">Productos en Stock</p>
                                <h3 class="mb-1 fw-bold text-dark">{{ $productosEnStock }}</h3>
                                <p class="mb-0 {{ $stockBajo > 0 ? 'text-warning' : 'text-success' }} fw-medium">
                                    <i class="fas fa-{{ $stockBajo > 0 ? 'exclamation-triangle' : 'check-circle' }} me-1"></i>
                                    {{ $stockBajo }} stock bajo
                                </p>
                            </div>
                            <div class="bg-light rounded-circle p-3">
                                <i class="fas fa-boxes text-primary" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ingresos del Mes -->
            <div class="col-lg-3 col-md-6">
                <div class="modern-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 fw-medium">Ingresos del Mes</p>
                                <h3 class="mb-1 fw-bold text-dark">Bs. {{ number_format($ingresosMes, 2) }}</h3>
                                <p class="mb-0 {{ $porcentajeIngresos >= 0 ? 'text-success' : 'text-danger' }} fw-medium">
                                    <i class="fas fa-arrow-{{ $porcentajeIngresos >= 0 ? 'up' : 'down' }} me-1"></i>
                                    {{ $porcentajeIngresos >= 0 ? '+' : '' }}{{ number_format($porcentajeIngresos, 1) }}% vs mes anterior
                                </p>
                            </div>
                            <div class="bg-light rounded-circle p-3">
                                <i class="fas fa-chart-line text-success" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="col-12">
        <div class="row g-4">
            <!-- Ventas Chart -->
            <div class="col-lg-8">
                <div class="modern-card">
                    <div class="card-header bg-white border-bottom-0 p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0 fw-bold">Ventas de los Últimos 7 Días</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Últimos 7 días
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Últimos 7 días</a></li>
                                    <li><a class="dropdown-item" href="#">Últimos 30 días</a></li>
                                    <li><a class="dropdown-item" href="#">Últimos 3 meses</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Placeholder for chart -->
                        <div class="bg-light rounded p-5 text-center" style="height: 300px;">
                            <i class="fas fa-chart-area text-muted mb-3" style="font-size: 48px;"></i>
                            <p class="text-muted">Gráfico de ventas - Chart.js será implementado aquí</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4">
                <div class="modern-card">
                    <div class="card-header bg-white border-bottom-0 p-4">
                        <h5 class="card-title mb-0 fw-bold">Acciones Rápidas</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-3">
                            <a href="{{ route('venta') }}" class="btn btn-outline-success d-flex align-items-center">
                                <i class="fas fa-plus me-2"></i>
                                Nueva Venta
                            </a>
                            <a href="{{ route('alquiler') }}" class="btn btn-outline-info d-flex align-items-center">
                                <i class="fas fa-handshake me-2"></i>
                                Nuevo Alquiler
                            </a>
                            <a href="{{ route('producto') }}" class="btn btn-outline-primary d-flex align-items-center">
                                <i class="fas fa-boxes me-2"></i>
                                Agregar Producto
                            </a>
                            <a href="{{ route('cliente') }}" class="btn btn-outline-secondary d-flex align-items-center">
                                <i class="fas fa-user-plus me-2"></i>
                                Nuevo Cliente
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions and Low Stock -->
    <div class="col-12">
        <div class="row g-4">
            <!-- Recent Transactions -->
            <div class="col-lg-7">
                <div class="modern-card">
                    <div class="card-header bg-white border-bottom-0 p-4">
                        <h5 class="card-title mb-0 fw-bold">Transacciones Recientes</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Tipo</th>
                                        <th class="border-0 fw-semibold">Cliente</th>
                                        <th class="border-0 fw-semibold">Monto</th>
                                        <th class="border-0 fw-semibold">Estado</th>
                                        <th class="border-0 fw-semibold">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaccionesRecientes as $transaccion)
                                        <tr>
                                            <td>
                                                <span class="badge bg-{{ $transaccion['tipo_class'] }}-subtle text-{{ $transaccion['tipo_class'] }}">
                                                    <i class="{{ $transaccion['tipo_icon'] }} me-1"></i>{{ $transaccion['tipo'] }}
                                                </span>
                                            </td>
                                            <td>{{ $transaccion['cliente'] }}</td>
                                            <td class="fw-semibold">Bs. {{ number_format($transaccion['monto'], 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $transaccion['estado'] === 'COMPLETADA' || $transaccion['estado'] === 'ACTIVO' ? 'success' : ($transaccion['estado'] === 'PENDIENTE' ? 'warning' : 'info') }}">
                                                    {{ ucfirst(strtolower($transaccion['estado'])) }}
                                                </span>
                                            </td>
                                            <td class="text-muted">{{ $transaccion['fecha']->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                No hay transacciones recientes
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="col-lg-5">
                <div class="modern-card">
                    <div class="card-header bg-white border-bottom-0 p-4">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Stock Bajo
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @forelse($productosStockBajo as $stock)
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                        <div>
                                            <h6 class="mb-1">{{ $stock->producto->nombre ?? 'Producto N/A' }}</h6>
                                            <small class="text-muted">Código: {{ $stock->producto->codigo ?? 'N/A' }}</small>
                                        </div>
                                        <span class="badge bg-{{ $stock->stock_actual == 0 ? 'danger' : 'warning' }}">
                                            {{ $stock->stock_actual }} {{ $stock->stock_actual == 1 ? 'unidad' : 'unidades' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-check-circle fa-2x mb-2 text-success"></i><br>
                                        <strong>¡Perfecto!</strong><br>
                                        No hay productos con stock bajo
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('producto') }}" class="btn btn-outline-primary w-100">
                                Ver Todo el Inventario
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }

    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }

    .bg-info-subtle {
        background-color: rgba(13, 202, 240, 0.1) !important;
    }

    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .btn:hover {
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
</style>
@endpush
@endsection