@include('common.modalhead')
<div class="card p-4 shadow-lg border-0 rounded-3">
    <h5 class="mb-4 text-primary fw-bold">Agregar Proveedor</h5>

    <!-- Datos del proveedor -->
    <div class="row g-3">
        <div class="col-md-4">
            <label for="nombreProveedor" class="form-label">Nombre del Proveedor</label>
            <input type="text" id="nombreProveedor" class="form-control" wire:model="nombreProveedor" required>
        </div>

        <div class="col-md-4">
            <label for="documentoNit" class="form-label">Documento NIT</label>
            <input type="text" id="documentoNit" class="form-control" wire:model="documentoNit" required>
        </div>

        <div class="col-md-4">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" id="direccion" class="form-control" wire:model="direccion" required>
        </div>

        <div class="col-md-4">
            <label for="telefono1" class="form-label">Teléfono 1</label>
            <input type="text" id="telefono1" class="form-control" wire:model="telefono1">
        </div>

        <div class="col-md-4">
            <label for="telefono2" class="form-label">Teléfono 2</label>
            <input type="text" id="telefono2" class="form-control" wire:model="telefono2">
        </div>

        <div class="col-md-4">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" id="email" class="form-control" wire:model="email">
        </div>

        <div class="col-md-4">
            <label for="banco" class="form-label">Banco</label>
            <select class="form-select" id="banco" wire:model="banco" required>
                <option value="">Seleccione un banco</option>
                <option value="Banco Central de Bolivia">Banco Central de Bolivia</option>
                <option value="Banco de Crédito de Bolivia">Banco de Crédito de Bolivia</option>
                <option value="Banco Unión">Banco Unión</option>
                <option value="Banco Nacional de Bolivia">Banco Nacional de Bolivia</option>
                <option value="Banco de la Vivienda y la Promoción de la Ciudad">Banco de la Vivienda y la Promoción de la Ciudad</option>
                <option value="Fondo de Desarrollo del Sistema Financiero">Fondo de Desarrollo del Sistema Financiero</option>
                <option value="Banco de Crédito de Bolivia (BCP)">Banco de Crédito de Bolivia (BCP)</option>
                <option value="Banco Mercantil Santa Cruz">Banco Mercantil Santa Cruz</option>
                <option value="Banco Nacional de Bolivia (BNB)">Banco Nacional de Bolivia (BNB)</option>
                <option value="Banco FIE">Banco FIE</option>
                <option value="Banco Ganadero">Banco Ganadero</option>
                <option value="Banco Ecofuturo">Banco Ecofuturo</option>
                <option value="Banco Fortaleza">Banco Fortaleza</option>
                <option value="Banco Bisa">Banco Bisa</option>
                <option value="BancoSol">BancoSol</option>
                <option value="Banco Prodem">Banco Prodem</option>
                <option value="Crecer">Crecer</option>
                <option value="Los Andes ProCredit">Los Andes ProCredit</option>
                <option value="Microfinanciera Los Andes">Microfinanciera Los Andes</option>
                <option value="Banco de la Nación Argentina">Banco de la Nación Argentina</option>
                <option value="HSBC">HSBC</option>
            </select>
            @error('banco') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="col-md-4">
            <label for="ci_proveedor" class="form-label">CI del Proveedor</label>
            <input type="text" id="ci_proveedor" class="form-control" wire:model="ci_proveedor" required>
            @error('ci_proveedor') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="col-md-4">
            <label for="tipo_cuenta" class="form-label">Tipo de Cuenta</label>
            <select class="form-select" id="tipo_cuenta" wire:model="tipo_cuenta" required>
                <option value="">Seleccione tipo de cuenta</option>
                <option value="Cuenta Corriente">Cuenta Corriente</option>
                <option value="Caja de Ahorro">Caja de Ahorro</option>
                <option value="Cuenta de Ahorro">Cuenta de Ahorro</option>
            </select>
            @error('tipo_cuenta') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="col-md-4">
            <label for="numeroCuenta" class="form-label">Número de Cuenta</label>
            <input type="text" id="numeroCuenta" class="form-control" wire:model="numeroCuenta" required>
        </div>

        <div class="col-md-4">
            <label for="categoria" class="form-label">Categoria</label>
            <select class="form-select" id="categoria" wire:model="categoria">
                <option value="">Seleccionar</option>
                <option value="Producto">Producto</option>
                <option value="Servicio">Servicio</option>
                <option value="Desparacitación interna">Desparacitación interna</option>
                <option value="Desparacitación externa">Desparacitación externa</option>
                <option value="Internación">Internación</option>
                <option value="Farmacia">Farmacia</option>
                <option value="Baño y peluquería">Baño y peluquería</option>
                <option value="Vacunas">Vacunas</option>
                <option value="Ortros">Otros</option>
            </select>
        </div>
    </div>



</div>



@include('common.modalfooter')

