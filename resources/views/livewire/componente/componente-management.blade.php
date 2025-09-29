<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-puzzle-piece text-primary me-2"></i>
                        Componentes de Trajes
                    </h1>
                    <p class="text-muted">Piezas individuales que conforman los conjuntos folklóricos</p>
                </div>
                <div>
                    <button wire:click="openNewComponenteModal" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Nuevo Componente
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Compactas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-puzzle-piece fa-2x text-primary mb-2"></i>
                    <h4 class="mb-0">{{ $estadisticas['total_componentes'] }}</h4>
                    <small class="text-muted">Total Componentes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-female fa-2x text-danger mb-2"></i>
                    <h4 class="mb-0">{{ $estadisticas['femeninos'] }}</h4>
                    <small class="text-muted">Femeninos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-male fa-2x text-info mb-2"></i>
                    <h4 class="mb-0">{{ $estadisticas['masculinos'] }}</h4>
                    <small class="text-muted">Masculinos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-layer-group fa-2x text-success mb-2"></i>
                    <h4 class="mb-0">{{ $estadisticas['total_tipos'] }}</h4>
                    <small class="text-muted">Tipos</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros Simplificados -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" wire:model.debounce.300ms="searchTerm" class="form-control" placeholder="Buscar componente...">
                </div>
                <div class="col-md-3">
                    <select wire:model="filterTipo" class="form-select">
                        <option value="">Todos los tipos</option>
                        @foreach($tiposComponente as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model="filterGenero" class="form-select">
                        <option value="">Todos</option>
                        <option value="MASCULINO">Masculino</option>
                        <option value="FEMENINO">Femenino</option>
                        <option value="UNISEX">Unisex</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button wire:click="clearFilters" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-1"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista Compacta de Componentes -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Género</th>
                            <th>Estado</th>
                            <th style="width: 120px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($componentes as $componente)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $componente->codigo }}</span></td>
                                <td>
                                    <strong>{{ $componente->nombre }}</strong>
                                    @if($componente->color)
                                        <br><small class="text-muted">{{ $componente->color }}</small>
                                    @endif
                                </td>
                                <td>{{ $componente->tipoComponente->nombre }}</td>
                                <td>
                                    @if($componente->genero == 'FEMENINO')
                                        <i class="fas fa-female text-danger"></i>
                                    @elseif($componente->genero == 'MASCULINO')
                                        <i class="fas fa-male text-info"></i>
                                    @else
                                        <i class="fas fa-users text-secondary"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($componente->activo)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button wire:click="viewComponente({{ $componente->id }})" class="btn btn-outline-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button wire:click="openEditComponenteModal({{ $componente->id }})" class="btn btn-outline-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="toggleActivo({{ $componente->id }})" class="btn btn-outline-secondary" title="Activar/Desactivar">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                                    <p class="text-muted mb-0">No se encontraron componentes</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $componentes->links() }}
            </div>
        </div>
    </div>

    <!-- Modales -->
    @include('livewire.componente.modals.nuevo-componente-modal')
    @include('livewire.componente.modals.editar-componente-modal')
    @include('livewire.componente.modals.view-componente-modal')

    <!-- Notificaciones -->
    @if (session()->has('success'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast show" role="alert">
                <div class="toast-header bg-success text-white">
                    <strong class="me-auto">Éxito</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast show" role="alert">
                <div class="toast-header bg-danger text-white">
                    <strong class="me-auto">Error</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('error') }}
                </div>
            </div>
        </div>
    @endif
</div>