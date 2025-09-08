
@section('content')
   <div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Devoluciones - {{ $entrada->nombre_evento }}</h5>
                </div>
                <div class="card-body">
                    @if(session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif
                    
                    @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif
                    
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#productos-tab" role="tab">
                                Productos Entregados ({{ $this->participantesPendientes->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#devueltos-tab" role="tab">
                                Productos Devueltos ({{ $this->participantesDevueltos->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#garantias-tab" role="tab">
                                Garantías Activas ({{ $this->garantiasActivas->count() }})
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-3">
                        <!-- Productos Entregados -->
                        <div class="tab-pane fade show active" id="productos-tab" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Participante</th>
                                            <th>Producto</th>
                                            <th>Entrega</th>
                                            <th>Garantía</th>
                                            <th class="w-1">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($this->participantesPendientes as $participante)
                                        <tr>
                                            <td>
                                                <strong>{{ $participante->nombre_participante }}</strong>
                                                @if($participante->telefono_participante)
                                                <div class="text-muted">{{ $participante->telefono_participante }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $participante->nombre_producto }}</div>
                                                <div class="text-muted">Talla: {{ $participante->talla_solicitada }}</div>
                                            </td>
                                            <td>
                                                @if($participante->fecha_entrega_individual)
                                                <div>{{ $participante->fecha_entrega_individual->format('d/m/Y H:i') }}</div>
                                                @endif
                                                @if($participante->observaciones_entrega)
                                                <div class="text-muted">{{ $participante->observaciones_entrega }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($participante->garantia)
                                                <div>
                                                    <span class="badge {{ $participante->garantia->estado_badge_class }}">
                                                        {{ $participante->garantia->estado_display }}
                                                    </span>
                                                    <div class="text-muted">
                                                        Disponible: Bs. {{ number_format($participante->garantia->monto_disponible, 2) }}
                                                    </div>
                                                </div>
                                                @else
                                                <span class="text-muted">Sin garantía</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-warning" wire:click="abrirModalDevolucion({{ $participante->id }})" title="Procesar Devolución">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M9 14l-4 -4l4 -4"/>
                                                        <path d="M5 10h11a4 4 0 1 1 0 8h-1"/>
                                                    </svg>
                                                    Devolver
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No hay productos entregados pendientes de devolución</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Productos Devueltos -->
                        <div class="tab-pane fade" id="devueltos-tab" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Participante</th>
                                            <th>Producto</th>
                                            <th>Devolución</th>
                                            <th>Penalización</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($this->participantesDevueltos as $participante)
                                        <tr>
                                            <td>
                                                <strong>{{ $participante->nombre_participante }}</strong>
                                                @if($participante->telefono_participante)
                                                <div class="text-muted">{{ $participante->telefono_participante }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $participante->nombre_producto }}</div>
                                                <div class="text-muted">Talla: {{ $participante->talla_solicitada }}</div>
                                            </td>
                                            <td>
                                                @if($participante->fecha_devolucion_individual)
                                                <div>{{ $participante->fecha_devolucion_individual->format('d/m/Y H:i') }}</div>
                                                @endif
                                                @if($participante->observaciones_devolucion)
                                                <div class="text-muted">{{ $participante->observaciones_devolucion }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($participante->penalizacion > 0)
                                                <div class="text-danger">
                                                    Bs. {{ number_format($participante->penalizacion, 2) }}
                                                </div>
                                                @if($participante->motivo_penalizacion)
                                                <div class="text-muted small">{{ $participante->motivo_penalizacion }}</div>
                                                @endif
                                                @else
                                                <span class="text-success">Sin penalización</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $participante->estado_badge_class }}">
                                                    {{ $participante->estado_display }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No hay productos devueltos</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Garantías Activas -->
                        <div class="tab-pane fade" id="garantias-tab" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Participante</th>
                                            <th>Número Garantía</th>
                                            <th>Monto Total</th>
                                            <th>Disponible</th>
                                            <th>Estado</th>
                                            <th class="w-1">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($this->garantiasActivas as $garantia)
                                        <tr>
                                            <td>
                                                <strong>{{ $garantia->nombre_participante }}</strong>
                                                @if($garantia->telefono_participante)
                                                <div class="text-muted">{{ $garantia->telefono_participante }}</div>
                                                @endif
                                            </td>
                                            <td>{{ $garantia->numero_garantia }}</td>
                                            <td>Bs. {{ number_format($garantia->monto_garantia, 2) }}</td>
                                            <td>
                                                <strong class="text-success">
                                                    Bs. {{ number_format($garantia->monto_disponible, 2) }}
                                                </strong>
                                                @if($garantia->monto_usado > 0)
                                                <div class="text-danger small">
                                                    Usado: Bs. {{ number_format($garantia->monto_usado, 2) }}
                                                </div>
                                                @endif
                                                @if($garantia->monto_devuelto > 0)
                                                <div class="text-info small">
                                                    Devuelto: Bs. {{ number_format($garantia->monto_devuelto, 2) }}
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $garantia->estado_badge_class }}">
                                                    {{ $garantia->estado_display }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($garantia->monto_disponible > 0)
                                                <button class="btn btn-sm btn-outline-success" wire:click="abrirModalGarantia({{ $garantia->id }})" title="Devolver Garantía">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M12 5l0 14"/>
                                                        <path d="M5 12l14 0"/>
                                                    </svg>
                                                    Devolver
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No hay garantías activas</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Devolución de Producto -->
    @if($showModal)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Procesar Devolución</h5>
                    <button type="button" class="btn-close" wire:click="cerrarModal"></button>
                </div>
                <div class="modal-body">
                    @if($participanteId)
                    @php
                        $participante = $participantes->find($participanteId);
                    @endphp
                    <div class="mb-3">
                        <strong>Participante:</strong> {{ $participante->nombre_participante }}<br>
                        <strong>Producto:</strong> {{ $participante->nombre_producto }}
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones de Devolución</label>
                        <textarea class="form-control" rows="3" wire:model="observaciones_devolucion" placeholder="Detalles sobre el estado del producto..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Penalización (Bs.)</label>
                            <input type="number" step="0.01" class="form-control @error('penalizacion') is-invalid @enderror" wire:model="penalizacion" min="0">
                            @error('penalizacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Motivo Penalización</label>
                            <input type="text" class="form-control" wire:model="motivo_penalizacion">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cerrarModal">Cancelar</button>
                    <button type="button" class="btn btn-warning" wire:click="procesarDevolucion" wire:loading.attr="disabled">
                        <span wire:loading.remove>Procesar Devolución</span>
                        <span wire:loading>Procesando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
    
    <!-- Modal Devolución de Garantía -->
    @if($showGarantiaModal && $garantiaSeleccionada)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Devolver Garantía</h5>
                    <button type="button" class="btn-close" wire:click="cerrarGarantiaModal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Participante:</strong> {{ $garantiaSeleccionada->nombre_participante }}<br>
                        <strong>Número Garantía:</strong> {{ $garantiaSeleccionada->numero_garantia }}<br>
                        <strong>Monto Disponible:</strong> Bs. {{ number_format($garantiaSeleccionada->monto_disponible, 2) }}
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Monto a Devolver (Bs.) *</label>
                        <input type="number" step="0.01" class="form-control @error('monto_devolver') is-invalid @enderror" wire:model="monto_devolver" min="0.01" max="{{ $garantiaSeleccionada->monto_disponible }}">
                        @error('monto_devolver') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" rows="3" wire:model="observaciones_garantia" placeholder="Motivo de la devolución..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cerrarGarantiaModal">Cancelar</button>
                    <button type="button" class="btn btn-success" wire:click="procesarDevolucionGarantia" wire:loading.attr="disabled">
                        <span wire:loading.remove">Devolver Garantía</span>
                        <span wire:loading>Procesando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>

