<div>
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fas fa-store-alt fa-lg me-2 text-primary"></i>
                <h4 class="mb-0">Sucursales</h4>
            </div>
            <button class="btn btn-warning text-dark" wire:click="crear">
                <i class="fas fa-plus me-1"></i> Nueva Sucursal
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h5><i class="fas fa-list me-2 text-primary"></i>Lista de Sucursales</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th>Dirección</th>
                        <th>Contacto</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sucursales as $sucursal)
                        <tr>
                            <td>{{ $sucursal->nombre }}</td>
                            <td>{{ $sucursal->codigo }}</td>
                            <td>{{ $sucursal->direccion }}</td>
                            <td>
                                <div><i class="fas fa-phone me-1 text-muted"></i>{{ $sucursal->telefono }}</div>
                                <div><i class="fas fa-user me-1 text-muted"></i>{{ $sucursal->responsable }}</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $sucursal->activa ? 'primary' : 'secondary' }}">
                                    {{ $sucursal->activa ? 'ACTIVO' : 'INACTIVO' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-outline-secondary" wire:click="editar({{ $sucursal->id }})" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" wire:click="desactivar({{ $sucursal->id }})" title="Activar/Desactivar">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No hay sucursales registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Bootstrap -->
    <div  class="modal fade @if($mostrar_modal) show d-block @endif" tabindex="-1" style="@if($mostrar_modal) display: block; @else display: none; @endif background-color: rgba(0,0,0,0.5);" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $modo_edicion ? 'Editar Sucursal' : 'Nueva Sucursal' }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('mostrar_modal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nombre *</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" wire:model="nombre">
                            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Código *</label>
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror" wire:model="codigo">
                            @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">Responsable</label>
                            <input type="text" class="form-control" wire:model="responsable">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" wire:model="telefono">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" wire:model="direccion">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="$set('mostrar_modal', false)">Cancelar</button>
                    <button class="btn btn-warning text-dark" wire:click="guardar">
                        <i class="fas fa-save me-1"></i> {{ $modo_edicion ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
