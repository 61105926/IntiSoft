<div>
    <div class="container py-4">
        <!-- Header -->
        <div class="card mb-4 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body d-flex justify-content-between align-items-start flex-wrap text-white">
                <div>
                    <h1 class="h3 fw-bold mb-2">
                        <i class="fas fa-history me-2"></i>
                        Historial de Productos
                    </h1>
                    <p class="mb-3 opacity-75">Seguimiento completo y trazabilidad de movimientos de inventario folklórico</p>
                    <div class="d-flex gap-3 small flex-wrap opacity-75">
                        <span class="d-flex align-items-center gap-1">
                            <i class="bi bi-clock"></i>
                            Última actualización: {{ now()->format('d/m/Y H:i') }}
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <i class="bi bi-box-seam"></i>
                            {{ $movimientos->total() }} movimientos encontrados
                        </span>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap mt-3 mt-md-0">
                    <button wire:click="limpiarFiltros" class="btn btn-outline-light d-flex align-items-center gap-2">
                        <i class="bi bi-arrow-clockwise"></i>
                        Limpiar Filtros
                    </button>
                    <button wire:click="exportar" class="btn btn-light d-flex align-items-center gap-2">
                        <i class="bi bi-download"></i>
                        Exportar
                    </button>
                </div>
            </div>
        </div>

        <!-- Cards resumen -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body d-flex justify-content-between align-items-center text-white">
                        <div>
                            <p class="mb-1 small opacity-75">Total Movimientos</p>
                            <h4 class="fw-bold mb-0">{{ number_format($estadisticas['total']) }}</h4>
                            <small class="opacity-75">Hoy: {{ $estadisticas['hoy'] }}</small>
                        </div>
                        <i class="bi bi-clock-history fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body d-flex justify-content-between align-items-center text-white">
                        <div>
                            <p class="mb-1 small opacity-75">Alquileres</p>
                            <h4 class="fw-bold mb-0">{{ number_format($estadisticas['alquileres']) }}</h4>
                            <small class="opacity-75">{{ $estadisticas['alquileresPercent'] }}%</small>
                        </div>
                        <i class="bi bi-box-seam fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="card-body d-flex justify-content-between align-items-center text-white">
                        <div>
                            <p class="mb-1 small opacity-75">Ventas</p>
                            <h4 class="fw-bold mb-0">{{ number_format($estadisticas['ventas']) }}</h4>
                            <small class="opacity-75">{{ $estadisticas['ventasPercent'] }}%</small>
                        </div>
                        <i class="bi bi-graph-up fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <div class="card-body d-flex justify-content-between align-items-center text-white">
                        <div>
                            <p class="mb-1 small opacity-75">Devoluciones</p>
                            <h4 class="fw-bold mb-0">{{ number_format($estadisticas['devoluciones']) }}</h4>
                            <small class="opacity-75">{{ $estadisticas['devolucionesPercent'] }}%</small>
                        </div>
                        <i class="bi bi-arrow-down-up fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <div class="card-body d-flex justify-content-between align-items-center text-white">
                        <div>
                            <p class="mb-1 small opacity-75">Entradas</p>
                            <h4 class="fw-bold mb-0">{{ number_format($estadisticas['entradas']) }}</h4>
                            <small class="opacity-75">{{ $estadisticas['entradasPercent'] }}%</small>
                        </div>
                        <i class="bi bi-box2 fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                    <div class="card-body d-flex justify-content-between align-items-center text-dark">
                        <div>
                            <p class="mb-1 small">Productos Activos</p>
                            <h4 class="fw-bold mb-0">{{ number_format($estadisticas['productosActivos']) }}</h4>
                            <small class="text-muted">En inventario</small>
                        </div>
                        <i class="bi bi-check-circle fs-2 text-success opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                    <div class="card-body d-flex justify-content-between align-items-center text-dark">
                        <div>
                            <p class="mb-1 small">Valor Total Movimientos</p>
                            <h4 class="fw-bold mb-0">Bs. {{ number_format($estadisticas['valorTotal'], 2) }}</h4>
                            <small class="text-muted">Valor acumulado en período</small>
                        </div>
                        <i class="bi bi-bar-chart fs-2 text-warning opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros avanzados -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-funnel text-primary"></i>
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
                                placeholder="Producto, código, referencia..."
                                wire:model="searchTerm"
                            />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Producto</label>
                        <select class="form-select" wire:model="filterProducto">
                            <option value="">TODOS LOS PRODUCTOS</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}">{{ $producto->codigo }} - {{ $producto->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tipo de Movimiento</label>
                        <select class="form-select" wire:model="filterTipoMovimiento">
                            <option value="TODOS">TODOS</option>
                            <option value="ALQUILER">ALQUILER</option>
                            <option value="VENTA">VENTA</option>
                            <option value="DEVOLUCION">DEVOLUCIÓN</option>
                            <option value="ENTRADA">ENTRADA</option>
                            <option value="AJUSTE">AJUSTE</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sucursal</label>
                        <select class="form-select" wire:model="filterSucursal">
                            <option value="TODAS">TODAS</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Usuario</label>
                        <select class="form-select" wire:model="filterUsuario">
                            <option value="TODOS">TODOS</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Elementos por página</label>
                        <select class="form-select" wire:model="perPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha Desde</label>
                        <input type="date" class="form-control" wire:model="filterFechaDesde" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha Hasta</label>
                        <input type="date" class="form-control" wire:model="filterFechaHasta" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de movimientos -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history text-primary"></i>
                        Movimientos de Inventario Folklórico
                    </h5>
                    <small class="text-muted">
                        Mostrando {{ $movimientos->firstItem() ?? 0 }} - {{ $movimientos->lastItem() ?? 0 }} de {{ $movimientos->total() }} resultados
                    </small>
                </div>

                @if($movimientos->count() > 0)
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
                                    <th>Usuario</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($movimientos as $movimiento)
                                    <tr>
                                        <td>
                                            <div>
                                                <p class="mb-0 fw-medium">{{ $movimiento->fecha_movimiento ? \Carbon\Carbon::parse($movimiento->fecha_movimiento)->format('d/m/Y') : 'N/A' }}</p>
                                                <small class="text-muted">{{ $movimiento->fecha_movimiento ? \Carbon\Carbon::parse($movimiento->fecha_movimiento)->format('H:i:s') : '' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0 fw-medium">{{ $movimiento->producto->nombre ?? 'Producto eliminado' }}</p>
                                            <small class="text-muted">{{ $movimiento->producto->codigo ?? 'N/A' }}</small>
                                            @if($movimiento->producto && $movimiento->producto->categoria)
                                                <br><small class="text-muted">{{ $movimiento->producto->categoria->nombre }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match($movimiento->tipo_movimiento) {
                                                    'ALQUILER' => 'bg-primary',
                                                    'VENTA' => 'bg-success',
                                                    'DEVOLUCION' => 'bg-info',
                                                    'ENTRADA' => 'bg-warning text-dark',
                                                    'AJUSTE' => 'bg-secondary',
                                                    default => 'bg-dark'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $movimiento->tipo_movimiento }}</span>
                                        </td>
                                        <td>
                                            @if($movimiento->cantidad < 0)
                                                <span class="text-danger fw-bold">{{ $movimiento->cantidad }}</span>
                                            @else
                                                <span class="text-success fw-bold">+{{ $movimiento->cantidad }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $movimiento->stock_anterior ?? 'N/A' }}</span>
                                            →
                                            <span class="fw-medium">{{ $movimiento->stock_actual ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <p class="mb-0 fw-semibold">Bs. {{ number_format($movimiento->valor_unitario ?? 0, 2) }}</p>
                                            @if($movimiento->cantidad)
                                                <small class="text-muted">Total: Bs. {{ number_format(($movimiento->valor_unitario ?? 0) * abs($movimiento->cantidad), 2) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <p class="mb-0 fw-medium">{{ $movimiento->usuario->name ?? 'Usuario eliminado' }}</p>
                                            <small class="text-muted">{{ $movimiento->sucursal->nombre ?? 'Sucursal eliminada' }}</small>
                                        </td>
                                        <td>
                                            <button
                                                class="btn btn-sm btn-outline-primary"
                                                wire:click="verDetalles({{ $movimiento->id }})"
                                                title="Ver detalles"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-3">
                        {{ $movimientos->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h4 class="text-muted mt-3">No hay movimientos</h4>
                        <p class="text-muted">No se encontraron movimientos con los filtros aplicados.</p>
                        <button wire:click="limpiarFiltros" class="btn btn-primary">
                            <i class="bi bi-funnel"></i>
                            Limpiar Filtros
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de detalles -->
    @if($showDetailsModal && $selectedMovimiento)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-info-circle me-2"></i>
                            Detalles del Movimiento
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeDetailsModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">Información del Producto</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Código:</strong></td>
                                        <td>{{ $selectedMovimiento->producto->codigo ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nombre:</strong></td>
                                        <td>{{ $selectedMovimiento->producto->nombre ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Categoría:</strong></td>
                                        <td>{{ $selectedMovimiento->producto->categoria->nombre ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Información del Movimiento</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Fecha:</strong></td>
                                        <td>{{ $selectedMovimiento->fecha_movimiento ? \Carbon\Carbon::parse($selectedMovimiento->fecha_movimiento)->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tipo:</strong></td>
                                        <td><span class="badge bg-primary">{{ $selectedMovimiento->tipo_movimiento }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cantidad:</strong></td>
                                        <td>{{ $selectedMovimiento->cantidad }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Valor Unitario:</strong></td>
                                        <td>Bs. {{ number_format($selectedMovimiento->valor_unitario ?? 0, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6 class="text-primary">Stock</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Stock Anterior:</strong></td>
                                        <td>{{ $selectedMovimiento->stock_anterior ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stock Actual:</strong></td>
                                        <td>{{ $selectedMovimiento->stock_actual ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Usuario y Sucursal</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Usuario:</strong></td>
                                        <td>{{ $selectedMovimiento->usuario->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sucursal:</strong></td>
                                        <td>{{ $selectedMovimiento->sucursal->nombre ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($selectedMovimiento->observaciones)
                            <div class="mt-3">
                                <h6 class="text-primary">Observaciones</h6>
                                <p class="border p-2 bg-light rounded">{{ $selectedMovimiento->observaciones }}</p>
                            </div>
                        @endif

                        @if($selectedMovimiento->referencia)
                            <div class="mt-3">
                                <h6 class="text-primary">Referencia</h6>
                                <p class="border p-2 bg-light rounded">{{ $selectedMovimiento->referencia }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeDetailsModal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    @push('scripts')
    <script>
        window.addEventListener('swal', event => {
            Swal.fire(event.detail);
        });
    </script>
    @endpush
</div>