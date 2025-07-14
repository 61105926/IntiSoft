@include('common.modalhead')

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="names">Nombres</label>
            <input type="text" class="form-control" id="names" wire:model="names">
        </div>
        <div class="form-group">
            <label for="last_name">Apellido Paterno</label>
            <input type="text" class="form-control" id="last_name" wire:model="last_name">
        </div>
        <div class="form-group">
            <label for="second_last_name">Apellido Materno</label>
            <input type="text" class="form-control" id="second_last_name" wire:model="second_last_name">
        </div>
        <div class="form-group">
            <label for="birthdate">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="birthdate" wire:model="birthdate">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="nationality">Nacionalidad</label>
            <input type="text" class="form-control" id="nationality" wire:model="nationality">
        </div>
        <div class="form-group">
            <label for="address">Domicilio</label>
            <input type="text" class="form-control" id="address" wire:model="address">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="city">Ciudad</label>
            <input type="text" class="form-control" id="city" wire:model="city">
        </div>
        <div class="form-group">
            <label for="gender">Sexo</label>
            <input type="text" class="form-control" id="gender" wire:model="gender">
        </div>
    </div>
</div>

@include('common.modalfooter')
