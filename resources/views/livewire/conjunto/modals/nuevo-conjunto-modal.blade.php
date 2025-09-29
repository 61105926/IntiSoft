{{-- Modal Nuevo Conjunto - Réplica exacta del sistema original --}}
@if($showNewConjuntoModal)
<div class="modal fade show" style="display: block;" tabindex="-1">
    <div class="modal-dialog modal-xl" style="max-width: 1000px;">
        <div class="modal-content" style="max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-tie me-2"></i>
                    Nuevo Conjunto - Solo Venta y Alquiler
                </h5>
                <button type="button" class="btn-close" wire:click="closeNewConjuntoModal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">
                    Cree un nuevo conjunto completo con componentes y variaciones. Sistema simplificado para venta y alquiler únicamente.
                </p>

                {{-- Pestañas --}}
                <ul class="nav nav-tabs" id="conjuntoTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                            General
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="componentes-tab" data-bs-toggle="tab" data-bs-target="#componentes" type="button" role="tab">
                            Componentes
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="variaciones-tab" data-bs-toggle="tab" data-bs-target="#variaciones" type="button" role="tab">
                            Variaciones
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="configuracion-tab" data-bs-toggle="tab" data-bs-target="#configuracion" type="button" role="tab">
                            Configuración
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-4" id="conjuntoTabsContent">
                    {{-- Pestaña General --}}
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="space-y-4">
                                    <div class="row align-items-center mb-3">
                                        <label class="col-sm-3 col-form-label text-end">Código</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" wire:model="newConjunto.codigo" placeholder="TF-CAP-M-001">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <label class="col-sm-3 col-form-label text-end">Nombre</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" wire:model="newConjunto.nombre">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <label class="col-sm-3 col-form-label text-end">Categoría</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" wire:model="newConjunto.categoria_conjunto_id">
                                                <option value="">Seleccione categoría</option>
                                                @foreach($categorias as $categoria)
                                                    <option value="{{ $categoria->id }}">{{ str_replace('_', ' ', $categoria->nombre) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <label class="col-sm-3 col-form-label text-end">Género</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" wire:model="newConjunto.genero">
                                                <option value="MASCULINO">Masculino</option>
                                                <option value="FEMENINO">Femenino</option>
                                                <option value="UNISEX">Unisex</option>
                                                <option value="INFANTIL">Infantil</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="space-y-4">
                                    <div class="row align-items-center mb-3">
                                        <label class="col-sm-4 col-form-label text-end">Precio Venta Base</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" wire:model="newConjunto.precio_venta_base" step="0.01">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <label class="col-sm-4 col-form-label text-end">Precio Alquiler/Día</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" wire:model="newConjunto.precio_alquiler_dia" step="0.01">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <label class="col-sm-4 col-form-label text-end">Precio Alquiler/Semana</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" wire:model="newConjunto.precio_alquiler_semana" step="0.01">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <label class="col-sm-4 col-form-label text-end">Precio Alquiler/Mes</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" wire:model="newConjunto.precio_alquiler_mes" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <label class="col-sm-2 col-form-label text-end">Descripción</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" wire:model="newConjunto.descripcion" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Pestaña Componentes --}}
                    <div class="tab-pane fade" id="componentes" role="tabpanel">
                        <h6 class="mb-3">Seleccione los componentes que forman este conjunto</h6>
                        <div class="row">
                            @foreach($tiposComponente as $tipoComponente)
                                <div class="col-md-6 mb-3">
                                    <div class="card {{ in_array($tipoComponente->id, $componentesSeleccionados) ? 'border-primary bg-light' : '' }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="checkbox"
                                                           value="{{ $tipoComponente->id }}"
                                                           wire:model="componentesSeleccionados"
                                                           id="componente_{{ $tipoComponente->id }}">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $tipoComponente->nombre }}</h6>
                                                    <p class="text-muted mb-1 small">{{ $tipoComponente->descripcion }}</p>
                                                    @if($tipoComponente->es_obligatorio_defecto)
                                                        <span class="badge bg-warning">Obligatorio por defecto</span>
                                                    @endif
                                                </div>
                                                <i class="fas fa-{{ $tipoComponente->icono }} fa-2x text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(count($componentesSeleccionados) > 0)
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ count($componentesSeleccionados) }} componente(s) seleccionado(s)
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Debe seleccionar al menos un componente para crear el conjunto.
                            </div>
                        @endif
                    </div>

                    {{-- Pestaña Variaciones --}}
                    <div class="tab-pane fade" id="variaciones" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6>Variaciones del Conjunto</h6>
                            <button type="button" class="btn btn-primary btn-sm" wire:click="agregarVariacion">
                                <i class="fas fa-plus me-2"></i>Agregar Variación
                            </button>
                        </div>

                        @if(count($variaciones) > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Talla</th>
                                            <th>Color</th>
                                            <th>Estilo</th>
                                            <th>Precio Venta</th>
                                            <th>Precio Alquiler/Día</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($variaciones as $index => $variacion)
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm"
                                                           wire:model="variaciones.{{ $index }}.talla" placeholder="S, M, L...">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm"
                                                           wire:model="variaciones.{{ $index }}.color" placeholder="Dorado, Plateado...">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm"
                                                           wire:model="variaciones.{{ $index }}.estilo" placeholder="Clásico, Moderno...">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm"
                                                           wire:model="variaciones.{{ $index }}.precio_venta" step="0.01">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm"
                                                           wire:model="variaciones.{{ $index }}.precio_alquiler_dia" step="0.01">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                                            wire:click="eliminarVariacion({{ $index }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                No hay variaciones agregadas. Agregue al menos una variación para crear instancias específicas del conjunto.
                            </div>
                        @endif
                    </div>

                    {{-- Pestaña Configuración --}}
                    <div class="tab-pane fade" id="configuracion" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Modalidades de Negocio</h6>
                                <div class="space-y-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" wire:model="newConjunto.disponible_venta" id="disponible_venta">
                                        <label class="form-check-label d-flex align-items-center" for="disponible_venta">
                                            <i class="fas fa-shopping-cart me-2"></i>
                                            Disponible para Venta
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" wire:model="newConjunto.disponible_alquiler" id="disponible_alquiler">
                                        <label class="form-check-label d-flex align-items-center" for="disponible_alquiler">
                                            <i class="fas fa-clock me-2"></i>
                                            Disponible para Alquiler
                                        </label>
                                    </div>
                                </div>
                                <div class="alert alert-warning mt-3">
                                    <p class="mb-0"><strong>Sistema Simplificado:</strong> Solo manejamos venta y alquiler. El inventario tradicional no es necesario en este sistema de conjuntos.</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="mb-3">Configuración Operativa</h6>
                                <div class="space-y-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" wire:model="newConjunto.requiere_limpieza" id="requiere_limpieza">
                                        <label class="form-check-label" for="requiere_limpieza">Requiere Limpieza</label>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <label class="col-sm-5 col-form-label text-end">Tiempo Limpieza (hrs)</label>
                                        <div class="col-sm-7">
                                            <input type="number" class="form-control" wire:model="newConjunto.tiempo_limpieza_horas">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <label class="col-sm-5 col-form-label text-end">Peso Aprox. (kg)</label>
                                        <div class="col-sm-7">
                                            <input type="number" class="form-control" wire:model="newConjunto.peso_aproximado" step="0.1">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <label class="col-sm-5 col-form-label text-end">Temporada</label>
                                        <div class="col-sm-7">
                                            <select class="form-select" wire:model="newConjunto.temporada">
                                                <option value="VERANO">Verano</option>
                                                <option value="INVIERNO">Invierno</option>
                                                <option value="TODO_ANIO">Todo el Año</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center mt-4">
                            <label class="col-sm-2 col-form-label text-end">Observaciones</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" wire:model="newConjunto.observaciones" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeNewConjuntoModal">Cancelar</button>
                <button type="button" class="btn text-gray-900" style="background-color: #facc15; border-color: #facc15;"
                        wire:click="guardarConjunto"
                        @if(!$this->puedeGuardarConjunto()) disabled @endif>
                    <i class="fas fa-save me-2"></i>Crear Conjunto
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>

<style>
.space-y-3 > * + * {
    margin-top: 0.75rem;
}

.space-y-4 > * + * {
    margin-top: 1rem;
}
</style>
@endif