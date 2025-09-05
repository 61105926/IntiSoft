<div>
    <div class="container py-4">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark">
                    <i class="fas fa-shield-alt me-2 text-warning"></i>Garantías
                </h1>
                <p class="text-muted mb-0">Gestión de garantías de alquiler</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success btn-sm" wire:click="actualizarEstadosVencidos">
                    <i class="fas fa-sync-alt me-1"></i> Actualizar Estados
                </button>
                <button type="button" class="btn btn-warning text-dark fw-semibold" wire:click="openNewGarantiaModal">
                    <i class="fas fa-plus me-2"></i>Nueva Garantía
                </button>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-2">
                <div class="card shadow-sm border-primary">
                    <div class="card-body text-center">
                        <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                        <h4 class="fw-bold mb-0">{{ $estadisticas['total'] }}</h4>
                        <small class="text-muted">Total</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="card shadow-sm border-success">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <h4 class="fw-bold mb-0 text-success">{{ $estadisticas['recibidas'] }}</h4>
                        <small class="text-muted">Recibidas</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="card shadow-sm border-info">
                    <div class="card-body text-center">
                        <i class="fas fa-undo fa-2x text-info mb-2"></i>
                        <h4 class="fw-bold mb-0 text-info">{{ $estadisticas['devueltas'] }}</h4>
                        <small class="text-muted">Devueltas</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="card shadow-sm border-danger">
                    <div class="card-body text-center">
                        <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                        <h4 class="fw-bold mb-0 text-danger">{{ $estadisticas['vencidas'] }}</h4>
                        <small class="text-muted">Vencidas</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="card shadow-sm border-warning">
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                        <h4 class="fw-bold mb-0 text-warning">{{ $estadisticas['venceHoy'] }}</h4>
                        <small class="text-muted">Vencen Hoy</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="card shadow-sm border-info">
                    <div class="card-body text-center">
                        <i class="fas fa-dollar-sign fa-2x text-success mb-2"></i>
                        <h5 class="fw-bold mb-0 text-success">{{ number_format($estadisticas['montoDisponible'], 2) }}</h5>
                        <small class="text-muted">Disponible (Bs.)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Búsqueda -->
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Buscar por ticket, cliente o descripción..." 
                                   wire:model.debounce.300ms="searchTerm">
                        </div>
                    </div>
                    <!-- Filtro por Estado -->
                    <div class="col-md-2">
                        <select class="form-select" wire:model="filterEstado">
                            <option value="TODOS">Todos los estados</option>
                            <option value="{{ \App\Models\Garantia::ESTADO_RECIBIDA }}">Recibidas</option>
                            <option value="{{ \App\Models\Garantia::ESTADO_DEVUELTA }}">Devueltas</option>
                            <option value="{{ \App\Models\Garantia::ESTADO_APLICADA }}">Aplicadas</option>
                            <option value="{{ \App\Models\Garantia::ESTADO_VENCIDA }}">Vencidas</option>
                            <option value="{{ \App\Models\Garantia::ESTADO_PERDIDA }}">Perdidas</option>
                        </select>
                    </div>
                    <!-- Filtro por Tipo -->
                    <div class="col-md-2">
                        <select class="form-select" wire:model="filterTipoGarantia">
                            <option value="TODOS">Todos los tipos</option>
                            @foreach($tiposGarantia as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Filtro por Sucursal -->
                    <div class="col-md-2">
                        <select class="form-select" wire:model="filterSucursal">
                            <option value="TODAS">Todas las sucursales</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Filtro por Fecha -->
                    <div class="col-md-2">
                        <input type="date" class="form-control" placeholder="Fecha" wire:model="filterFecha">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Garantías ({{ $garantias->total() }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ticket</th>
                                <th>Cliente</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Monto</th>
                                <th>Fechas</th>
                                <th>Estado</th>
                                <th>Alquiler</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($garantias as $garantia)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-primary">{{ $garantia->numero_ticket }}</div>
                                        <small class="text-muted">{{ $garantia->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $garantia->cliente->nombres }} {{ $garantia->cliente->apellidos }}</div>
                                        <small class="text-muted">CI: {{ $garantia->cliente->carnet_identidad }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $garantia->tipoGarantia->nombre }}</span>
                                    </td>
                                    <td>
                                        <div class="text-wrap" style="max-width: 200px;">{{ $garantia->descripcion }}</div>
                                    </td>
                                    <td>
                                        @if($garantia->monto > 0)
                                            <div class="fw-bold text-success">Bs. {{ number_format($garantia->monto, 2) }}</div>
                                            @if($garantia->monto_aplicado > 0)
                                                <small class="text-danger">Aplicado: Bs. {{ number_format($garantia->monto_aplicado, 2) }}</small><br>
                                            @endif
                                            @if($garantia->monto_devuelto > 0)
                                                <small class="text-info">Devuelto: Bs. {{ number_format($garantia->monto_devuelto, 2) }}</small><br>
                                            @endif
                                            <small class="text-primary fw-bold">Disponible: Bs. {{ number_format($garantia->monto_disponible, 2) }}</small>
                                        @else
                                            <span class="text-muted">Sin monto</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <small class="text-muted">Recepción:</small><br>
                                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($garantia->fecha_recepcion)->format('d/m/Y') }}</span>
                                        </div>
                                        @if($garantia->fecha_vencimiento)
                                            <div class="mt-1">
                                                <small class="text-muted">Vence:</small><br>
                                                <span class="fw-semibold {{ $garantia->esta_vencida ? 'text-danger' : 'text-success' }}">
                                                    {{ \Carbon\Carbon::parse($garantia->fecha_vencimiento)->format('d/m/Y') }}
                                                </span>
                                                @if($garantia->esta_vencida)
                                                    <br><small class="text-danger">({{ $garantia->dias_vencidos }} días vencida)</small>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($garantia->estado) {
                                                \App\Models\Garantia::ESTADO_RECIBIDA => 'bg-success',
                                                \App\Models\Garantia::ESTADO_DEVUELTA => 'bg-info',
                                                \App\Models\Garantia::ESTADO_APLICADA => 'bg-warning',
                                                \App\Models\Garantia::ESTADO_VENCIDA => 'bg-danger',
                                                \App\Models\Garantia::ESTADO_PERDIDA => 'bg-dark',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $garantia->estado_display }}</span>
                                    </td>
                                    <td>
                                        @if($garantia->alquileres->count() > 0)
                                            @foreach($garantia->alquileres as $alquiler)
                                                <div class="mb-1">
                                                    <span class="badge bg-primary">{{ $alquiler->numero_contrato }}</span>
                                                    <small class="text-muted d-block">{{ $alquiler->estado }}</small>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Sin asignar</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    wire:click="viewGarantia({{ $garantia->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($garantia->estado === \App\Models\Garantia::ESTADO_RECIBIDA)
                                                <button type="button" class="btn btn-sm btn-success" 
                                                        wire:click="openDevolucionModal({{ $garantia->id }})">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        wire:click="marcarComoPerdida({{ $garantia->id }}, 'Marcada como perdida desde interfaz')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-4 text-center">
                                        <div class="text-muted">
                                            <i class="fas fa-shield-alt fa-3x mb-3"></i>
                                            <p>No se encontraron garantías</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Paginación -->
            @if ($garantias->hasPages())
                <div class="card-footer">
                    {{ $garantias->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Nueva Garantía -->
    @if ($showNewGarantiaModal)
        @include('livewire.garantia.form')
    @endif

    <!-- Modal Ver Garantía -->
    @if ($showViewGarantiaModal && $selectedGarantia)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-shield-alt me-2"></i>Detalles de Garantía
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeViewGarantiaModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">Información General</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Ticket:</strong></td>
                                        <td>{{ $selectedGarantia->numero_ticket }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cliente:</strong></td>
                                        <td>{{ $selectedGarantia->cliente->nombres }} {{ $selectedGarantia->cliente->apellidos }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tipo:</strong></td>
                                        <td>{{ $selectedGarantia->tipoGarantia->nombre }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Descripción:</strong></td>
                                        <td>{{ $selectedGarantia->descripcion }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Estado:</strong></td>
                                        <td><span class="badge bg-success">{{ $selectedGarantia->estado_display }}</span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Información Financiera y Fechas</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Monto:</strong></td>
                                        <td>Bs. {{ number_format($selectedGarantia->monto, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Aplicado:</strong></td>
                                        <td>Bs. {{ number_format($selectedGarantia->monto_aplicado, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Devuelto:</strong></td>
                                        <td>Bs. {{ number_format($selectedGarantia->monto_devuelto, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Disponible:</strong></td>
                                        <td><strong class="text-success">Bs. {{ number_format($selectedGarantia->monto_disponible, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fecha Recepción:</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($selectedGarantia->fecha_recepcion)->format('d/m/Y') }}</td>
                                    </tr>
                                    @if($selectedGarantia->fecha_vencimiento)
                                        <tr>
                                            <td><strong>Fecha Vencimiento:</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($selectedGarantia->fecha_vencimiento)->format('d/m/Y') }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        
                        @if($selectedGarantia->alquileres->count() > 0)
                            <h6 class="text-muted mt-3">Alquileres Asociados</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Contrato</th>
                                            <th>Cliente</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selectedGarantia->alquileres as $alquiler)
                                            <tr>
                                                <td>{{ $alquiler->numero_contrato }}</td>
                                                <td>{{ $alquiler->cliente->nombres }}</td>
                                                <td>{{ $alquiler->fecha_alquiler->format('d/m/Y') }}</td>
                                                <td><span class="badge bg-primary">{{ $alquiler->estado }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        
                        @if($selectedGarantia->observaciones)
                            <h6 class="text-muted mt-3">Observaciones</h6>
                            <p class="text-muted">{{ $selectedGarantia->observaciones }}</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeViewGarantiaModal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Devolución -->
    @if ($showDevolucionModal && $selectedGarantia)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-undo me-2"></i>Devolver Garantía
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeDevolucionModal"></button>
                    </div>
                    <div class="modal-body">
                        @if (session()->has('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        
                        <div class="alert alert-info">
                            <strong>Garantía:</strong> {{ $selectedGarantia->numero_ticket }}<br>
                            <strong>Cliente:</strong> {{ $selectedGarantia->cliente->nombres }} {{ $selectedGarantia->cliente->apellidos }}<br>
                            <strong>Monto disponible:</strong> Bs. {{ number_format($selectedGarantia->monto_disponible, 2) }}
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Monto a devolver (Bs.)</label>
                            <input type="number" step="0.01" class="form-control @error('monto_devuelto') is-invalid @enderror" 
                                   wire:model="monto_devuelto" max="{{ $selectedGarantia->monto_disponible }}">
                            @error('monto_devuelto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" rows="3" wire:model="observaciones_devolucion" 
                                      placeholder="Motivo de la devolución..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeDevolucionModal">Cancelar</button>
                        <button type="button" class="btn btn-success" wire:click="procesarDevolucion">
                            <i class="fas fa-check me-1"></i>Procesar Devolución
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>