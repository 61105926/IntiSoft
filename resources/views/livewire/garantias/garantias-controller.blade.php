<div>
    <!-- Botón Nueva Garantía -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                <i class="fas fa-shield-alt fa-lg text-primary"></i>
            </div>
            <div>
                <h4 class="mb-0">Gestión de Garantías</h4>
                <small class="text-muted">Control y seguimiento de garantías de alquileres</small>
            </div>
        </div>
        <button type="button" class="btn btn-primary" wire:click="openNewGarantiaModal">
            <i class="fas fa-plus me-2"></i>Nueva Garantía
        </button>
    </div>

    <!-- Mensajes de éxito/error -->
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

    <!-- Estadísticas -->
    <div class="row row-cols-1 row-cols-md-5 g-4 mb-4">
        <div class="col">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Total</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['total'] }}</p>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                        <i class="fas fa-shield-alt fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Recibidas</p>
                        <p class="fs-2 fw-bold mb-0 text-success">{{ $estadisticas['recibidas'] }}</p>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-circle p-2">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Vencidas</p>
                        <p class="fs-2 fw-bold mb-0 text-danger">{{ $estadisticas['vencidas'] }}</p>
                    </div>
                    <div class="bg-danger bg-opacity-10 rounded-circle p-2">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Devueltas</p>
                        <p class="fs-2 fw-bold mb-0 text-info">{{ $estadisticas['devueltas'] }}</p>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-circle p-2">
                        <i class="fas fa-undo fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Aplicadas</p>
                        <p class="fs-2 fw-bold mb-0 text-warning">{{ $estadisticas['aplicadas'] }}</p>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-start-0" 
                               placeholder="Buscar por ticket, cliente, documento..." 
                               wire:model.debounce.300ms="searchTerm">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model="filterEstado">
                        <option value="TODAS">Todos los Estados</option>
                        <option value="RECIBIDA">Recibidas</option>
                        <option value="DEVUELTA">Devueltas</option>
                        <option value="APLICADA">Aplicadas</option>
                        <option value="PERDIDA">Perdidas</option>
                        <option value="VENCIDAS">Vencidas</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model="filterTipo">
                        <option value="TODOS">Todos los Tipos</option>
                        @foreach ($tiposGarantia as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model="filterSucursal">
                        <option value="TODAS">Todas las Sucursales</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Garantías -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Garantías ({{ $garantias->total() }})
                </h5>
                <small class="text-muted">{{ $garantias->firstItem() }} - {{ $garantias->lastItem() }} de {{ $garantias->total() }}</small>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ticket</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Fechas</th>
                            <th>Sucursal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($garantias as $garantia)
                            <tr class="{{ $garantia->esta_vencida ? 'table-danger' : '' }}">
                                <td>
                                    <div class="fw-bold text-primary">{{ $garantia->numero_ticket }}</div>
                                    @if($garantia->documento_respaldo)
                                        <small class="text-muted">{{ Str::limit($garantia->documento_respaldo, 20) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $garantia->cliente->nombres }} {{ $garantia->cliente->apellidos }}</strong>
                                        <br><small class="text-muted">CI: {{ $garantia->cliente->carnet_identidad }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $garantia->tipoGarantia->requiere_monto ? 'success' : 'info' }}">
                                        {{ $garantia->tipoGarantia->nombre }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        {{ Str::limit($garantia->descripcion, 50) }}
                                    </div>
                                </td>
                                <td>
                                    @if($garantia->monto > 0)
                                        <div class="text-center">
                                            <div class="fw-bold">Bs. {{ number_format($garantia->monto, 2) }}</div>
                                            @if($garantia->monto_aplicado > 0 || $garantia->monto_devuelto > 0)
                                                <small class="text-muted">
                                                    Disp: Bs. {{ number_format($garantia->monto_disponible, 2) }}
                                                </small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">Sin monto</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($garantia->estado)
                                        @case('RECIBIDA')
                                            @if($garantia->esta_vencida)
                                                <span class="badge bg-danger">Vencida</span>
                                            @else
                                                <span class="badge bg-success">{{ $garantia->estado_display }}</span>
                                            @endif
                                        @break
                                        @case('DEVUELTA')
                                            <span class="badge bg-info">{{ $garantia->estado_display }}</span>
                                        @break
                                        @case('APLICADA')
                                            <span class="badge bg-warning">{{ $garantia->estado_display }}</span>
                                        @break
                                        @case('PERDIDA')
                                            <span class="badge bg-secondary">{{ $garantia->estado_display }}</span>
                                        @break
                                        @default
                                            <span class="badge bg-light text-dark">{{ $garantia->estado_display }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <div class="small">
                                        <div><strong>Recibida:</strong> {{ $garantia->fecha_recepcion->format('d/m/Y') }}</div>
                                        @if($garantia->fecha_vencimiento)
                                            <div class="{{ $garantia->esta_vencida ? 'text-danger' : 'text-muted' }}">
                                                <strong>Vence:</strong> {{ $garantia->fecha_vencimiento->format('d/m/Y') }}
                                            </div>
                                        @endif
                                        @if($garantia->fecha_devolucion)
                                            <div class="text-success">
                                                <strong>Devuelta:</strong> {{ $garantia->fecha_devolucion->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <small>{{ $garantia->sucursal->nombre }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary" 
                                                wire:click="viewGarantia({{ $garantia->id }})"
                                                title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        @if($garantia->estado === 'RECIBIDA')
                                            <button type="button" 
                                                    class="btn btn-sm btn-success" 
                                                    wire:click="openDevolucionModal({{ $garantia->id }})"
                                                    title="Devolver">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-warning" 
                                                    wire:click="marcarComoPerdida({{ $garantia->id }})"
                                                    title="Marcar como Perdida">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-5 text-center">
                                    <div class="text-muted">
                                        <i class="fas fa-shield-alt fa-3x mb-3"></i>
                                        <p class="mb-0">No se encontraron garantías</p>
                                        <small>Utiliza los filtros o crea una nueva garantía</small>
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
            <div class="card-footer bg-light">
                {{ $garantias->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Nueva Garantía --}}
    @if ($showNewGarantiaModal)
        @include('livewire.garantias.modal-nueva-garantia')
    @endif

    {{-- Modal Ver Garantía --}}
    @if ($showViewGarantiaModal && $selectedGarantia)
        @include('livewire.garantias.modal-ver-garantia')
    @endif

    {{-- Modal Devolución --}}
    @if ($showDevolucionModal && $selectedGarantia)
        @include('livewire.garantias.modal-devolucion')
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Listener para eventos de SweetAlert con confirmación
        window.addEventListener('swal-confirm', function(e) {
            Swal.fire({
                title: e.detail.title,
                text: e.detail.text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call(e.detail.method, e.detail.params);
                }
            });
        });

        // Listener para eventos de SweetAlert simples
        window.addEventListener('swal', function(e) {
            Swal.fire({
                title: e.detail.title,
                text: e.detail.text,
                icon: e.detail.icon,
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
</div>