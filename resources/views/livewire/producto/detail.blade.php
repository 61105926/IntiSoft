{{-- resources/views/livewire/producto/partials/_detail_modal.blade.php --}}
<div wire:ignore.self class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detalles del Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($detailProducto)
                    @php
                        // Fetch the stock information for the product
                        $stock = \App\Models\StockPorSucursal::where('producto_id', $detailProducto->id)
                            ->when($sucursal_id, fn($q) => $q->where('sucursal_id', $sucursal_id))
                            ->first();
                        $sucursal = $stock ? \App\Models\Sucursal::find($stock->sucursal_id) : null;
                    @endphp

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Código:</strong> {{ $detailProducto->codigo ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Nombre:</strong> {{ $detailProducto->nombre ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Descripción:</strong> {{ $detailProducto->descripcion ?? 'Sin descripción' }}</li>
                        <li class="list-group-item"><strong>Categoría:</strong> {{ optional($detailProducto->categoria)->nombre ?? 'Sin categoría' }}</li>
                        <li class="list-group-item"><strong>Sucursal:</strong> {{ optional($sucursal)->nombre ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Precio Venta:</strong> ${{ number_format($stock->precio_venta_sucursal ?? 0, 2) }}</li>
                        <li class="list-group-item"><strong>Precio Alquiler:</strong> ${{ number_format($stock->precio_alquiler_sucursal ?? 0, 2) }}</li>
                        <li class="list-group-item"><strong>Stock Actual:</strong> {{ $stock->stock_actual ?? 0 }}</li>
                        <li class="list-group-item"><strong>Stock Mínimo:</strong> {{ $stock->stock_minimo ?? 0 }}</li>
                        <li class="list-group-item"><strong>Disponible Venta:</strong> {{ $detailProducto->disponible_venta ? 'Sí' : 'No' }}</li>
                        <li class="list-group-item"><strong>Disponible Alquiler:</strong> {{ $detailProducto->disponible_alquiler ? 'Sí' : 'No' }}</li>
                        <li class="list-group-item"><strong>Creado por:</strong> {{ optional($detailProducto->creator)->name ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Fecha creación:</strong> {{ optional($detailProducto->created_at)->format('d/m/Y H:i') ?? 'N/A' }}</li>
                    </ul>
                @else
                    <p class="text-muted text-center">No se encontraron detalles del producto.</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>