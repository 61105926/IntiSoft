{{-- resources/views/livewire/producto/partials/_detail_modal.blade.php --}}
<div wire:ignore.self class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">Detalles del Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @if($detailProducto)
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Código:</strong> {{ $detailProducto->codigo }}</li>
            <li class="list-group-item"><strong>Nombre:</strong> {{ $detailProducto->nombre }}</li>
            <li class="list-group-item"><strong>Descripción:</strong> {{ $detailProducto->descripcion }}</li>
            <li class="list-group-item"><strong>Categoría:</strong> {{ $detailProducto->categoria->nombre }}</li>
            <li class="list-group-item"><strong>Sucursal:</strong> {{ $detailProducto->sucursal->nombre }}</li>
            <li class="list-group-item"><strong>Precio Venta:</strong> ${{ number_format($detailProducto->precio_venta, 2) }}</li>
            <li class="list-group-item"><strong>Precio Alquiler:</strong> ${{ number_format($detailProducto->precio_alquiler, 2) }}</li>
            <li class="list-group-item"><strong>Stock Actual:</strong> {{ $detailProducto->stock_actual }}</li>
            <li class="list-group-item"><strong>Stock Mínimo:</strong> {{ $detailProducto->stock_minimo }}</li>
            <li class="list-group-item"><strong>Disponible Venta:</strong> {{ $detailProducto->disponible_venta ? 'Sí' : 'No' }}</li>
            <li class="list-group-item"><strong>Disponible Alquiler:</strong> {{ $detailProducto->disponible_alquiler ? 'Sí' : 'No' }}</li>
            <li class="list-group-item"><strong>Creado por:</strong> {{ optional($detailProducto->creator)->name }}</li>
            <li class="list-group-item"><strong>Fecha creación:</strong> {{ $detailProducto->created_at->format('d/m/Y H:i') }}</li>
          </ul>
        @else
          <p class="text-muted">Cargando detalles...</p>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
