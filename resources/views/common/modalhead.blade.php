<div wire:ignore.self
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="exampleModalLabel">{{ $componentName }}|{{ $selected_id > 0 ? 'Editar' : 'Crear' }}</h5>
                <h6 class="text-center text-warning"
                    wire:loading>Por favor espere</h6>
            </div>
            <div class="modal-body">
