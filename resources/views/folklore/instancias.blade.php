@extends('layouts.theme.modern-app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-warehouse me-2"></i>
                    Inventario Físico
                </h1>
                <p class="page-subtitle">Gestión de instancias físicas de trajes folklóricos</p>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-boxes fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">13</h5>
                                    <p class="text-muted mb-0">Trajes Disponibles</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-handshake fa-2x text-warning"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">0</h5>
                                    <p class="text-muted mb-0">En Alquiler</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-tools fa-2x text-info"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">0</h5>
                                    <p class="text-muted mb-0">En Mantenimiento</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-ban fa-2x text-danger"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">0</h5>
                                    <p class="text-muted mb-0">No Disponibles</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Inventario de Trajes Folklóricos</h3>
                    <div class="card-actions">
                        <button class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Registrar Nueva Instancia
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Módulo en Desarrollo:</strong> Este módulo para gestionar el inventario físico estará disponible próximamente.
                        <br><br>
                        <strong>Instancias actuales disponibles:</strong>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Conjunto</th>
                                    <th>Color</th>
                                    <th>Talla</th>
                                    <th>Estado</th>
                                    <th>Sucursal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>CHOL-R-S-001</code></td>
                                    <td>Cholita Paceña Completa</td>
                                    <td><span class="badge bg-danger">Rojo</span></td>
                                    <td>S</td>
                                    <td><span class="badge bg-success">Disponible</span></td>
                                    <td>Principal</td>
                                </tr>
                                <tr>
                                    <td><code>CHOL-R-S-002</code></td>
                                    <td>Cholita Paceña Completa</td>
                                    <td><span class="badge bg-danger">Rojo</span></td>
                                    <td>S</td>
                                    <td><span class="badge bg-success">Disponible</span></td>
                                    <td>Principal</td>
                                </tr>
                                <tr>
                                    <td><code>CAP-M-D-M-001</code></td>
                                    <td>Caporal Masculino</td>
                                    <td><span class="badge bg-warning">Dorado</span></td>
                                    <td>M</td>
                                    <td><span class="badge bg-success">Disponible</span></td>
                                    <td>Principal</td>
                                </tr>
                                <tr>
                                    <td><code>CAP-F-D-S-001</code></td>
                                    <td>Caporal Femenino</td>
                                    <td><span class="badge bg-warning">Dorado</span></td>
                                    <td>S</td>
                                    <td><span class="badge bg-success">Disponible</span></td>
                                    <td>Principal</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-ellipsis-h"></i>
                                        Y 9 instancias más...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection