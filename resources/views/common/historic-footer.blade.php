<div class="modal-footer">
    @if ($selected_id < 1)
        <button wire:click.prevent="store()" type="button" class="btn btn-primary">Guardar</button>
    @else
        <button wire:click.prevent="update()" type="button" class="btn btn-primary">Actualizar</button>
    @endif
    <button class="btn btn btn-light-dark" wire:click="resetUI" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>
        Cerrar</button>
</div>
</div>
</div>
</div>
