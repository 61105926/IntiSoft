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
                                <h3 class="mb-1 fw-bold text-dark">Bs. 2,450</h3>
                                <p class="mb-0 text-success fw-medium">
                                    <i class="fas fa-arrow-up me-1"></i>+12% vs ayer
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
                                <h3 class="mb-1 fw-bold text-dark">23</h3>
                                <p class="mb-0 text-info fw-medium">
                                    <i class="fas fa-clock me-1"></i>5 por vencer
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
                                <h3 class="mb-1 fw-bold text-dark">156</h3>
                                <p class="mb-0 text-warning fw-medium">
                                    <i class="fas fa-exclamation-triangle me-1"></i>8 stock bajo
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
                                <h3 class="mb-1 fw-bold text-dark">Bs. 48,320</h3>
                                <p class="mb-0 text-success fw-medium">
                                    <i class="fas fa-arrow-up me-1"></i>+18% vs mes anterior
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
                                    <tr>
                                        <td>
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="fas fa-shopping-cart me-1"></i>Venta
                                            </span>
                                        </td>
                                        <td>María González</td>
                                        <td class="fw-semibold">Bs. 450</td>
                                        <td><span class="badge bg-success">Completado</span></td>
                                        <td class="text-muted">Hace 2 min</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="fas fa-handshake me-1"></i>Alquiler
                                            </span>
                                        </td>
                                        <td>Juan Pérez</td>
                                        <td class="fw-semibold">Bs. 200</td>
                                        <td><span class="badge bg-warning">Pendiente</span></td>
                                        <td class="text-muted">Hace 15 min</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">
                                                <i class="fas fa-calendar me-1"></i>Reserva
                                            </span>
                                        </td>
                                        <td>Ana López</td>
                                        <td class="fw-semibold">Bs. 120</td>
                                        <td><span class="badge bg-info">Confirmado</span></td>
                                        <td class="text-muted">Hace 1 hora</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="fas fa-shopping-cart me-1"></i>Venta
                                            </span>
                                        </td>
                                        <td>Carlos Mamani</td>
                                        <td class="fw-semibold">Bs. 380</td>
                                        <td><span class="badge bg-success">Completado</span></td>
                                        <td class="text-muted">Hace 2 horas</td>
                                    </tr>
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
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                    <div>
                                        <h6 class="mb-1">Pollera Tradicional</h6>
                                        <small class="text-muted">Código: PLT-001</small>
                                    </div>
                                    <span class="badge bg-warning">2 unidades</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                    <div>
                                        <h6 class="mb-1">Sombrero de Cholita</h6>
                                        <small class="text-muted">Código: SOM-003</small>
                                    </div>
                                    <span class="badge bg-warning">1 unidad</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                    <div>
                                        <h6 class="mb-1">Mantilla Bordada</h6>
                                        <small class="text-muted">Código: MAN-007</small>
                                    </div>
                                    <span class="badge bg-danger">0 unidades</span>
                                </div>
                            </div>
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