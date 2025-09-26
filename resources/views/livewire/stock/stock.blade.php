<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-gradient fw-bold">
            <i class="fas fa-boxes me-2"></i>Control de Stock por Sucursal
        </h2>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-warning" wire:click="openAlertasModal">
                <i class="fas fa-exclamation-triangle me-2"></i>Alertas
                @if(count($alertas['stock_agotado']) + count($alertas['stock_bajo']) > 0)
                    <span class="badge bg-danger">{{ count($alertas['stock_agotado']) + count($alertas['stock_bajo']) }}</span>
                @endif
            </button>
            <button type="button" class="btn btn-info" wire:click="exportarStock">
                <i class="fas fa-file-excel me-2"></i>Exportar
            </button>
            <button type="button" class="btn btn-secondary" wire:click="generarReporteRotacion">
                <i class="fas fa-chart-line me-2"></i>Rotación
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas de Stock -->
    <div class="row row-cols-1 row-cols-md-4 g-4 mb-4">
        <div class="col">
            <div class="modern-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Productos</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['total_productos'] }}</p>
                    </div>
                    <i class="fas fa-cubes text-primary fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="modern-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Stock Total</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['stock_total'] }}</p>
                        <small class="text-success">Disponible: {{ $estadisticas['stock_disponible'] }}</small>
                    </div>
                    <i class="fas fa-warehouse text-success fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="modern-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">En Uso</p>
                        <p class="fs-3 fw-bold mb-0">{{ $estadisticas['stock_alquilado'] + $estadisticas['stock_en_eventos'] }}</p>
                        <small class="text-info">Alq: {{ $estadisticas['stock_alquilado'] }} | Evt: {{ $estadisticas['stock_en_eventos'] }}</small>
                    </div>
                    <i class="fas fa-handshake text-info fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="modern-card border-warning">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Alertas</p>
                        <p class="fs-3 fw-bold mb-0 text-warning">{{ $estadisticas['productos_agotados'] + $estadisticas['productos_bajo_stock'] }}</p>
                        <small class="text-danger">Agotados: {{ $estadisticas['productos_agotados'] }}</small>
                    </div>
                    <i class="fas fa-exclamation-triangle text-warning fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda fila de estadísticas -->
    <div class="row row-cols-1 row-cols-md-4 g-4 mb-4">
        <div class="col">
            <div class="modern-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Reservado</p>
                        <p class="fs-3 fw-bold mb-0 text-warning">{{ $estadisticas['stock_reservado'] }}</p>
                    </div>
                    <i class="fas fa-bookmark text-warning fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="modern-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Mantenimiento</p>
                        <p class="fs-3 fw-bold mb-0 text-danger">{{ $estadisticas['stock_mantenimiento'] }}</p>
                    </div>
                    <i class="fas fa-tools text-danger fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="modern-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Valor Total</p>
                        <p class="fs-4 fw-bold mb-0 text-success">Bs. {{ number_format($this->valorTotalInventario, 2) }}</p>
                    </div>
                    <i class="fas fa-dollar-sign text-success fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="modern-card">
                <div class="card-body text-center">
                    <div class="progress mb-2" style="height: 20px;">
                        @php
                            $porcentajeDisponible = $estadisticas['stock_total'] > 0 ? ($estadisticas['stock_disponible'] / $estadisticas['stock_total']) * 100 : 0;
                            $porcentajeEnUso = $estadisticas['stock_total'] > 0 ? (($estadisticas['stock_alquilado'] + $estadisticas['stock_en_eventos']) / $estadisticas['stock_total']) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-success" style="width: {{ $porcentajeDisponible }}%" title="Disponible {{ number_format($porcentajeDisponible, 1) }}%"></div>
                        <div class="progress-bar bg-info" style="width: {{ $porcentajeEnUso }}%" title="En Uso {{ number_format($porcentajeEnUso, 1) }}%"></div>
                    </div>
                    <small class="text-muted">Estado General del Stock</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="modern-card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar producto..."
                               wire:model.debounce.300ms="searchTerm">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model="filterSucursal">
                        <option value="TODAS">Todas las Sucursales</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model="filterEstado">
                        <option value="TODOS">Todos los Estados</option>
                        <option value="ACTIVO">Activos</option>
                        <option value="INACTIVO">Inactivos</option>
                        <option value="MANTENIMIENTO">Mantenimiento</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model="filterAlerta">
                        <option value="TODAS">Todas las Alertas</option>
                        <option value="AGOTADO">Stock Agotado</option>
                        <option value="BAJO">Stock Bajo</option>
                        <option value="EXCESIVO">Stock Excesivo</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="btn-group w-100" role="group">
                        <button type="button" class="btn btn-outline-primary" title="Actualizar Vista" wire:click="$refresh">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" title="Limpiar Filtros"
                                wire:click="resetFilters">
                            <i class="fas fa-eraser"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Stock -->
    <div class="modern-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Sucursal</th>
                            <th class="text-center">Stock Total</th>
                            <th class="text-center">Disponible</th>
                            <th class="text-center">Reservado</th>
                            <th class="text-center">Alquilado</th>
                            <th class="text-center">En Eventos</th>
                            <th class="text-center">Mantenimiento</th>
                            <th class="text-center">Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stocks as $stock)
                            <tr class="@if($stock->stock_disponible <= 0) table-danger @elseif($stock->stock_disponible <= $stock->stock_minimo) table-warning @endif">
                                <td>
                                    <div>
                                        <strong>{{ $stock->producto->nombre }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $stock->producto->codigo }}</small>
                                        <br>
                                        <span class="badge bg-secondary">{{ $stock->producto->tipo_vestimenta ?? 'N/A' }}</span>
                                        @if($stock->producto->talla)
                                            <span class="badge bg-info">{{ $stock->producto->talla }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $stock->sucursal->nombre }}</strong>
                                    @if($stock->ubicacion_fisica)
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $stock->ubicacion_fisica }}
                                        </small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold fs-5">{{ $stock->stock_total }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold fs-5 @if($stock->stock_disponible <= 0) text-danger @elseif($stock->stock_disponible <= $stock->stock_minimo) text-warning @else text-success @endif">
                                        {{ $stock->stock_disponible }}
                                    </span>
                                    @if($stock->stock_minimo > 0)
                                        <br>
                                        <small class="text-muted">Min: {{ $stock->stock_minimo }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($stock->stock_reservado > 0)
                                        <span class="badge bg-warning fs-6">{{ $stock->stock_reservado }}</span>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($stock->stock_alquilado > 0)
                                        <span class="badge bg-info fs-6">{{ $stock->stock_alquilado }}</span>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($stock->stock_en_eventos > 0)
                                        <span class="badge bg-primary fs-6">{{ $stock->stock_en_eventos }}</span>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($stock->stock_mantenimiento > 0)
                                        <span class="badge bg-danger fs-6">{{ $stock->stock_mantenimiento }}</span>
                                        <br>
                                        <button class="btn btn-sm btn-outline-success mt-1"
                                                wire:click="volverDeMantenimiento({{ $stock->id }})"
                                                title="Devolver de Mantenimiento">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($stock->stock_disponible <= 0)
                                        <span class="badge bg-danger">AGOTADO</span>
                                    @elseif($stock->stock_disponible <= $stock->stock_minimo)
                                        <span class="badge bg-warning">BAJO</span>
                                    @elseif($stock->stock_total >= $stock->stock_maximo)
                                        <span class="badge bg-info">EXCESIVO</span>
                                    @else
                                        <span class="badge bg-success">NORMAL</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-primary"
                                                wire:click="openHistorialModal({{ $stock->id }})"
                                                title="Ver Historial">
                                            <i class="fas fa-history"></i>
                                        </button>

                                        <button class="btn btn-outline-warning"
                                                wire:click="openAjusteModal({{ $stock->id }})"
                                                title="Ajustar Stock">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button class="btn btn-outline-info"
                                                wire:click="openTransferenciaModal({{ $stock->id }})"
                                                title="Transferir Stock">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>

                                        @if($stock->stock_disponible > 0)
                                            <button class="btn btn-outline-danger"
                                                    wire:click="moverAMantenimiento({{ $stock->id }})"
                                                    title="Mover a Mantenimiento">
                                                <i class="fas fa-tools"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No hay productos en stock</h5>
                                    <p class="text-muted">Agregue productos al inventario</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $stocks->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Ajuste de Stock -->
    @if($showAjusteModal && $selectedStock)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="fas fa-edit me-2"></i>Ajustar Stock
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeAjusteModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Producto:</strong> {{ $selectedStock->producto->nombre }}<br>
                            <strong>Sucursal:</strong> {{ $selectedStock->sucursal->nombre }}
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Stock Actual</label>
                                <div class="form-control-plaintext bg-light border rounded text-center fw-bold fs-4">
                                    {{ $ajuste_cantidad_actual }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nueva Cantidad *</label>
                                <input type="number" class="form-control @error('ajuste_nueva_cantidad') is-invalid @enderror"
                                       wire:model="ajuste_nueva_cantidad" min="0">
                                @error('ajuste_nueva_cantidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if($this->diferenciaAjuste != 0)
                            <div class="alert {{ $this->diferenciaAjuste > 0 ? 'alert-success' : 'alert-danger' }}">
                                <strong>
                                    {{ $this->diferenciaAjuste > 0 ? 'Incremento:' : 'Decremento:' }}
                                    {{ abs($this->diferenciaAjuste) }} unidades
                                </strong>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Observaciones del Ajuste *</label>
                            <textarea class="form-control @error('ajuste_observaciones') is-invalid @enderror"
                                      rows="3" wire:model="ajuste_observaciones"
                                      placeholder="Motivo del ajuste: inventario físico, error de sistema, pérdida, etc."></textarea>
                            @error('ajuste_observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeAjusteModal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-warning" wire:click="saveAjusteStock">
                            <i class="fas fa-save me-1"></i>Guardar Ajuste
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Transferencia de Stock -->
    @if($showTransferenciaModal && $selectedStock)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-exchange-alt me-2"></i>Transferir Stock
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeTransferenciaModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Producto:</strong> {{ $selectedStock->producto->nombre }}<br>
                            <strong>Sucursal Origen:</strong> {{ $selectedStock->sucursal->nombre }}<br>
                            <strong>Stock Disponible:</strong> {{ $this->stockDisponibleTransferencia }}
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Sucursal Destino *</label>
                                <select class="form-select @error('transferencia_sucursal_destino') is-invalid @enderror"
                                        wire:model="transferencia_sucursal_destino">
                                    <option value="">Seleccione destino</option>
                                    @foreach ($sucursales as $sucursal)
                                        @if($sucursal->id != $transferencia_sucursal_origen)
                                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('transferencia_sucursal_destino')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cantidad a Transferir *</label>
                                <input type="number" class="form-control @error('transferencia_cantidad') is-invalid @enderror"
                                       wire:model="transferencia_cantidad" min="1" max="{{ $this->stockDisponibleTransferencia }}">
                                @error('transferencia_cantidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones de la Transferencia *</label>
                            <textarea class="form-control @error('transferencia_observaciones') is-invalid @enderror"
                                      rows="3" wire:model="transferencia_observaciones"
                                      placeholder="Motivo de la transferencia, solicitud, etc."></textarea>
                            @error('transferencia_observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeTransferenciaModal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-info" wire:click="saveTransferenciaStock">
                            <i class="fas fa-exchange-alt me-1"></i>Transferir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Historial de Movimientos -->
    @if($showHistorialModal && $selectedStock)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-history me-2"></i>Historial de Movimientos
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeHistorialModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Producto:</strong> {{ $selectedStock->producto->nombre }}<br>
                            <strong>Sucursal:</strong> {{ $selectedStock->sucursal->nombre }}<br>
                            <strong>Stock Actual:</strong> {{ $selectedStock->stock_total }}
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Referencia</th>
                                        <th>Anterior</th>
                                        <th>Movimiento</th>
                                        <th>Posterior</th>
                                        <th>Usuario</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($this->getHistorialMovimientos() as $movimiento)
                                        <tr>
                                            <td>{{ $movimiento->fecha_movimiento->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @php
                                                    $badgeClass = match($movimiento->tipo_movimiento) {
                                                        'ENTRADA' => 'bg-success',
                                                        'SALIDA' => 'bg-danger',
                                                        'RESERVA' => 'bg-warning',
                                                        'LIBERACION' => 'bg-info',
                                                        'ALQUILER' => 'bg-primary',
                                                        'DEVOLUCION' => 'bg-success',
                                                        'VENTA' => 'bg-secondary',
                                                        'EVENTO' => 'bg-info',
                                                        'MANTENIMIENTO' => 'bg-danger',
                                                        'AJUSTE' => 'bg-warning',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $movimiento->tipo_movimiento }}</span>
                                            </td>
                                            <td>{{ $movimiento->numero_referencia }}</td>
                                            <td class="text-center">{{ $movimiento->cantidad_anterior }}</td>
                                            <td class="text-center fw-bold {{ $movimiento->tipo_movimiento === 'ENTRADA' ? 'text-success' : 'text-danger' }}">
                                                {{ $movimiento->tipo_movimiento === 'ENTRADA' ? '+' : '-' }}{{ $movimiento->cantidad_movimiento }}
                                            </td>
                                            <td class="text-center">{{ $movimiento->cantidad_posterior }}</td>
                                            <td>{{ $movimiento->usuario->nombres ?? 'Sistema' }}</td>
                                            <td>
                                                <small>{{ $movimiento->observaciones }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeHistorialModal">
                            <i class="fas fa-times me-1"></i>Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Alertas -->
    @if($showAlertasModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>Alertas de Stock
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeAlertasModal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Stock Agotado -->
                        @if(count($alertas['stock_agotado']) > 0)
                            <div class="card border-danger mb-3">
                                <div class="card-header bg-danger text-white">
                                    <i class="fas fa-times-circle me-2"></i>Stock Agotado ({{ count($alertas['stock_agotado']) }})
                                </div>
                                <div class="card-body">
                                    @foreach($alertas['stock_agotado'] as $stock)
                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <div>
                                                <strong>{{ $stock->producto->nombre }}</strong><br>
                                                <small class="text-muted">{{ $stock->sucursal->nombre }}</small>
                                            </div>
                                            <span class="badge bg-danger">{{ $stock->stock_disponible }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Stock Bajo -->
                        @if(count($alertas['stock_bajo']) > 0)
                            <div class="card border-warning mb-3">
                                <div class="card-header bg-warning text-dark">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Stock Bajo ({{ count($alertas['stock_bajo']) }})
                                </div>
                                <div class="card-body">
                                    @foreach($alertas['stock_bajo'] as $stock)
                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <div>
                                                <strong>{{ $stock->producto->nombre }}</strong><br>
                                                <small class="text-muted">{{ $stock->sucursal->nombre }} | Mín: {{ $stock->stock_minimo }}</small>
                                            </div>
                                            <span class="badge bg-warning">{{ $stock->stock_disponible }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Stock Excesivo -->
                        @if(count($alertas['stock_excesivo']) > 0)
                            <div class="card border-info mb-3">
                                <div class="card-header bg-info text-white">
                                    <i class="fas fa-info-circle me-2"></i>Stock Excesivo ({{ count($alertas['stock_excesivo']) }})
                                </div>
                                <div class="card-body">
                                    @foreach($alertas['stock_excesivo'] as $stock)
                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <div>
                                                <strong>{{ $stock->producto->nombre }}</strong><br>
                                                <small class="text-muted">{{ $stock->sucursal->nombre }} | Máx: {{ $stock->stock_maximo }}</small>
                                            </div>
                                            <span class="badge bg-info">{{ $stock->stock_total }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(count($alertas['stock_agotado']) == 0 && count($alertas['stock_bajo']) == 0 && count($alertas['stock_excesivo']) == 0)
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h5 class="text-success">¡Todo está en orden!</h5>
                                <p class="text-muted">No hay alertas de stock en este momento</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeAlertasModal">
                            <i class="fas fa-times me-1"></i>Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        // Confirmación para acciones críticas
        Livewire.on('confirmarAjuste', () => {
            if (confirm('¿Está seguro de realizar este ajuste de stock? Esta acción quedará registrada en el historial.')) {
                @this.call('saveAjusteStock');
            }
        });

        Livewire.on('confirmarTransferencia', () => {
            if (confirm('¿Está seguro de realizar esta transferencia de stock?')) {
                @this.call('saveTransferenciaStock');
            }
        });

        // Auto-refresh cada 30 segundos para stock crítico
        setInterval(function() {
            if (document.hidden === false) {
                @this.call('$refresh');
            }
        }, 30000);
    });
</script>
@endpush