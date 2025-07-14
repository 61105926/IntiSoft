<div class="modal-footer">

    @if ($selected_id < 1)
        <button wire:click.prevent="store()" type="button" class="btn btn-primary" wire:loading.attr="disabled"
            wire:target="image">Guardar</button>
    @else
        <button wire:click.prevent="update()" type="button" class="btn btn-primary" wire:loading.attr="disabled"
            wire:target="image">Actualizar</button>
    @endif
    <button class="btn btn-light-dark" wire:click="resetUI" data-bs-dismiss="modal" ><i class="flaticon-cancel-12"></i> Cerrar</button>
    <style>
        .disabled {
            pointer-events: none;
            /* Evita clics */
            opacity: 0.5;
            /* Cambia la opacidad para indicar que está deshabilitado */
        }
    </style>
</div>
</div>
</div>
</div>
