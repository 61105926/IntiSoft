{{-- resources/views/livewire/producto/partials/_form_modal.blade.php --}}
<div
    wire:ignore.self
    class="modal fade"
    id="productoModal"
    tabindex="-1"
    aria-labelledby="productoModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
>
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form wire:submit.prevent="save">
                {{-- Modal Header --}}
                <div class="modal-header">
                    <h5 class="modal-title" id="productoModalLabel">
                        {{ $isEdit ? 'Editar Producto' : 'Nuevo Producto' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="resetForm()" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Variables globales accesibles para el frontend
        window.productos = @json($productosAll);
    </script>
@endpush
