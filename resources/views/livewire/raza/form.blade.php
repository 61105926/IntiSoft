@include('common.modalhead')
<div class="modal-body">
    <div class="form-group">
        <label for="nombre">Nombre de la Raza</label>
        <input type="text" wire:model.defer="nombre" class="form-control" id="nombre" placeholder="Ingrese el nombre de la raza">
        @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label for="especie_id">Especie</label>
        <select wire:model.defer="especie_id" class="form-control" id="especie_id">
            <option value="">Seleccione una especie</option>
            @foreach($especies as $especie)
                <option value="{{ $especie->id }}">{{ $especie->nombre }}</option>
            @endforeach
        </select>
        @error('especie_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>
@include('common.modalfooter')
