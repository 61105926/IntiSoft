<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Participantes - {{ $entrada->nombre_evento }}</h5>
                <button class="btn btn-primary btn-sm" wire:click="abrirModal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 5l0 14"/>
                        <path d="M5 12l14 0"/>
                    </svg>
                    Agregar Participante
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Participante</th>
                            <th>Producto</th>
                            <th>Talla</th>
                            <th>Precio</th>
                            <th>Garantía</th>
                            <th>Estado</th>
                            <th class="w-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($participantes as $participante)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $participante->nombre_participante }}</strong>
                                    @if($participante->telefono_participante)
                                    <div class="text-muted">{{ $participante->telefono_participante }}</div>
                                    @endif
                                    @if($participante->documento_identidad)
                                    <div class="text-muted">CI: {{ $participante->documento_identidad }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>{{ $participante->nombre_producto }}</div>
                                <div class="text-muted">{{ $participante->codigo_producto }}</div>
                            </td>
                            <td>{{ $participante->talla_solicitada }}</td>
                            <td>Bs. {{ number_format($participante->precio_unitario, 2) }}</td>
                            <td>
                                @if($participante->garantia)
                                <div>
                                    <span class="badge {{ $participante->garantia->estado_badge_class }}">
                                        {{ $participante->garantia->estado_display }}
                                    </span>
                                    <div class="text-muted">Bs. {{ number_format($participante->garantia->monto_garantia, 2) }}</div>
                                    <div class="text-muted">{{ $participante->garantia->numero_garantia }}</div>
                                </div>
                                @else
                                <span class="text-muted">Sin garantía</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $participante->estado_badge_class }}">
                                    {{ $participante->estado_display }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" wire:click="editarParticipante({{ $participante->id }})" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                            <path d="M16 5l3 3"/>
                                        </svg>
                                    </button>
                                    @if($participante->estado === 'PENDIENTE')
                                    <button class="btn btn-sm btn-outline-success" wire:click="marcarComoEntregado({{ $participante->id }})" title="Marcar como Entregado">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5 12l5 5l10 -10"/>
                                        </svg>
                                    </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline-danger" wire:click="eliminarParticipante({{ $participante->id }})" 
                                            onclick="return confirm('¿Está seguro de eliminar este participante?')" title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M4 7l16 0"/>
                                            <path d="M10 11l0 6"/>
                                            <path d="M14 11l0 6"/>
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay participantes registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Modal Participante -->
    @if($showModal)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $editMode ? 'Editar' : 'Agregar' }} Participante
                    </h5>
                    <button type="button" class="btn-close" wire:click="cerrarModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre Participante *</label>
                            <input type="text" class="form-control @error('nombre_participante') is-invalid @enderror" wire:model="nombre_participante">
                            @error('nombre_participante') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" wire:model="telefono_participante">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Documento</label>
                            <input type="text" class="form-control" wire:model="documento_identidad">
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Producto *</label>
                            <select class="form-select @error('producto_id') is-invalid @enderror" wire:model="producto_id" wire:change="actualizarPrecio">
                                <option value="">Seleccione producto</option>
                                @foreach($productos as $producto)
                                <option value="{{ $producto->id }}">{{ $producto->nombre }} - {{ $producto->codigo }}</option>
                                @endforeach
                            </select>
                            @error('producto_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Talla Solicitada</label>
                            <input type="text" class="form-control" wire:model="talla_solicitada">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Precio (Bs.) *</label>
                            <input type="number" step="0.01" class="form-control @error('precio_unitario') is-invalid @enderror" wire:model="precio_unitario" min="0">
                            @error('precio_unitario') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" rows="2" wire:model="observaciones_participante"></textarea>
                        </div>
                    </div>
                    
                    <hr>
                    <h6>Garantía Individual</h6>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model="crear_garantia">
                                <label class="form-check-label">Crear garantía para este participante</label>
                            </div>
                        </div>
                    </div>
                    
                    @if($crear_garantia)
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Monto Garantía (Bs.) *</label>
                            <input type="number" step="0.01" class="form-control @error('monto_garantia') is-invalid @enderror" wire:model="monto_garantia" min="0">
                            @error('monto_garantia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Método de Pago</label>
                            <select class="form-select" wire:model="metodo_pago_garantia">
                                <option value="EFECTIVO">Efectivo</option>
                                <option value="TRANSFERENCIA">Transferencia</option>
                                <option value="TARJETA">Tarjeta</option>
                                <option value="QR">QR</option>
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cerrarModal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="guardar" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $editMode ? 'Actualizar' : 'Agregar' }} Participante</span>
                        <span wire:loading>Guardando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>