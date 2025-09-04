<div class="modal fade show" style="display: block;" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="d-flex align-items-center text-white w-100">
                    <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                        <i class="fas fa-shield-alt fa-lg text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Nueva Garantía</h5>
                        <small class="opacity-75">Registrar garantía para alquiler</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" wire:click="closeNewGarantiaModal"></button>
            </div>
            
            <div class="modal-body">
                @if (session()->has('errorModal'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('errorModal') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <!-- Información del Cliente -->
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-user text-primary me-2"></i>
                            Información del Cliente
                        </h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cliente *</label>
                            <select class="form-select @error('cliente_id') is-invalid @enderror" wire:model="cliente_id">
                                <option value="">Seleccione un cliente</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">
                                        {{ $cliente->nombres }} {{ $cliente->apellidos }} - CI: {{ $cliente->carnet_identidad }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Sucursal *</label>
                            <select class="form-select @error('sucursal_id') is-invalid @enderror" wire:model="sucursal_id">
                                @foreach ($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                @endforeach
                            </select>
                            @error('sucursal_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Información de la Garantía -->
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-shield-alt text-success me-2"></i>
                            Detalles de la Garantía
                        </h6>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipo de Garantía *</label>
                            <select class="form-select @error('tipo_garantia_id') is-invalid @enderror" wire:model="tipo_garantia_id">
                                <option value="">Seleccione tipo de garantía</option>
                                @foreach ($tiposGarantia as $tipo)
                                    <option value="{{ $tipo->id }}">
                                        {{ $tipo->nombre }}
                                        @if($tipo->requiere_monto)
                                            - {{ $tipo->rango_monto }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_garantia_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($tipo_garantia_id)
                            @php
                                $tipoSeleccionado = $tiposGarantia->find($tipo_garantia_id);
                            @endphp
                            @if($tipoSeleccionado && $tipoSeleccionado->requiere_monto)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Monto de la Garantía (Bs.) *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs.</span>
                                        <input type="number" 
                                               step="0.01" 
                                               min="{{ $tipoSeleccionado->monto_minimo }}"
                                               @if($tipoSeleccionado->monto_maximo > 0) max="{{ $tipoSeleccionado->monto_maximo }}" @endif
                                               class="form-control @error('monto') is-invalid @enderror" 
                                               wire:model="monto"
                                               placeholder="0.00">
                                        @error('monto')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if($tipoSeleccionado->monto_minimo > 0 || $tipoSeleccionado->monto_maximo > 0)
                                        <small class="form-text text-muted">
                                            Rango permitido: {{ $tipoSeleccionado->rango_monto }}
                                        </small>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Este tipo de garantía no requiere monto monetario.
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Descripción y Documentos -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-bold mb-3 mt-3">
                            <i class="fas fa-file-text text-info me-2"></i>
                            Documentación y Detalles
                        </h6>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Descripción de la Garantía *</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      rows="3"
                                      wire:model="descripcion"
                                      placeholder="Ej: Depósito en efectivo para alquiler de trajes folklóricos..."></textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Documento de Respaldo</label>
                            <input type="text" 
                                   class="form-control @error('documento_respaldo') is-invalid @enderror" 
                                   wire:model="documento_respaldo"
                                   placeholder="Ej: CI 12345678, Recibo #001, etc.">
                            @error('documento_respaldo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones Adicionales</label>
                            <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                      rows="3"
                                      wire:model="observaciones"
                                      placeholder="Observaciones adicionales sobre la garantía..."></textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información del Sistema -->
                @if($tipo_garantia_id)
                    @php
                        $tipoSeleccionado = $tiposGarantia->find($tipo_garantia_id);
                    @endphp
                    <div class="card bg-light mt-3">
                        <div class="card-header py-2">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Información del Proceso
                            </h6>
                        </div>
                        <div class="card-body py-2">
                            <div class="row small">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Días para devolución:</span>
                                        <strong>{{ $tipoSeleccionado->dias_devolucion }} días</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Fecha límite estimada:</span>
                                        <strong>{{ $tipoSeleccionado->calcularFechaVencimiento()->format('d/m/Y') }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Usuario que recibe:</span>
                                        <strong>{{ Auth::user()->name }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Número de ticket:</span>
                                        <strong class="text-success">Se generará automáticamente</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="modal-footer bg-light">
                <div class="d-flex justify-content-between w-100">
                    <div class="d-flex align-items-center text-muted small">
                        <i class="fas fa-info-circle me-2"></i>
                        La garantía quedará registrada y disponible para asignar a alquileres
                    </div>
                    <div>
                        <button type="button" class="btn btn-light me-2" wire:click="closeNewGarantiaModal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="button" 
                                class="btn btn-success btn-lg px-4" 
                                wire:click="saveGarantia"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="fas fa-shield-alt me-2"></i>Registrar Garantía
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin me-2"></i>Registrando...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
</div>