@if($showViewComponenteModal && $selectedComponente)
<div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>
                    Detalles del Componente
                </h5>
                <button type="button" class="btn-close btn-close-white" wire:click="closeViewComponenteModal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Información básica -->
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <strong class="text-muted d-block mb-1">Código:</strong>
                                        <span class="badge bg-secondary fs-6">{{ $selectedComponente->codigo }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong class="text-muted d-block mb-1">Estado:</strong>
                                        @if($selectedComponente->activo)
                                            <span class="badge bg-success fs-6">Activo</span>
                                        @else
                                            <span class="badge bg-secondary fs-6">Inactivo</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <strong class="text-muted d-block mb-1">Género:</strong>
                                        @if($selectedComponente->genero == 'FEMENINO')
                                            <span class="badge bg-danger fs-6"><i class="fas fa-female"></i> Femenino</span>
                                        @elseif($selectedComponente->genero == 'MASCULINO')
                                            <span class="badge bg-info fs-6"><i class="fas fa-male"></i> Masculino</span>
                                        @elseif($selectedComponente->genero == 'INFANTIL')
                                            <span class="badge bg-warning fs-6"><i class="fas fa-child"></i> Infantil</span>
                                        @else
                                            <span class="badge bg-secondary fs-6"><i class="fas fa-users"></i> Unisex</span>
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <strong class="text-muted d-block mb-1">Nombre:</strong>
                                        <h5 class="mb-0">{{ $selectedComponente->nombre }}</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <strong class="text-muted d-block mb-1">Tipo:</strong>
                                        {{ $selectedComponente->tipoComponente->nombre }}
                                    </div>
                                    @if($selectedComponente->color)
                                    <div class="col-md-6">
                                        <strong class="text-muted d-block mb-1">Color:</strong>
                                        {{ $selectedComponente->color }}
                                    </div>
                                    @endif
                                    @if($selectedComponente->descripcion)
                                    <div class="col-12">
                                        <strong class="text-muted d-block mb-1">Descripción:</strong>
                                        <p class="mb-0">{{ $selectedComponente->descripcion }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conjuntos donde se usa -->
                    @if($selectedComponente->conjuntos->count() > 0)
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-layer-group me-2"></i>
                                    Usado en {{ $selectedComponente->conjuntos->count() }} Conjunto(s)
                                </h6>
                                <div class="list-group">
                                    @foreach($selectedComponente->conjuntos as $conjunto)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $conjunto->nombre }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $conjunto->codigo }}</small>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">{{ $conjunto->pivot->cantidad_requerida }}x</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col-12">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Este componente no está siendo usado en ningún conjunto actualmente.
                        </div>
                    </div>
                    @endif

                    <!-- Metadatos -->
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-plus me-1"></i>
                                            Creado: {{ $selectedComponente->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            Actualizado: {{ $selectedComponente->updated_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeViewComponenteModal">
                    <i class="fas fa-times me-1"></i> Cerrar
                </button>
                <button type="button" class="btn btn-warning" wire:click="openEditComponenteModal({{ $selectedComponente->id }})">
                    <i class="fas fa-edit me-1"></i> Editar
                </button>
            </div>
        </div>
    </div>
</div>
@endif