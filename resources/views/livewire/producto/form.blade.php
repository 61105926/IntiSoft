{{-- Modal Produto - Igual estructura que alquiler --}}
@if ($showModal)
    <div class="modal fade show" id="productoModal" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
            <form wire:submit.prevent="save" enctype="multipart/form-data">
                {{-- Modal Header --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-{{ $isEdit ? 'edit' : 'plus' }} me-2"></i>{{ $isEdit ? 'Editar Produto' : 'Nuevo Produto' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="resetForm"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row g-3">
                        @if ($isEdit)
                            <div class="col-md-6">
                                <label class="form-label">Nombre *</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $nombre ?? 'Nombre no disponible' }}"
                                    disabled
                                />
                            </div>
                        @else
                            {{-- Nombre con Select2 (solo si no estás editando) --}}
                            <div class="col-md-6">
                                <label class="form-label">Nombre *</label>
                                <div wire:ignore>
                                    <div id="vue-app" key="vue-{{ uniqid() }}">
                                        <producto-select></producto-select>
                                    </div>
                                </div>
                                @error('productoSeleccionadoId')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        {{-- Descripción --}}
                        <div class="col-md-6">
                            <label class="form-label">Descripción</label>
                            <textarea
                                rows="2"
                                wire:model.defer="descripcion"
                                class="form-control @error('descripcion') is-invalid @enderror"
                                {{ $productosExistentes ? 'disabled' : '' }}
                            ></textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Categoría --}}
                        <div class="col-md-6">
                            <label class="form-label">Categoría *</label>
                            <select
                                wire:model.defer="categoria_id_form"
                                class="form-select @error('categoria_id_form') is-invalid @enderror"
                                {{ $productosExistentes ? 'disabled' : '' }}
                            >
                                <option value="">Seleccionar categoría</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                            @error('categoria_id_form')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Sucursal --}}
                        <div class="col-md-6">
                            <label class="form-label">Sucursal *</label>
                            <select
                                wire:model="sucursal_id_form"
                                class="form-select @error('sucursal_id_form') is-invalid @enderror"
                                {{ $isEdit ? 'disabled' : '' }}
                            >
                                <option value="" @if ($sucursal_id_form != '') disabled @endif>
                                    Seleccionar sucursal
                                </option>
                                @foreach ($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                @endforeach
                            </select>
                            @error('sucursal_id_form')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Precio Venta --}}
                        <div class="col-md-6">
                            <label class="form-label">Precio Venta</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model.defer="precio_venta"
                                placeholder="0.00"
                                class="form-control @error('precio_venta') is-invalid @enderror"
                            />
                            @error('precio_venta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Precio Alquiler --}}
                        <div class="col-md-6">
                            <label class="form-label">Precio Alquiler</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model.defer="precio_alquiler"
                                placeholder="0.00"
                                class="form-control @error('precio_alquiler') is-invalid @enderror"
                            />
                            @error('precio_alquiler')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Talla --}}
                        <div class="col-md-4">
                            <label class="form-label">Talla</label>
                            <input
                                type="text"
                                wire:model.defer="talla"
                                placeholder="Ej: S, M, L"
                                class="form-control @error('talla') is-invalid @enderror"
                                {{ $productosExistentes ? 'disabled' : '' }}
                                {{ $isEdit ? 'disabled' : '' }}
                            />
                            @error('talla')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Color --}}
                        <div class="col-md-4">
                            <label class="form-label">Color</label>
                            <input
                                type="text"
                                wire:model.defer="color"
                                placeholder="Ej: Rojo"
                                class="form-control @error('color') is-invalid @enderror"
                                {{ $productosExistentes ? 'disabled' : '' }}
                            />
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Material --}}
                        <div class="col-md-4">
                            <label class="form-label">Material</label>
                            <input
                                type="text"
                                wire:model.defer="material"
                                placeholder="Ej: Algodón"
                                class="form-control @error('material') is-invalid @enderror"
                                {{ $productosExistentes ? 'disabled' : '' }}
                            />
                            @error('material')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Código de Barras --}}
                        <div class="col-md-12">
                            <label class="form-label">Código de Barras</label>
                            <input
                                type="text"
                                wire:model.defer="codigo_barras"
                                placeholder="Código de barras del producto"
                                class="form-control @error('codigo_barras') is-invalid @enderror"
                            />
                            @error('codigo_barras')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Imagen del Producto --}}
                        <div class="col-md-12">
                            <label class="form-label">Imagen del Producto</label>
                            <input
                                type="file"
                                wire:model="imagen_principal"
                                accept="image/*"
                                class="form-control @error('imagen_principal') is-invalid @enderror"
                                id="imageInput"
                                onchange="previewImage(this)"
                            />
                            @error('imagen_principal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div wire:loading wire:target="imagen_principal" class="mt-2">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <small class="text-muted ms-2">Subiendo imagen...</small>
                            </div>

                            {{-- Vista previa --}}
                            <div id="imagePreview" class="mt-3 text-center" style="display: none;">
                                <img id="previewImg" 
                                     alt="Vista previa" 
                                     class="img-thumbnail" 
                                     style="max-width: 250px; max-height: 250px; object-fit: cover;">
                            </div>
                            
                            @if ($isEdit && $producto_id && !$imagen_principal)
                                @php
                                    $producto = \App\Models\Producto::find($producto_id);
                                @endphp
                                @if ($producto && $producto->imagen_principal)
                                    <div class="mt-3 text-center">
                                        <small class="text-muted d-block">Imagen actual:</small>
                                        <img src="{{ asset('storage/' . $producto->imagen_principal) }}" 
                                             alt="Imagen actual" 
                                             class="img-thumbnail mt-1" 
                                             style="max-width: 250px; max-height: 250px; object-fit: cover;"
                                             onerror="this.src='{{ asset('images/produto-default.jpg') }}'">
                                    </div>
                                @else
                                    <div class="mt-3 text-center">
                                        <small class="text-muted d-block">Sin imagen:</small>
                                        <img src="{{ asset('images/produto-default.jpg') }}" 
                                             alt="Sin imagen" 
                                             class="img-thumbnail mt-1" 
                                             style="max-width: 250px; max-height: 250px; object-fit: cover;">
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- Stock Actual --}}
                        <div class="col-md-6">
                            <label class="form-label">Stock Actual</label>
                            <input
                                type="number"
                                wire:model.defer="stock_actual"
                                placeholder="0"
                                class="form-control @error('stock_actual') is-invalid @enderror"
                                {{ $isEdit ? 'disabled' : '' }}
                            />
                            @error('stock_actual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Stock Mínimo --}}
                        <div class="col-md-6">
                            <label class="form-label">Stock Mínimo</label>
                            <input
                                type="number"
                                wire:model.defer="stock_minimo"
                                placeholder="1"
                                class="form-control @error('stock_minimo') is-invalid @enderror"
                            />
                            @error('stock_minimo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Disponible Venta --}}
                        <div class="col-md-6">
                            <div class="form-check mt-2">
                                <input
                                    type="checkbox"
                                    wire:model.defer="disponible_venta_form"
                                    id="dispVenta"
                                    class="form-check-input"
                                />
                                <label for="dispVenta" class="form-check-label">Disponible Venta</label>
                            </div>
                        </div>

                        {{-- Disponible Alquiler --}}
                        <div class="col-md-6">
                            <div class="form-check mt-2">
                                <input
                                    type="checkbox"
                                    wire:model.defer="disponible_alquiler_form"
                                    id="dispAlquiler"
                                    class="form-check-input"
                                />
                                <label for="dispAlquiler" class="form-check-label">Disponible Alquiler</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="resetForm">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-{{ $isEdit ? 'save' : 'plus' }} me-2"></i>{{ $isEdit ? 'Actualizar Produto' : 'Crear Produto' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>
@endif

@push('scripts')
    <script>
        // Variables globales accesibles para el frontend
        window.productos = @json($productosAll);
        
        // Función para vista previa de imagen
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }

        // Mejorar el manejo de archivos y previews
        document.addEventListener('DOMContentLoaded', function() {
            // El scroll ya está manejado por modal-dialog-scrollable
            // Removemos la interferencia con overflow del body
        });

        // Livewire hooks para manejar archivos
        document.addEventListener('livewire:load', function () {
            // Limpiar previews cuando se cierra el modal
            Livewire.on('hideModal', function() {
                // Reset file input y vista previa
                const imageInput = document.getElementById('imageInput');
                const imagePreview = document.getElementById('imagePreview');
                
                if (imageInput) imageInput.value = '';
                if (imagePreview) imagePreview.style.display = 'none';
            });
        });
    </script>
@endpush

@push('styles')
  
@endpush
