@if($showNewComponenteModal)
<div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>
                    Nuevo Componente
                </h5>
                <button type="button" class="btn-close btn-close-white" wire:click="closeNewComponenteModal"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="saveComponente">
                    <div class="row g-3">
                        <!-- Código y Nombre -->
                        <div class="col-md-4">
                            <label class="form-label">Código <span class="text-danger">*</span></label>
                            <input type="text" wire:model="form.codigo" class="form-control @error('form.codigo') is-invalid @enderror" readonly>
                            @error('form.codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" wire:model="form.nombre" class="form-control @error('form.nombre') is-invalid @enderror" placeholder="Ej: Pollera de Cholita">
                            @error('form.nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Tipo y Género -->
                        <div class="col-md-6">
                            <label class="form-label">Tipo <span class="text-danger">*</span></label>
                            <select wire:model="form.tipo_componente_id" class="form-select @error('form.tipo_componente_id') is-invalid @enderror">
                                <option value="">Seleccione...</option>
                                @foreach($tiposComponente as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                            @error('form.tipo_componente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Género <span class="text-danger">*</span></label>
                            <select wire:model="form.genero" class="form-select">
                                <option value="UNISEX">Unisex</option>
                                <option value="MASCULINO">Masculino</option>
                                <option value="FEMENINO">Femenino</option>
                                <option value="INFANTIL">Infantil</option>
                            </select>
                        </div>

                        <!-- Color -->
                        <div class="col-md-12">
                            <label class="form-label">Color</label>
                            <input type="text" wire:model="form.color" class="form-control" placeholder="Ej: Rojo, Azul, Multicolor">
                        </div>

                        <!-- Descripción -->
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea wire:model="form.descripcion" class="form-control" rows="2" placeholder="Descripción breve"></textarea>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" wire:model="form.activo" id="activo" checked>
                                <label class="form-check-label" for="activo">
                                    <strong>Activo</strong>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeNewComponenteModal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" wire:click="saveComponente">
                    <i class="fas fa-save me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>
@endif