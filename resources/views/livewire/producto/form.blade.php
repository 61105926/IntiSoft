{{-- resources/views/livewire/producto/partials/_form_modal.blade.php --}}
<div wire:ignore.self class="modal fade" id="productoModal" tabindex="-1" aria-labelledby="productoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <form wire:submit.prevent="save">
        <div class="modal-header">
          <h5 class="modal-title" id="productoModalLabel">
            {{ $isEdit ? 'Editar Producto' : 'Nuevo Producto' }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            {{-- Nombre --}}
            <div class="col-md-6">
              <label class="form-label">Nombre *</label>
              <input type="text" wire:model.defer="nombre" class="form-control @error('nombre') is-invalid @enderror">
              @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Descripción --}}
            <div class="col-md-6">
              <label class="form-label">Descripción</label>
              <textarea rows="2" wire:model.defer="descripcion" class="form-control @error('descripcion') is-invalid @enderror"></textarea>
              @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Categoría --}}
            <div class="col-md-6">
              <label class="form-label">Categoría *</label>
              <select wire:model.defer="categoria_id_form" class="form-select @error('categoria_id_form') is-invalid @enderror">
                <option value="">Seleccionar categoría</option>
                @foreach($categorias as $c)
                  <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                @endforeach
              </select>
              @error('categoria_id_form')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Sucursal --}}
            <div class="col-md-6">
              <label class="form-label">Sucursal *</label>
              <select wire:model.defer="sucursal_id_form" class="form-select @error('sucursal_id_form') is-invalid @enderror">
                <option value="">Seleccionar sucursal</option>
                @foreach($sucursales as $s)
                  <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                @endforeach
              </select>
              @error('sucursal_id_form')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Precio Venta --}}
            <div class="col-md-6">
              <label class="form-label">Precio Venta</label>
              <input type="number" step="0.01" wire:model.defer="precio_venta" placeholder="0.00" class="form-control @error('precio_venta') is-invalid @enderror">
              @error('precio_venta')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Precio Alquiler --}}
            <div class="col-md-6">
              <label class="form-label">Precio Alquiler</label>
              <input type="number" step="0.01" wire:model.defer="precio_alquiler" placeholder="0.00" class="form-control @error('precio_alquiler') is-invalid @enderror">
              @error('precio_alquiler')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Talla --}}
            <div class="col-md-4">
              <label class="form-label">Talla</label>
              <input type="text" wire:model.defer="talla" placeholder="Ej: S, M, L" class="form-control @error('talla') is-invalid @enderror">
              @error('talla')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Color --}}
            <div class="col-md-4">
              <label class="form-label">Color</label>
              <input type="text" wire:model.defer="color" placeholder="Ej: Rojo" class="form-control @error('color') is-invalid @enderror">
              @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Material --}}
            <div class="col-md-4">
              <label class="form-label">Material</label>
              <input type="text" wire:model.defer="material" placeholder="Ej: Algodón" class="form-control @error('material') is-invalid @enderror">
              @error('material')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Stock Actual --}}
            <div class="col-md-6">
              <label class="form-label">Stock Actual</label>
              <input type="number" wire:model.defer="stock_actual" placeholder="0" class="form-control @error('stock_actual') is-invalid @enderror">
              @error('stock_actual')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Stock Mínimo --}}
            <div class="col-md-6">
              <label class="form-label">Stock Mínimo</label>
              <input type="number" wire:model.defer="stock_minimo" placeholder="1" class="form-control @error('stock_minimo') is-invalid @enderror">
              @error('stock_minimo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Disponible Venta --}}
            <div class="col-md-6">
              <div class="form-check mt-2">
                <input type="checkbox" wire:model.defer="disponible_venta_form" id="dispVenta" class="form-check-input">
                <label for="dispVenta" class="form-check-label">Disponible Venta</label>
              </div>
            </div>

            {{-- Disponible Alquiler --}}
            <div class="col-md-6">
              <div class="form-check mt-2">
                <input type="checkbox" wire:model.defer="disponible_alquiler_form" id="dispAlquiler" class="form-check-input">
                <label for="dispAlquiler" class="form-check-label">Disponible Alquiler</label>
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">
            {{ $isEdit ? 'Actualizar' : 'Guardar' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
