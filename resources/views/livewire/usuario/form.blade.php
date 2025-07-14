@include('common.modalhead')
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="names">Nombres</label>
            <input type="text"
                class="form-control"
                id="names"
                wire:model="names">
        </div>
        <div class="form-group">
            <label for="last_name">Apellido Paterno</label>
            <input type="text"
                class="form-control"
                id="last_name"
                wire:model="last_name">
        </div>
        <div class="form-group">
            <label for="second_last_name">Apellido Materno</label>
            <input type="text"
                class="form-control"
                id="second_last_name"
                wire:model="second_last_name">
        </div>
        <div class="form-group">
            <label for="birthdate">Fecha de Nacimiento</label>
            <input type="date"
                class="form-control"
                id="birthdate"
                wire:model="birthdate">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="nationality">Nacionalidad</label>
            <input type="text"
                class="form-control"
                id="nationality"
                wire:model="nationality">
        </div>
        <div class="form-group">
            <label for="address">Domicilio</label>
            <input type="text"
                class="form-control"
                id="address"
                wire:model="address">
        </div>
        <div class="form-group">
            <label for="city">Ciudad</label>
            <input type="text"
                class="form-control"
                id="city"
                wire:model="city">
        </div>
        <div class="form-group">
            <label for="gender">Sexo</label>
            <input type="text"
                class="form-control"
                id="gender"
                wire:model="gender">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email"
                class="form-control"
                id="email"
                wire:model="email">
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password"
                class="form-control"
                id="password"
                wire:model="password">
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group mb-3">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-street-view"></i>
                </span>
                <select wire:model.lazy='post'
                    id="inputState"
                    class="form-select">
                    <option selected=""
                        selected
                        hidden>Nombre de Puesto</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('post')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

@include('common.modalfooter')
