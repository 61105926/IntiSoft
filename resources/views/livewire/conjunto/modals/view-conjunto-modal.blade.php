{{-- Modal Ver Detalles del Conjunto - Réplica exacta del sistema original --}}
@if($showViewConjuntoModal && $selectedConjunto)
<div class="modal fade show" style="display: block;" tabindex="-1">
    <div class="modal-dialog modal-xl" style="max-width: 1200px;">
        <div class="modal-content" style="max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>
                    Detalles del Conjunto: {{ $selectedConjunto->nombre }}
                </h5>
                <button type="button" class="btn-close" wire:click="$set('showViewConjuntoModal', false)"></button>
            </div>
            <div class="modal-body">
                {{-- Pestañas --}}
                <ul class="nav nav-tabs" id="detallesTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                            <i class="fas fa-info-circle me-2"></i>General
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="componentes-tab" data-bs-toggle="tab" data-bs-target="#componentes" type="button" role="tab">
                            <i class="fas fa-puzzle-piece me-2"></i>Componentes
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="variaciones-tab" data-bs-toggle="tab" data-bs-target="#variaciones" type="button" role="tab">
                            <i class="fas fa-layer-group me-2"></i>Variaciones
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="instancias-tab" data-bs-toggle="tab" data-bs-target="#instancias" type="button" role="tab">
                            <i class="fas fa-cubes me-2"></i>Instancias
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="analisis-tab" data-bs-toggle="tab" data-bs-target="#analisis" type="button" role="tab">
                            <i class="fas fa-chart-line me-2"></i>Análisis
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-4" id="detallesTabsContent">
                    {{-- Pestaña General --}}
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Información Básica</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row align-items-center mb-3">
                                            <div class="col-sm-4"><strong>Código:</strong></div>
                                            <div class="col-sm-8">
                                                <span class="badge bg-primary bg-opacity-10 text-primary font-monospace">{{ $selectedConjunto->codigo }}</span>
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-3">
                                            <div class="col-sm-4"><strong>Nombre:</strong></div>
                                            <div class="col-sm-8">{{ $selectedConjunto->nombre }}</div>
                                        </div>
                                        <div class="row align-items-center mb-3">
                                            <div class="col-sm-4"><strong>Categoría:</strong></div>
                                            <div class="col-sm-8">
                                                <span class="badge bg-{{ $this->getCategoryBadgeColor($selectedConjunto->categoria_conjunto_id) }} bg-opacity-10 text-{{ $this->getCategoryBadgeColor($selectedConjunto->categoria_conjunto_id) }}">
                                                    {{ str_replace('_', ' ', $selectedConjunto->categoriaConjunto->nombre ?? 'Sin categoría') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-3">
                                            <div class="col-sm-4"><strong>Género:</strong></div>
                                            <div class="col-sm-8">
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $selectedConjunto->genero }}</span>
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-3">
                                            <div class="col-sm-4"><strong>Temporada:</strong></div>
                                            <div class="col-sm-8">{{ str_replace('_', ' ', $selectedConjunto->temporada) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Precios y Modalidades</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row align-items-center mb-3">
                                            <div class="col-sm-5"><strong>Precio Venta Base:</strong></div>
                                            <div class="col-sm-7">
                                                <span class="text-success fw-bold">Bs. {{ number_format($selectedConjunto->precio_venta_base, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-3">
                                            <div class="col-sm-5"><strong>Alquiler/Día:</strong></div>
                                            <div class="col-sm-7">
                                                <span class="text-primary fw-bold">Bs. {{ number_format($selectedConjunto->precio_alquiler_dia, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-3">
                                            <div class="col-sm-5"><strong>Alquiler/Semana:</strong></div>
                                            <div class="col-sm-7">
                                                <span class="text-primary fw-bold">Bs. {{ number_format($selectedConjunto->precio_alquiler_semana, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-3">
                                            <div class="col-sm-5"><strong>Alquiler/Mes:</strong></div>
                                            <div class="col-sm-7">
                                                <span class="text-primary fw-bold">Bs. {{ number_format($selectedConjunto->precio_alquiler_mes, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-3">
                                            <div class="col-sm-5"><strong>Modalidades:</strong></div>
                                            <div class="col-sm-7">
                                                @if($selectedConjunto->disponible_venta)
                                                    <span class="badge bg-success me-1">
                                                        <i class="fas fa-shopping-cart me-1"></i>Venta
                                                    </span>
                                                @endif
                                                @if($selectedConjunto->disponible_alquiler)
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-clock me-1"></i>Alquiler
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($selectedConjunto->descripcion)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Descripción</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $selectedConjunto->descripcion }}</p>
                            </div>
                        </div>
                        @endif

                        @if($selectedConjunto->observaciones)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Observaciones</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $selectedConjunto->observaciones }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Pestaña Componentes --}}
                    <div class="tab-pane fade" id="componentes" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6>Componentes del Conjunto</h6>
                            <span class="badge bg-primary">{{ count($selectedConjunto->componentes ?? []) }} componentes</span>
                        </div>

                        @if($selectedConjunto->componentes && count($selectedConjunto->componentes) > 0)
                            <div class="row g-3">
                                @foreach($selectedConjunto->componentes as $componente)
                                    <div class="col-md-6">
                                        <div class="card border-left-primary">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <i class="fas fa-puzzle-piece fa-2x text-primary"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $componente->nombre ?? 'Componente' }}</h6>
                                                        <p class="text-muted small mb-1">{{ $componente->descripcion ?? '' }}</p>
                                                        <div class="d-flex gap-2">
                                                            @if($componente->pivot->es_obligatorio ?? false)
                                                                <span class="badge bg-danger bg-opacity-10 text-danger">Obligatorio</span>
                                                            @else
                                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">Opcional</span>
                                                            @endif
                                                            <span class="badge bg-info bg-opacity-10 text-info">{{ $componente->codigo ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-puzzle-piece fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay componentes asociados</h5>
                                <p class="text-muted">Este conjunto no tiene componentes configurados.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Pestaña Variaciones --}}
                    <div class="tab-pane fade" id="variaciones" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6>Variaciones del Conjunto</h6>
                            <span class="badge bg-primary">{{ count($selectedConjunto->variaciones ?? []) }} variaciones</span>
                        </div>

                        @if($selectedConjunto->variaciones && count($selectedConjunto->variaciones) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Código</th>
                                            <th>Talla</th>
                                            <th>Color</th>
                                            <th>Estilo</th>
                                            <th>Precio Venta</th>
                                            <th>Precio Alquiler/Día</th>
                                            <th>Instancias</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selectedConjunto->variaciones as $variacion)
                                            @php
                                                $totalInstancias = count($variacion->instancias ?? []);
                                                $disponibles = collect($variacion->instancias ?? [])->where('estado_disponibilidad', 'DISPONIBLE')->count();
                                            @endphp
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary font-monospace">
                                                        {{ $variacion->codigo ?? 'SIN-CÓDIGO' }}
                                                    </span>
                                                </td>
                                                <td>{{ $variacion->talla ?? '-' }}</td>
                                                <td>{{ $variacion->color ?? '-' }}</td>
                                                <td>{{ $variacion->estilo ?? '-' }}</td>
                                                <td>
                                                    <span class="text-success fw-bold">
                                                        Bs. {{ number_format($variacion->precio_venta ?? 0, 2) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-primary fw-bold">
                                                        Bs. {{ number_format($variacion->precio_alquiler_dia ?? 0, 2) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info bg-opacity-10 text-info">
                                                        {{ $disponibles }}/{{ $totalInstancias }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($variacion->activa)
                                                        <span class="badge bg-success">Activa</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactiva</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay variaciones definidas</h5>
                                <p class="text-muted">Este conjunto no tiene variaciones configuradas.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Pestaña Instancias --}}
                    <div class="tab-pane fade" id="instancias" role="tabpanel">
                        @php
                            $todasInstancias = collect();
                            if($selectedConjunto->variaciones) {
                                foreach($selectedConjunto->variaciones as $variacion) {
                                    if($variacion->instancias) {
                                        $todasInstancias = $todasInstancias->merge($variacion->instancias);
                                    }
                                }
                            }
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6>Instancias Físicas</h6>
                            <span class="badge bg-primary">{{ $todasInstancias->count() }} instancias</span>
                        </div>

                        @if($todasInstancias->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Número Serie</th>
                                            <th>Código Interno</th>
                                            <th>Variación</th>
                                            <th>Estado Físico</th>
                                            <th>Disponibilidad</th>
                                            <th>Ubicación</th>
                                            <th>Total Usos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($todasInstancias as $instancia)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary font-monospace">
                                                        {{ $instancia->numero_serie }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info bg-opacity-10 text-info font-monospace">
                                                        {{ $instancia->codigo_interno }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $instancia->variacionConjunto->talla ?? '' }}
                                                    {{ $instancia->variacionConjunto->color ?? '' }}
                                                    {{ $instancia->variacionConjunto->estilo ?? '' }}
                                                </td>
                                                <td>
                                                    @if($instancia->estado_fisico === 'EXCELENTE')
                                                        <span class="badge bg-success">Excelente</span>
                                                    @elseif($instancia->estado_fisico === 'BUENO')
                                                        <span class="badge bg-primary">Bueno</span>
                                                    @elseif($instancia->estado_fisico === 'REGULAR')
                                                        <span class="badge bg-warning">Regular</span>
                                                    @else
                                                        <span class="badge bg-danger">Malo</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($instancia->estado_disponibilidad === 'DISPONIBLE')
                                                        <span class="badge bg-success">Disponible</span>
                                                    @elseif($instancia->estado_disponibilidad === 'ALQUILADO')
                                                        <span class="badge bg-primary">Alquilado</span>
                                                    @elseif($instancia->estado_disponibilidad === 'RESERVADO')
                                                        <span class="badge bg-warning">Reservado</span>
                                                    @else
                                                        <span class="badge bg-secondary">En Limpieza</span>
                                                    @endif
                                                </td>
                                                <td>{{ $instancia->ubicacion_almacen ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-info bg-opacity-10 text-info">
                                                        {{ $instancia->total_usos ?? 0 }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-cubes fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay instancias creadas</h5>
                                <p class="text-muted">Este conjunto no tiene instancias físicas creadas.</p>
                                <button class="btn btn-primary" wire:click="manageInstances({{ $selectedConjunto->id }})">
                                    <i class="fas fa-plus me-2"></i>Crear Instancias
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Pestaña Análisis --}}
                    <div class="tab-pane fade" id="analisis" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Análisis de Rentabilidad</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="p-4 bg-success bg-opacity-10 rounded-lg border border-success border-opacity-25 mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-success fw-medium">ROI Promedio</span>
                                                <span class="display-6 fw-bold text-success">85.5%</span>
                                            </div>
                                            <div class="progress mt-2" style="height: 8px;">
                                                <div class="progress-bar bg-success" style="width: 85.5%"></div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="p-3 bg-primary bg-opacity-10 rounded text-center">
                                                    <p class="small text-primary mb-1">Tasa de Utilización</p>
                                                    <p class="h5 fw-bold text-primary mb-0">
                                                        @php
                                                            $totalInstancias = $todasInstancias->count();
                                                            $enUso = $todasInstancias->whereIn('estado_disponibilidad', ['ALQUILADO', 'RESERVADO'])->count();
                                                            $tasaUtilizacion = $totalInstancias > 0 ? round(($enUso / $totalInstancias) * 100) : 0;
                                                        @endphp
                                                        {{ $tasaUtilizacion }}%
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-3 bg-warning bg-opacity-10 rounded text-center">
                                                    <p class="small text-warning mb-1">Instancias Vendidas</p>
                                                    <p class="h5 fw-bold text-warning mb-0">0</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Métricas de Desempeño</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                            <span class="fw-medium">Rotación de Inventario:</span>
                                            <span class="badge bg-success">Alta</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                            <span class="fw-medium">Demanda Promedio:</span>
                                            <span class="badge bg-primary">Estable</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                            <span class="fw-medium">Tiempo Promedio Alquiler:</span>
                                            <span class="badge bg-info">3.5 días</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                            <span class="fw-medium">Satisfacción Cliente:</span>
                                            <span class="badge bg-success">Excelente</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">Recomendaciones</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-start p-3 bg-primary bg-opacity-10 rounded mb-3">
                                    <i class="fas fa-chart-line text-primary me-3 mt-1"></i>
                                    <div>
                                        <p class="fw-medium text-primary mb-1">Oportunidad de Crecimiento</p>
                                        <p class="small text-primary mb-0">
                                            Considere aumentar el inventario de tallas M y L debido a la alta demanda.
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start p-3 bg-warning bg-opacity-10 rounded mb-3">
                                    <i class="fas fa-exclamation-triangle text-warning me-3 mt-1"></i>
                                    <div>
                                        <p class="fw-medium text-warning mb-1">Atención Requerida</p>
                                        <p class="small text-warning mb-0">Algunos componentes están llegando al stock mínimo.</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start p-3 bg-success bg-opacity-10 rounded">
                                    <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                    <div>
                                        <p class="fw-medium text-success mb-1">Desempeño Óptimo</p>
                                        <p class="small text-success mb-0">
                                            El conjunto mantiene un excelente ROI y alta satisfacción del cliente.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="$set('showViewConjuntoModal', false)">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>

<style>
.border-left-primary {
    border-left: 4px solid #0d6efd !important;
}
</style>
@endif