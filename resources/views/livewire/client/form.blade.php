@include('common.modalhead')

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="nombre_completo">Nombre Completo</label>
            <input type="text" class="form-control" id="nombre_completo" wire:model="nombre_completo">
            @error('nombre_completo') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="ci">C.I.</label>
            <input type="text" class="form-control" id="ci" wire:model="ci">
            @error('ci') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="nacionalidad">Nacionalidad</label>
            <input type="text" class="form-control" id="nacionalidad" wire:model="nacionalidad">
            @error('nacionalidad') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fecha_nacimiento" wire:model="fecha_nacimiento">
            @error('fecha_nacimiento') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="direccion">Domicilio</label>
            <input type="text" class="form-control" id="direccion" wire:model="direccion">
            @error('direccion') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="numero_telefono">Número de Teléfono</label>
            <input type="text" class="form-control" id="numero_telefono" wire:model="numero_telefono">
            @error('numero_telefono') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="numero_telefono2">Número de Teléfono 2</label>
            <input type="text" class="form-control" id="numero_telefono2" wire:model="numero_telefono2">
            @error('numero_telefono2') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="correo">Correo</label>
            <input type="email" class="form-control" id="correo" wire:model="correo">
            @error('correo') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="sexo">Sexo</label>
            <select class="form-control" id="sexo" wire:model="sexo">
                <option value="">Seleccionar</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
            </select>
            @error('sexo') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
</div>

@include('common.modalfooter')
