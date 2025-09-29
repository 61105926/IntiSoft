<div>
    {{-- Sistema de Conjuntos Simplificado - Réplica exacta del sistema original --}}
    <div class="space-y-6 p-6 bg-gray-50 min-h-screen">

        {{-- Header Simplificado --}}
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Sistema de Conjuntos Simplificado</h1>
                    <p class="text-gray-600 mb-4">
                        Gestión profesional de conjuntos para <strong>Venta y Alquiler</strong> únicamente
                    </p>
                    <div class="d-flex align-items-center gap-4 text-sm text-gray-500">
                        <span class="d-flex align-items-center gap-1">
                            <i class="fas fa-clock"></i>
                            Última actualización: {{ now()->format('d/m/Y H:i') }}
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <i class="fas fa-box"></i>
                            {{ $conjuntos->total() }} conjuntos encontrados
                        </span>
                        <span class="d-flex align-items-center gap-1 bg-yellow-100 text-yellow-800 px-2 py-1 rounded">
                            <i class="fas fa-shopping-cart"></i>
                            Solo Venta y Alquiler
                        </span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary d-flex align-items-center gap-2" wire:click="refreshData">
                        <i class="fas fa-sync-alt"></i>
                        Actualizar
                    </button>
                    <button class="btn btn-outline-secondary d-flex align-items-center gap-2" wire:click="exportData">
                        <i class="fas fa-download"></i>
                        Exportar
                    </button>
                    <button class="btn text-gray-900" style="background-color: #facc15; border-color: #facc15;" wire:click="openNewConjuntoModal">
                        <i class="fas fa-plus me-2"></i>
                        Nuevo Conjunto
                    </button>
                </div>
            </div>
        </div>

        {{-- Dashboard de Estadísticas Simplificado --}}
        <div class="row g-4">
            {{-- Total Conjuntos --}}
            <div class="col-lg-2 col-md-4">
                <div class="card h-100 border-0" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border: 1px solid #93c5fd !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-sm font-medium text-blue-700 mb-1">Total Conjuntos</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $estadisticas['total_conjuntos'] ?? 0 }}</p>
                                <p class="text-xs text-blue-600">Activos en sistema</p>
                            </div>
                            <i class="fas fa-layer-group fa-2x text-blue-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Instancias --}}
            <div class="col-lg-2 col-md-4">
                <div class="card h-100 border-0" style="background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%); border: 1px solid #c4b5fd !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-sm font-medium text-purple-700 mb-1">Total Instancias</p>
                                <p class="text-2xl font-bold text-purple-900">{{ $estadisticas['total_instancias'] ?? 0 }}</p>
                                <p class="text-xs text-purple-600">Unidades físicas</p>
                            </div>
                            <i class="fas fa-cube fa-2x text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Disponibles --}}
            <div class="col-lg-2 col-md-4">
                <div class="card h-100 border-0" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #86efac !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-sm font-medium text-green-700 mb-1">Disponibles</p>
                                <p class="text-2xl font-bold text-green-900">{{ $estadisticas['disponibles'] ?? 0 }}</p>
                                <p class="text-xs text-green-600">{{ number_format($estadisticas['porcentaje_disponibles'] ?? 0, 1) }}% del total</p>
                            </div>
                            <i class="fas fa-check-circle fa-2x text-green-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- En Uso --}}
            <div class="col-lg-2 col-md-4">
                <div class="card h-100 border-0" style="background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%); border: 1px solid #fdba74 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-sm font-medium text-orange-700 mb-1">En Uso</p>
                                <p class="text-2xl font-bold text-orange-900">{{ $estadisticas['en_uso'] ?? 0 }}</p>
                                <p class="text-xs text-orange-600">Alquilados/Reservados</p>
                            </div>
                            <i class="fas fa-clock fa-2x text-orange-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ROI Promedio --}}
            <div class="col-lg-2 col-md-4">
                <div class="card h-100 border-0" style="background: linear-gradient(135deg, #fefce8 0%, #fef3c7 100%); border: 1px solid #fde68a !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-sm font-medium text-yellow-700 mb-1">ROI Promedio</p>
                                <p class="text-2xl font-bold text-yellow-900">{{ number_format($estadisticas['roi_promedio'] ?? 0, 1) }}%</p>
                                <p class="text-xs text-yellow-600">Retorno inversión</p>
                            </div>
                            <i class="fas fa-chart-line fa-2x text-yellow-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Valor Inventario --}}
            <div class="col-lg-2 col-md-4">
                <div class="card h-100 border-0" style="background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%); border: 1px solid #fca5a5 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-sm font-medium text-red-700 mb-1">Valor Inventario</p>
                                <p class="text-2xl font-bold text-red-900">{{ number_format($estadisticas['valor_inventario'] ?? 0, 0) }}K</p>
                                <p class="text-xs text-red-600">Miles de Bs.</p>
                            </div>
                            <i class="fas fa-dollar-sign fa-2x text-red-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Controles de Filtros --}}
        <div class="bg-white rounded-lg shadow-sm border p-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-gray-600">Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" wire:model.lazy="searchTerm" placeholder="Código, nombre, descripción...">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-gray-600">Categoría</label>
                    <select class="form-select" wire:model="filterCategoria">
                        <option value="">Todas</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ str_replace('_', ' ', $categoria->nombre) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-gray-600">Género</label>
                    <select class="form-select" wire:model="filterGenero">
                        <option value="">Todos</option>
                        <option value="MASCULINO">Masculino</option>
                        <option value="FEMENINO">Femenino</option>
                        <option value="UNISEX">Unisex</option>
                        <option value="INFANTIL">Infantil</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-gray-600">Ordenar por</label>
                    <select class="form-select" wire:model="sortBy">
                        <option value="nombre">Nombre</option>
                        <option value="codigo">Código</option>
                        <option value="categoria">Categoría</option>
                        <option value="precio_alquiler_dia">Precio Alquiler</option>
                        <option value="created_at">Fecha Creación</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary" wire:click="clearFilters">
                            <i class="fas fa-filter"></i>
                            Limpiar Filtros
                        </button>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" wire:model="vistaActual" value="tarjetas" id="vista-tarjetas" autocomplete="off">
                            <label class="btn btn-outline-primary" for="vista-tarjetas">
                                <i class="fas fa-th-large"></i>
                            </label>
                            <input type="radio" class="btn-check" wire:model="vistaActual" value="lista" id="vista-lista" autocomplete="off">
                            <label class="btn btn-outline-primary" for="vista-lista">
                                <i class="fas fa-list"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grid de Conjuntos --}}
        <div class="row g-4">
            @forelse($conjuntos as $conjunto)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm border-0 hover-shadow-lg" style="transition: all 0.2s ease;">
                        <div class="card-header bg-white border-bottom pb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1 text-lg">{{ $conjunto->nombre }}</h5>
                                    <p class="text-sm text-muted font-monospace mb-2">{{ $conjunto->codigo }}</p>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-ghost btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><h6 class="dropdown-header">Acciones</h6></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="#" wire:click="viewConjunto({{ $conjunto->id }})">
                                                <i class="fas fa-eye me-2"></i> Ver Detalles
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" wire:click="editConjunto({{ $conjunto->id }})">
                                                <i class="fas fa-edit me-2"></i> Editar
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" wire:click="manageInstances({{ $conjunto->id }})">
                                                <i class="fas fa-copy me-2"></i> Crear Instancias
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="#" wire:click="viewStats({{ $conjunto->id }})">
                                                <i class="fas fa-chart-bar me-2"></i> Estadísticas
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" wire:click="viewHistory({{ $conjunto->id }})">
                                                <i class="fas fa-history me-2"></i> Historial
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" wire:click="deleteConjunto({{ $conjunto->id }})">
                                                <i class="fas fa-trash me-2"></i> Eliminar
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <span class="badge bg-{{ $this->getCategoryBadgeColor($conjunto->categoria_conjunto_id) }} bg-opacity-10 text-{{ $this->getCategoryBadgeColor($conjunto->categoria_conjunto_id) }}">
                                    {{ str_replace('_', ' ', $conjunto->categoriaConjunto->nombre ?? 'Sin categoría') }}
                                </span>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $conjunto->genero }}</span>
                                @php
                                    $totalInstancias = $conjunto->instancias_count ?? 0;
                                    $disponibles = $conjunto->variaciones->sum(function($v) {
                                        return $v->instancias->where('estado_disponibilidad', 'DISPONIBLE')->count();
                                    });
                                    $porcentaje = $totalInstancias > 0 ? ($disponibles / $totalInstancias) * 100 : 0;
                                @endphp
                                @if($porcentaje >= 70)
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <i class="fas fa-check-circle me-1"></i> Disponible
                                    </span>
                                @elseif($porcentaje >= 30)
                                    <span class="badge bg-warning bg-opacity-10 text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i> Limitado
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger">
                                        <i class="fas fa-times-circle me-1"></i> Agotado
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 text-sm mb-4">
                                <div class="col-6">
                                    <p class="text-muted mb-1">Variaciones</p>
                                    <p class="fw-bold mb-0">{{ $conjunto->variaciones_count ?? 0 }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-1">Instancias</p>
                                    <p class="fw-bold mb-0">{{ $totalInstancias }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-1">Precio Venta</p>
                                    <p class="fw-bold mb-0">Bs. {{ number_format($conjunto->precio_venta_base, 0) }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-1">Alquiler/Día</p>
                                    <p class="fw-bold mb-0">Bs. {{ number_format($conjunto->precio_alquiler_dia, 0) }}</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between text-sm mb-1">
                                    <span>Disponibilidad</span>
                                    <span class="@if($porcentaje >= 70) text-success @elseif($porcentaje >= 30) text-warning @else text-danger @endif">
                                        {{ $disponibles }}/{{ $totalInstancias }}
                                    </span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar @if($porcentaje >= 70) bg-success @elseif($porcentaje >= 30) bg-warning @else bg-danger @endif"
                                         style="width: {{ $porcentaje }}%"></div>
                                </div>
                            </div>

                            <div class="row g-2 text-xs">
                                <div class="col-3 text-center">
                                    <p class="text-success fw-bold mb-0">{{ $disponibles }}</p>
                                    <p class="text-muted small">Disponibles</p>
                                </div>
                                <div class="col-3 text-center">
                                    @php
                                        $alquilados = $conjunto->variaciones->sum(function($v) {
                                            return $v->instancias->where('estado_disponibilidad', 'ALQUILADO')->count();
                                        });
                                    @endphp
                                    <p class="text-primary fw-bold mb-0">{{ $alquilados }}</p>
                                    <p class="text-muted small">Alquilados</p>
                                </div>
                                <div class="col-3 text-center">
                                    @php
                                        $reservados = $conjunto->variaciones->sum(function($v) {
                                            return $v->instancias->where('estado_disponibilidad', 'RESERVADO')->count();
                                        });
                                    @endphp
                                    <p class="text-warning fw-bold mb-0">{{ $reservados }}</p>
                                    <p class="text-muted small">Reservados</p>
                                </div>
                                <div class="col-3 text-center">
                                    @php
                                        $enLimpieza = $conjunto->variaciones->sum(function($v) {
                                            return $v->instancias->where('estado_disponibilidad', 'EN_LIMPIEZA')->count();
                                        });
                                    @endphp
                                    <p class="text-secondary fw-bold mb-0">{{ $enLimpieza }}</p>
                                    <p class="text-muted small">Limpieza</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-gray-50 border-top-0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex gap-1">
                                    @if($conjunto->disponible_venta)
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-shopping-cart me-1"></i> Venta
                                        </span>
                                    @endif
                                    @if($conjunto->disponible_alquiler)
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="fas fa-clock me-1"></i> Alquiler
                                        </span>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    ROI: <strong class="text-success">{{ number_format(85.5, 1) }}%</strong>
                                </small>
                            </div>
                            {{-- Botones de Acción Específicos --}}
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm flex-fill" wire:click="viewConjunto({{ $conjunto->id }})">
                                    <i class="fas fa-eye me-1"></i>
                                    Detalles
                                </button>
                                <button class="btn btn-outline-secondary btn-sm flex-fill" wire:click="manageInstances({{ $conjunto->id }})">
                                    <i class="fas fa-cubes me-1"></i>
                                    Instancias
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay conjuntos disponibles</h4>
                        <p class="text-muted">Cree su primer conjunto para comenzar a gestionar su inventario.</p>
                        <button class="btn text-gray-900" style="background-color: #facc15; border-color: #facc15;" wire:click="openNewConjuntoModal">
                            <i class="fas fa-plus me-2"></i>
                            Crear Primer Conjunto
                        </button>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        @if($conjuntos->hasPages())
            <div class="d-flex justify-content-center">
                {{ $conjuntos->links() }}
            </div>
        @endif
    </div>

    {{-- Incluir Modales --}}
    @include('livewire.conjunto.modals.nuevo-conjunto-modal')

    @if($showViewConjuntoModal && $selectedConjunto)
        @include('livewire.conjunto.modals.view-conjunto-modal')
    @endif

    @if($showManageInstancesModal && $selectedConjunto)
        @include('livewire.conjunto.modals.manage-instances-modal')
    @endif
</div>

<style>
.hover-shadow-lg:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    transform: translateY(-2px);
}

.progress {
    background-color: #f1f5f9;
}

.card {
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
}

.btn-ghost {
    background: transparent;
    border: none;
    color: #6b7280;
    padding: 0.375rem 0.75rem;
}

.btn-ghost:hover {
    background-color: #f9fafb;
    color: #374151;
}

.text-lg {
    font-size: 1.125rem;
    line-height: 1.75rem;
}

.small {
    font-size: 0.875rem;
}

.space-y-6 > * + * {
    margin-top: 1.5rem;
}

.gap-4 {
    gap: 1rem;
}

.gap-3 {
    gap: 0.75rem;
}

.gap-2 {
    gap: 0.5rem;
}

.gap-1 {
    gap: 0.25rem;
}
</style>

@push('scripts')
<script>
document.addEventListener('livewire:init', () => {
    // Manejar tooltips si es necesario
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush