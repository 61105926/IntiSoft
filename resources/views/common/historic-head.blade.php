<div wire:ignore.self class="modal fade" id="historic" tabindex="-1" role="dialog" aria-labelledby="historicLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historicLabel">
                    {{ $componentName }}|{{ $selected_id > 0 ? 'Editar' : 'Historico' }}</h5>
                <h6 class="text-center text-warning" wire:loading>Por favor espere</h6>
            </div>
            <div class="modal-body">
