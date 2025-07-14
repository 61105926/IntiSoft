<div>
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex align-items-center">
            <i class="fas fa-building fa-lg me-2 text-primary"></i>
            <h3 class="mb-0">Información de la Empresa</h3>
        </div>

        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nombre_legal" class="form-label">
                        <i class="fas fa-id-card me-1 text-secondary"></i>Nombre Legal
                    </label>
                    <input type="text" class="form-control @error('nombre_legal') is-invalid @enderror" id="nombre_legal" wire:model="nombre_legal">
                    @error('nombre_legal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="razon_social" class="form-label">
                        <i class="fas fa-briefcase me-1 text-secondary"></i>Razón Social <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('razon_social') is-invalid @enderror" id="razon_social" wire:model="razon_social">
                    @error('razon_social') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="nit" class="form-label">
                        <i class="fas fa-barcode me-1 text-secondary"></i>NIT <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('nit') is-invalid @enderror" id="nit" wire:model="nit">
                    @error('nit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="moneda_base" class="form-label">
                        <i class="fas fa-money-bill-wave me-1 text-secondary"></i>Moneda Base <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('moneda_base') is-invalid @enderror" id="moneda_base" wire:model="moneda_base">
                        <option>BOB (Bolivianos)</option>
                        <option>USD (Dólares)</option>
                        <option>EUR (Euros)</option>
                    </select>
                    @error('moneda_base') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label for="direccion_principal" class="form-label">
                        <i class="fas fa-map-marker-alt me-1 text-secondary"></i>Dirección Principal
                    </label>
                    <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion_principal" wire:model="direccion">
                    @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="telefono_principal" class="form-label">
                        <i class="fas fa-phone me-1 text-secondary"></i>Teléfono Principal
                    </label>
                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono_principal" wire:model="telefono">
                    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="email_principal" class="form-label">
                        <i class="fas fa-envelope me-1 text-secondary"></i>Email Principal
                    </label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email_principal" wire:model="email">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label for="sitio_web" class="form-label">
                        <i class="fas fa-globe me-1 text-secondary"></i>Sitio Web
                    </label>
                    <input type="text" class="form-control @error('sitio_web') is-invalid @enderror" id="sitio_web" wire:model="sitio_web">
                    @error('sitio_web') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-warning text-dark" wire:click="guardar">
                        <i class="fas fa-save me-2"></i>
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
