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

                    {{-- Product Images --}}
                    @if($detailProducto->imagen_principal || ($detailProducto->imagenes_adicionales && count($detailProducto->imagenes_adicionales) > 0))
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Imágenes del Producto</h6>
                            
                            <div class="row g-2">
                                @if($detailProducto->imagen_principal)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="position-relative">
                                            <img src="{{ $detailProducto->imagen_principal_url }}" 
                                                 alt="Imagen principal" 
                                                 class="img-fluid rounded" 
                                                 style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#imageModal-{{ $detailProducto->id }}">
                                            <span class="badge bg-primary position-absolute top-0 start-0 m-2">Principal</span>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($detailProducto->imagenes_adicionales && count($detailProducto->imagenes_adicionales) > 0)
                                    @foreach($detailProducto->imagenes_adicionales_urls as $index => $imagenUrl)
                                        <div class="col-md-4 col-sm-6">
                                            <img src="{{ $imagenUrl }}" 
                                                 alt="Imagen adicional {{ $index + 1 }}" 
                                                 class="img-fluid rounded" 
                                                 style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#imageModal-{{ $detailProducto->id }}">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Código:</strong> {{ $detailProducto->codigo ?? 'N /A' }}</li>
                        @if($detailProducto->codigo_barras)
                            <li class="list-group-item"><strong>Código de Barras:</strong> {{ $detailProducto->codigo_barras }}</li>
                        @endif
                        <li class="list-group-item"><strong>Nombre:</strong> {{ $detailProducto->nombre ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Descripción:</strong> {{ $detailProducto->descripcion ?? 'Sin descripción' }}</li>
                        <li class="list-group-item"><strong>Categoría:</strong> {{ optional($detailProducto->categoria)->nombre ?? 'Sin categoría' }}</li>
                        @if($detailProducto->talla)
                            <li class="list-group-item"><strong>Talla:</strong> {{ $detailProducto->talla }}</li>
                        @endif
                        @if($detailProducto->color)
                            <li class="list-group-item"><strong>Color:</strong> {{ $detailProducto->color }}</li>
                        @endif
                        @if($detailProducto->material)
                            <li class="list-group-item"><strong>Material:</strong> {{ $detailProducto->material }}</li>
                        @endif
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