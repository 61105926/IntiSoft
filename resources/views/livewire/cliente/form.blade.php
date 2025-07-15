<div>
    <button type="button" class="btn btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#clienteModal">
        <i class="fas fa-user-plus me-2"></i> Nuevo Cliente
    </button>

    <div wire:ignore.self class="modal fade" id="clienteModal" tabindex="-1" aria-labelledby="newClientModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form wire:submit.prevent="{{ $modalMode === 'edit' ? 'updateCliente' : 'saveCliente' }}"
                class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2 text-warning"></i>
                        {{ $modalMode === 'edit' ? 'Editar Cliente' : 'Registrar Cliente' }}
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    {{-- Tipo Cliente --}}
                    <div class="mb-3">
                        <label class="form-label">Tipo de Cliente</label>
                        <select class="form-select" wire:model="tipo_cliente">
                            <option value="">Seleccione</option>
                            <option value="INDIVIDUAL">Persona Natural</option>
                            <option value="INSTITUCIONAL">Institucional</option>
                        </select>
                        @error('tipo_cliente')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Subtipo institucional --}}
                    @if ($tipo_cliente === 'INSTITUCIONAL')
                        <div class="mb-3">
                            <label class="form-label">Tipo Institucional</label>
                            <select class="form-select" wire:model="institucional_tipo">
                                <option value="">Seleccione</option>
                                <option value="EMPRESA">Empresa</option>
                                <option value="UNIDAD_EDUCATIVA">Unidad Educativa</option>
                            </select>
                            @error('institucional_tipo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    @endif

                    {{-- Persona Natural --}}
                    @if ($tipo_cliente === 'INDIVIDUAL')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nombres</label>
                                <input type="text" class="form-control" wire:model.defer="nombres">
                                @error('nombres')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Apellidos</label>
                                <input type="text" class="form-control" wire:model.defer="apellidos">
                                @error('apellidos')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Carnet de Identidad</label>
                                <input type="text" class="form-control" wire:model.defer="carnet_identidad">
                                @error('carnet_identidad')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    @endif

                    {{-- Empresa --}}
                    @if ($institucional_tipo === 'EMPRESA')
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label>Razón Social</label>
                                <input type="text" class="form-control" wire:model.defer="razon_social">
                                @error('razon_social')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>NIT</label>
                                <input type="text" class="form-control" wire:model.defer="nit">
                                @error('nit')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Teléfono Principal</label>
                                <input type="text" class="form-control" wire:model.defer="telefono_principal">
                                @error('telefono_principal')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Teléfono Secundario</label>
                                <input type="text" class="form-control" wire:model.defer="telefono_secundario">
                                @error('telefono_secundario')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    @endif

                    {{-- Unidad Educativa --}}
                    @if ($institucional_tipo === 'UNIDAD_EDUCATIVA')
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label>Nombre Unidad</label>
                                <input type="text" class="form-control" wire:model.defer="nombre_unidad">
                                @error('nombre_unidad')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Código Unidad</label>
                                <input type="text" class="form-control" wire:model.defer="codigo_unidad">
                                @error('codigo_unidad')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Responsable</label>
                                <input type="text" class="form-control" wire:model.defer="contacto_responsable">
                                @error('contacto_responsable')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Cargo Responsable</label>
                                <input type="text" class="form-control" wire:model.defer="cargo_responsable">
                                @error('cargo_responsable')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Tipo Unidad</label>
                                <select class="form-select" wire:model.defer="tipo_unidad">
                                    <option value="COLEGIO">COLEGIO</option>
                                    <option value="UNIVERSIDAD">UNIVERSIDAD</option>
                                    <option value="INSTITUTO">INSTITUTO</option>
                                    <option value="ACADEMIA">ACADEMIA</option>
                                    <option value="OTRO">OTRO</option>
                                </select>
                                @error('tipo_unidad')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    @endif

                    {{-- Datos comunes --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Teléfono</label>
                            <input type="text" class="form-control" wire:model.defer="telefono">
                            @error('telefono')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" wire:model.defer="email">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Dirección</label>
                            <input type="text" class="form-control" wire:model.defer="direccion">
                            @error('direccion')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Sucursal --}}
                    <div class="mb-3">
                        <label>Sucursal</label>
                        <select class="form-select" wire:model="sucursal_id">
                            <option value="">Seleccione</option>
                            @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                            @endforeach
                        </select>
                        @error('sucursal_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning text-dark">
                        <i class="fas fa-save me-2"></i> Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Feedback rápido --}}
    {{-- Modal de Visualización --}}
    <div wire:ignore.self class="modal fade" id="viewClienteModal" tabindex="-1"
        aria-labelledby="viewClienteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewClienteModalLabel">
                        <i class="fas fa-user me-2"></i> Detalles del Cliente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Tipo de Cliente:</strong> {{ $tipo_cliente }}</p>
                            <p><strong>Sucursal:</strong> {{ optional($sucursales->find($sucursal_id))->nombre }}</p>
                            <p><strong>Estado:</strong> {{ $activo ? 'Activo' : 'Inactivo' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Correo Electrónico:</strong> {{ $email ?? '—' }}</p>
                            <p><strong>Dirección:</strong> {{ $direccion ?? '—' }}</p>
                            <p><strong>Teléfono:</strong> {{ $telefono ?? '—' }}</p>
                        </div>
                    </div>

                    <hr>

                    @if ($tipo_cliente === 'INDIVIDUAL')
                        <h6 class="mb-3">Datos Individuales</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nombres:</strong> {{ $nombres }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Apellidos:</strong> {{ $apellidos }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>C.I.:</strong> {{ $carnet_identidad }}</p>
                            </div>
                        </div>
                    @elseif ($institucional_tipo === 'EMPRESA')
                        <h6 class="mb-3">Datos de Empresa</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Razón Social:</strong> {{ $razon_social }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>NIT:</strong> {{ $nit }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Teléfono Principal:</strong> {{ $telefono_principal }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Teléfono Secundario:</strong> {{ $telefono_secundario ?? '—' }}</p>
                            </div>
                        </div>
                    @elseif ($institucional_tipo === 'UNIDAD_EDUCATIVA')
                        <h6 class="mb-3">Datos de Unidad Educativa</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nombre de Unidad:</strong> {{ $nombre_unidad }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Código:</strong> {{ $codigo_unidad }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Tipo de Unidad:</strong> {{ $tipo_unidad }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Teléfono:</strong> {{ $telefono_principal }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Responsable:</strong> {{ $contacto_responsable }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Cargo del Responsable:</strong> {{ $cargo_responsable }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>



    {{-- Scripts para abrir/cerrar modales --}}
    <script>
        window.addEventListener('showClienteModal', event => {
            var modal = new bootstrap.Modal(document.getElementById('clienteModal'));
            modal.show();
        });

        window.addEventListener('closeClienteModal', event => {
            var modalEl = document.getElementById('clienteModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        });

        window.addEventListener('showViewClienteModal', event => {
            var modal = new bootstrap.Modal(document.getElementById('viewClienteModal'));
            modal.show();
        });

        window.addEventListener('closeViewClienteModal', event => {
            var modalEl = document.getElementById('viewClienteModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        });
    </script>
</div>
