<!-- Modal Nueva Garantía -->
<div class="modal fade show" style="display: block;" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      
      {{-- Header --}}
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title">
          <i class="fas fa-shield-alt me-2"></i>Nueva Garantía
        </h5>
        <button type="button" class="btn-close" wire:click="closeNewGarantiaModal"></button>
      </div>

      {{-- Body --}}
      <div class="modal-body">
        @if (session()->has('errorModal'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('errorModal') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <form wire:submit.prevent="guardar">
          <div class="row g-3">

            {{-- Cliente --}}
            <div class="col-md-6">
              <label for="cliente_id" class="form-label fw-bold">Cliente *</label>
              <select wire:model="cliente_id" id="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror">
                <option value="">🔍 Seleccione un cliente</option>
                @foreach($clientes as $cliente)
                  <option value="{{ $cliente->id }}">{{ $cliente->nombres }} {{ $cliente->apellidos }}</option>
                @endforeach
              </select>
              @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Tipo de Garantía --}}
            <div class="col-md-6">
              <label for="tipo_garantia_id" class="form-label fw-bold">Tipo de Garantía *</label>
              <select wire:model="tipo_garantia_id" id="tipo_garantia_id" class="form-select @error('tipo_garantia_id') is-invalid @enderror">
                <option value="">🛡️ Seleccione tipo</option>
                  <option value="ci">ci</option>
              </select>
              @error('tipo_garantia_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Descripción --}}
            <div class="col-md-8">
              <label for="descripcion" class="form-label fw-bold">Descripción *</label>
              <input type="text" wire:model="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" placeholder="Describa el objeto o servicio en garantía">
              @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Monto --}}
            <div class="col-md-4">
              <label for="monto" class="form-label fw-bold">Monto (Bs.) *</label>
              <input type="number" step="0.01" wire:model="monto" id="monto" class="form-control @error('monto') is-invalid @enderror" placeholder="0.00" min="0">
              @error('monto') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Fecha Recepción --}}
            <div class="col-md-6">
              <label for="fecha_recepcion" class="form-label fw-bold">Fecha de Recepción *</label>
              <input type="date" wire:model="fecha_recepcion" id="fecha_recepcion" class="form-control @error('fecha_recepcion') is-invalid @enderror">
              @error('fecha_recepcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Sucursal --}}
            <div class="col-md-6">
              <label for="sucursal_id" class="form-label fw-bold">Sucursal *</label>
              <select wire:model="sucursal_id" id="sucursal_id" class="form-select @error('sucursal_id') is-invalid @enderror">
                <option value="">🏪 Seleccione una sucursal</option>
                @foreach($sucursales as $sucursal)
                  <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                @endforeach
              </select>
              @error('sucursal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Documento Respaldo --}}
            <div class="col-md-12">
              <label for="documento_respaldo" class="form-label">Documento de Respaldo</label>
              <input type="text" wire:model="documento_respaldo" id="documento_respaldo" class="form-control" placeholder="Número de documento, referencia, etc. (opcional)">
            </div>

            {{-- Observaciones --}}
            <div class="col-md-12">
              <label for="observaciones" class="form-label">Observaciones</label>
              <textarea wire:model="observaciones" id="observaciones" rows="3" class="form-control @error('observaciones') is-invalid @enderror" placeholder="Notas adicionales sobre la garantía..."></textarea>
              @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Vista previa de fecha de vencimiento --}}
            @if($fecha_vencimiento)
              <div class="col-md-12">
                <div class="alert alert-info">
                  <i class="fas fa-info-circle me-2"></i>
                  <strong>Fecha de vencimiento calculada:</strong> {{ \Carbon\Carbon::parse($fecha_vencimiento)->format('d/m/Y') }}
                </div>
              </div>
            @endif

          </div>
        </form>
      </div>

      {{-- Footer --}}
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click="closeNewGarantiaModal">
          <i class="fas fa-times me-1"></i>Cancelar
        </button>
        <button type="button" wire:click="guardar" class="btn btn-warning text-dark">
          <i class="fas fa-save me-1"></i>Registrar Garantía
        </button>
      </div>

    </div>
  </div>
</div>
<div class="modal-backdrop fade show"></div>