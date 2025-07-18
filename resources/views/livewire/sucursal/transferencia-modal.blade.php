<div wire:ignore.self class="modal fade" id="transferenciaModal" tabindex="-1" aria-labelledby="transferenciaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferenciaModalLabel">Nueva Transferencia entre Sucursales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <div class="row g-4">
                    {{-- DATOS DE TRANSFERENCIA --}}
                    <div class="col-md-6">
                        <h6 class="fw-bold">Detalles de la Transferencia</h6>

                        {{-- Sucursal Origen --}}
                        <div class="mb-3">
                            <label class="form-label">Sucursal Origen</label>
                            <select class="form-select" wire:model.defer="sucursal_origen_id">
                                <option value="" selected disabled>Seleccione sucursal origen</option>
                                @foreach($sucursales as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                            @error('sucursal_origen_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- Sucursal Destino --}}
                        <div class="mb-3">
                            <label class="form-label">Sucursal Destino</label>
                            <select class="form-select" wire:model.defer="sucursal_destino_id">
                                <option value="" selected disabled>Seleccione sucursal destino</option>
                                @foreach($sucursales as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                            @error('sucursal_destino_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- Motivo --}}
                        <div class="mb-3">
                            <label class="form-label">Motivo</label>
                            <input type="text" class="form-control" wire:model.defer="motivo" placeholder="Motivo de la transferencia">
                            @error('motivo') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- Observaciones --}}
                        <div class="mb-3">
                            <label class="form-label">Observaciones</label>
                            <input type="text" class="form-control" wire:model.defer="observaciones" placeholder="Observaciones adicionales">
                            @error('observaciones') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- PRODUCTOS A TRANSFERIR --}}
                    <div class="col-md-6">
                        <h6 class="fw-bold">Productos a Transferir</h6>

                        {{-- Selección de producto y cantidad --}}
                        <div class="d-flex mb-3">
                            <select class="form-select me-2" wire:model.defer="producto_id">
                                <option value="" selected disabled>Seleccione un producto</option>
                                @foreach($productos as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>

                            <input type="number" class="form-control me-2" wire:model.defer="cantidad" placeholder="Cant." min="1">
                            <button class="btn btn-outline-secondary" wire:click.prevent="agregarProducto">Añadir</button>
                        </div>
                        @error('producto_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        @error('cantidad') <span class="text-danger small">{{ $message }}</span> @enderror

                        {{-- Lista de productos seleccionados --}}
                        <div class="border rounded p-2" style="max-height: 250px; overflow-y: auto">
                            @if(count($productos_seleccionados) > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach($productos_seleccionados as $index => $p)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $p['nombre'] }} (x{{ $p['cantidad'] }})
                                            <button type="button" class="btn btn-sm btn-danger"
                                                    wire:click.prevent="eliminarProductoTransferencia({{ $index }})">x</button>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted small mb-0">No hay productos añadidos.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning text-dark" wire:click.prevent="guardarTransferencia">Solicitar Transferencia</button>
            </div>
        </div>
    </div>
</div>
