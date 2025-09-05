<div>
    <div class="container py-4">
        <!-- Header -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <h1 class="h3 fw-bold mb-2">Historial de Productos</h1>
                    <p class="text-muted mb-3">Seguimiento completo y trazabilidad de movimientos de inventario</p>
                    <div class="d-flex gap-3 text-muted small flex-wrap">
                        <span class="d-flex align-items-center gap-1">
                            <i class="bi bi-clock"></i>
                            Última actualización: 05/09/2025 09:15
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <i class="bi bi-box-seam"></i>
                            1 movimiento encontrado
                        </span>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap mt-3 mt-md-0">
                    <button class="btn btn-outline-secondary d-flex align-items-center gap-2">
                        <i class="bi bi-arrow-clockwise"></i>
                        Limpiar Filtros
                    </button>
                    <button class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="bi bi-download"></i>
                        Exportar
                    </button>
                </div>
            </div>
        </div>

        <!-- Cards resumen -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-primary shadow-sm text-primary bg-light">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small">Total Movimientos</p>
                            <h4 class="fw-bold mb-0">8</h4>
                            <small>Hoy: 0</small>
                        </div>
                        <i class="bi bi-clock-history fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-purple shadow-sm text-purple bg-light">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small">Alquileres</p>
                            <h4 class="fw-bold mb-0">1</h4>
                            <small>12.5%</small>
                        </div>
                        <i class="bi bi-box-seam fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-success shadow-sm text-success bg-light">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small">Ventas</p>
                            <h4 class="fw-bold mb-0">1</h4>
                            <small>12.5%</small>
                        </div>
                        <i class="bi bi-graph-up fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-info shadow-sm text-info bg-light">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small">Devoluciones</p>
                            <h4 class="fw-bold mb-0">1</h4>
                            <small>12.5%</small>
                        </div>
                        <i class="bi bi-arrow-down-up fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-indigo shadow-sm text-indigo bg-light">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small">Entradas</p>
                            <h4 class="fw-bold mb-0">1</h4>
                            <small>12.5%</small>
                        </div>
                        <i class="bi bi-box2 fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-warning shadow-sm text-warning bg-light">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small">Productos Activos</p>
                            <h4 class="fw-bold mb-0">2</h4>
                            <small>En uso/reserva</small>
                        </div>
                        <i class="bi bi-exclamation-circle fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card border-success shadow-sm text-success bg-light">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small">Valor Total Movimientos</p>
                            <h4 class="fw-bold mb-0">Bs. 3,390</h4>
                            <small>Valor acumulado</small>
                        </div>
                        <i class="bi bi-bar-chart fs-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros avanzados -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-funnel"></i>
                    Filtros Avanzados
                </h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Búsqueda General</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Producto, código, referencia, cliente..."
                            />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Producto</label>
                        <select class="form-select">
                            <option>TF001 - Traje Caporales Masculino</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tipo de Movimiento</label>
                        <select class="form-select">
                            <option>ALQUILER</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado Actual</label>
                        <select class="form-select">
                            <option>TODOS</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sucursal</label>
                        <select class="form-select">
                            <option>TODAS</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Usuario</label>
                        <select class="form-select">
                            <option>TODOS</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Prioridad</label>
                        <select class="form-select">
                            <option>TODAS</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Elementos por página</label>
                        <select class="form-select">
                            <option>10</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha Desde</label>
                        <input type="date" class="form-control" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha Hasta</label>
                        <input type="date" class="form-control" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de movimientos -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history"></i>
                        Movimientos de Inventario
                    </h5>
                    <small class="text-muted">Mostrando 1 - 1 de 1 resultados</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Producto</th>
                                <th>Tipo</th>
                                <th>Cantidad</th>
                                <th>Stock</th>
                                <th>Valor</th>
                                <th>Estado</th>
                                <th>Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div>
                                        <p class="mb-0">22/07/2024</p>
                                        <small class="text-muted">10:30:00</small>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 fw-medium">Traje Caporales Masculino</p>
                                    <small class="text-muted">TF001</small>
                                    <br />
                                    <small class="text-muted">Trajes Folklóricos</small>
                                </td>
                                <td><span class="badge bg-primary">Alquiler</span></td>
                                <td><span class="text-danger fw-bold">-2</span></td>
                                <td>
                                    <span class="text-muted">15</span>
                                    →
                                    <span class="fw-medium">13</span>
                                </td>
                                <td>
                                    <p class="mb-0 fw-semibold">Bs. 300.00</p>
                                    <small class="text-muted">Unit: Bs. 150.00</small>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark">Alquilado</span>
                                    <br />
                                    <small class="text-warning">
                                        <i class="bi bi-clock"></i>
                                        28/07
                                    </small>
                                </td>
                                <td>
                                    <p class="mb-0 fw-medium">Luis Pérez</p>
                                    <small class="text-muted">Sucursal Centro</small>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
