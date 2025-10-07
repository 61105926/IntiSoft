{{-- Modal Crear Instancias Masivas - Réplica exacta del sistema original --}}
@if($showManageInstancesModal && $selectedConjunto)
<div class="modal fade show" style="display: block;" tabindex="-1">
    <div class="modal-dialog modal-lg" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-copy me-2"></i>
                    Crear Instancias Masivas
                </h5>
                <button type="button" class="btn-close" wire:click="$set('showManageInstancesModal', false)"></button>
            </div>
            <div class="modal-body">
                {{-- ALERTAS DE ERROR/ÉXITO --}}
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Crear múltiples instancias físicas del conjunto "{{ $selectedConjunto->nombre }}" con verificación automática de componentes.
                </div>

                {{-- Formulario de Creación de Instancias --}}
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Variación</label>
                        <select class="form-select" wire:model="instanceForm.variacion_id">
                            <option value="">Seleccione variación</option>
                            @if($selectedConjunto->variaciones)
                                @foreach($selectedConjunto->variaciones as $variacion)
                                    @php
                                        $totalInstancias = count($variacion->instancias ?? []);
                                        $disponibles = collect($variacion->instancias ?? [])->where('estado_disponibilidad', 'DISPONIBLE')->count();
                                    @endphp
                                    <option value="{{ $variacion->id }}">
                                        {{ $variacion->codigo ?? 'SIN-CÓDIGO' }} - {{ $variacion->talla }} {{ $variacion->color }} ({{ $disponibles }}/{{ $totalInstancias }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Sucursal</label>
                        <select class="form-select" wire:model="instanceForm.sucursal_id">
                            <option value="">Seleccione sucursal</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Cantidad</label>
                        <div class="d-flex gap-2">
                            <input type="number" class="form-control" wire:model="instanceForm.cantidad" min="1" max="50" placeholder="1">
                            <button class="btn btn-outline-primary" type="button" wire:click="verificarDisponibilidad">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        </div>
                        <div class="form-text">Cantidad de instancias a crear (máximo 50)</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Prefijo Serie</label>
                        <input type="text" class="form-control" wire:model="instanceForm.prefijo_serie" placeholder="INST">
                        <div class="form-text">Prefijo para los números de serie</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Lote Fabricación</label>
                        <input type="text" class="form-control" wire:model="instanceForm.lote_fabricacion" placeholder="LOTE-2024-001">
                        <div class="form-text">Identificador del lote de fabricación</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Estado Físico Inicial</label>
                        <select class="form-select" wire:model="instanceForm.estado_fisico">
                            <option value="EXCELENTE">Excelente</option>
                            <option value="BUENO">Bueno</option>
                            <option value="REGULAR">Regular</option>
                            <option value="MALO">Malo</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Observaciones</label>
                        <textarea class="form-control" wire:model="instanceForm.observaciones" rows="3" placeholder="Observaciones adicionales sobre las instancias..."></textarea>
                    </div>
                </div>

                {{-- Verificación de Disponibilidad --}}
                @if($this->disponibilidadVerificada)
                    <div class="alert {{ $this->disponibilidadVerificada['disponible'] ? 'alert-success' : 'alert-danger' }} mt-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            @if($this->disponibilidadVerificada['disponible'])
                                <i class="fas fa-check-circle text-success"></i>
                                <span class="fw-bold text-success">Componentes Disponibles</span>
                            @else
                                <i class="fas fa-exclamation-triangle text-danger"></i>
                                <span class="fw-bold text-danger">Componentes Insuficientes</span>
                            @endif
                        </div>
                        <p class="mb-2">
                            Cantidad máxima posible: <strong>{{ $this->disponibilidadVerificada['cantidad_maxima'] ?? 0 }}</strong>
                        </p>
                        @if(isset($this->disponibilidadVerificada['componentes_limitantes']) && count($this->disponibilidadVerificada['componentes_limitantes']) > 0)
                            <div>
                                <p class="fw-bold text-danger mb-1">Componentes limitantes:</p>
                                @foreach($this->disponibilidadVerificada['componentes_limitantes'] as $comp)
                                    <p class="small text-danger mb-1">
                                        • {{ $comp['nombre'] ?? 'Componente' }}: {{ $comp['disponibles'] ?? 0 }} disponibles
                                    </p>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Previsualización de Instancias --}}
                @if($this->instanceForm['cantidad'] > 0 && $this->instanceForm['variacion_id'] && $this->instanceForm['prefijo_serie'])
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-eye me-2"></i>
                                Previsualización de Instancias
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Se crearán las siguientes instancias:</p>
                            <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Número Serie</th>
                                            <th>Código Interno</th>
                                            <th>Estado</th>
                                            <th>Ubicación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($i = 1; $i <= min($this->instanceForm['cantidad'], 10); $i++)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary font-monospace">
                                                        {{ $this->instanceForm['prefijo_serie'] }}-{{ str_pad($this->instanceForm['variacion_id'], 3, '0', STR_PAD_LEFT) }}-{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info bg-opacity-10 text-info font-monospace">
                                                        INT-{{ $this->instanceForm['variacion_id'] }}-{{ $i }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">{{ $this->instanceForm['estado_fisico'] ?? 'EXCELENTE' }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">Por asignar</span>
                                                </td>
                                            </tr>
                                        @endfor
                                        @if($this->instanceForm['cantidad'] > 10)
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">
                                                    ... y {{ $this->instanceForm['cantidad'] - 10 }} instancias más
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Resumen de Creación --}}
                @if($this->instanceForm['cantidad'] > 0)
                    <div class="row g-3 mt-4">
                        <div class="col-md-3">
                            <div class="p-3 bg-primary bg-opacity-10 rounded text-center">
                                <p class="small text-primary mb-1">Total Instancias</p>
                                <p class="h5 fw-bold text-primary mb-0">{{ $this->instanceForm['cantidad'] }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-success bg-opacity-10 rounded text-center">
                                <p class="small text-success mb-1">Estado Inicial</p>
                                <p class="h6 fw-bold text-success mb-0">{{ $this->instanceForm['estado_fisico'] ?? 'EXCELENTE' }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-warning bg-opacity-10 rounded text-center">
                                <p class="small text-warning mb-1">Disponibilidad</p>
                                <p class="h6 fw-bold text-warning mb-0">DISPONIBLE</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-info bg-opacity-10 rounded text-center">
                                <p class="small text-info mb-1">Costo Estimado</p>
                                <p class="h6 fw-bold text-info mb-0">
                                    @if($selectedConjunto->precio_venta_base)
                                        Bs. {{ number_format($selectedConjunto->precio_venta_base * ($this->instanceForm['cantidad'] ?? 0), 0) }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="$set('showManageInstancesModal', false)">
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary" wire:click="crearInstanciasMasivas"
                        @if(!$this->puedeCrearInstancias()) disabled @endif>
                    <i class="fas fa-plus me-2"></i>
                    Crear {{ $this->instanceForm['cantidad'] ?? 0 }} Instancia(s)
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>
@endif