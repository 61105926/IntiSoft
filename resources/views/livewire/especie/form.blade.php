@include('common.modalhead')
<div class="modal-body">
    <div class="form-group">
        <label for="nombre">Nombre de la Especie</label>
        <input type="text" wire:model.defer="nombre" class="form-control" id="nombre"
            placeholder="Ingrese el nombre de la especie">
        @error('nombre')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
@include('common.modalfooter')
