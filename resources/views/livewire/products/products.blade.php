<div>
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <!-- BREADCRUMB -->
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Productos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lista</li>
                    </ol>
                </nav>
            </div>
            <!-- /BREADCRUMB -->

            <!-- Card para el botón de añadir producto y buscador -->
            <div class="card widget-content widget-content-area br-8 mb-4">
                <div class="row align-items-center p-3">
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary btn-lg _effect--ripple waves-effect waves-light"
                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fa fa-plus"></i> Añadir Producto
                        </button>
                    </div>
                    <div class="col-auto">
                        <button type="button" wire:click="generateInventoryReport" 
                                class="btn btn-success btn-lg _effect--ripple waves-effect waves-light">
                            <i class="fas fa-file-pdf"></i> Reporte de Inventario
                        </button>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Mostrar</label>
                        <select wire:model="perPage" class="form-select">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="9999999">Todos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Filtrar</label>
                        <select wire:model="productFilter" class="form-select">
                            <option value="1">Activos</option>
                            <option value="0">Eliminados</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Desde</label>
                        <input type="date" wire:model="dateFrom" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Hasta</label>
                        <input type="date" wire:model="dateTo" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="search" class="form-label">Buscar por Nombre o Código</label>
                        @include('common.searchbox')
                    </div>
                </div>

                @include('livewire.products.form')
            </div>
        </div>

        <!-- Card para la tabla de productos -->
        <div class="card widget-content widget-content-area br-8">
            <div class="table-responsive p-3" style="max-height: 600px; overflow-y: auto;">
                <table id="invoice-list" class="table table-hover table-bordered mb-0" style="width: 100%;" role="grid">
                    <thead class="table-dark" style="position: sticky; top: 0; z-index: 1020; background-color: white; color: black;">
                        <tr role="row">
                            <th style="position: sticky; left: 0; background-color: white; z-index: 1030;">ID</th>
                            <th style="position: sticky; left: 50px; background-color: white; z-index: 1030;">Código</th>
                            <th style="position: sticky; left: 150px; background-color: white; z-index: 1030;">Nombre</th>
                            <th>Tipo</th>
                            <th>Monto Compra</th>
                            <th>Precio</th>
                            <th>Fecha Venc.</th>
                            <th>Stock</th>
                            <th>Vendido</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
        
                    <tbody>
                        @forelse ($data as $product)
                        <tr style="cursor: pointer;" 
                        wire:click="toggleHistorial({{ $product->id }})"
                        class="{{ $product->state == 0 ? 'table-danger' : '' }}">
                                                        <td style="position: sticky; left: 0; background-color: white;">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">{{ $product->id }}</span>
                                        <button class="btn btn-outline-primary btn-sm rounded-circle" 
                                                wire:click="toggleHistorial({{ $product->id }})"
                                                title="Ver historial"
                                                style="width: 28px; height: 28px; padding: 0; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                                            <i class="fas fa-history" style="font-size: 14px;"></i>
                                        </button>
                                    </div>
                                </td>
                                <td style="position: sticky; left: 50px; background-color: white;">{{ $product->codigo_producto }}</td>
                                <td style="position: sticky; left: 150px; background-color: white;">
                                    <div class="d-flex align-items-center">
                                        @if($product->foto_producto)
                                            <img src="{{ asset('storage/Product/'.$product->foto_producto) }}" 
                                                 alt="Imagen de {{ $product->foto_producto }}"
                                                 class="rounded-circle me-2 product-image-thumb"
                                                 style="width: 70px; height: 70px; object-fit: cover; cursor: pointer;"
                                                 onclick="showImageModal('{{ asset('storage/Product/'.$product->foto_producto) }}', '{{ $product->nombre_producto }}'); event.stopPropagation();">
                                        @else
                                            <div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-light"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-box text-secondary"></i>
                                            </div>
                                        @endif
                                        {{ $product->nombre_producto }}
                                    </div>
                                </td>
                                <td>{{ $product->categoria }}</td>

                                <td>{{ $product->monto_comprado }} Bs</td>
                                <td>{{ number_format($product->precio, 2) }} Bs</td>
                                <!-- Mostrar Monto Compra -->

                                <td>{{ $product->fecha_vencimiento }}</td>

                                <td class="text-center">
                                    @php
                                        $entradas = $product->stockHistories()
                                            ->where('tipo_movimiento', 'entrada')
                                           
                                            ->sum('cantidad');
                                        
                                        $salidas = $product->stockHistories()
                                            ->where('tipo_movimiento', 'salida')
                                         
                                            ->sum('cantidad');
                                        
                                        $stockReal = $entradas - $salidas;
                                    @endphp
                                    <span class="badge {{ $stockReal > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $stockReal }}
                                    </span>
                                </td>
                                <td>{{ $product->vendido }}</td>


                                <td class="text-center" style="width: 120px;">
                                    <div class="action-btns d-flex justify-content-center">
                                        <!-- Botón de editar -->
                                        <a class="btn btn-info btn-sm me-2" wire:click='edit({{ $product->id }})'
                                            data-toggle="tooltip" data-placement="top" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <!-- Botón de deshabilitar -->
                                        <a class="btn btn-danger btn-sm" onclick="Confirm({{ $product->id }})"
                                            data-toggle="tooltip" data-placement="top" title="Deshabilitar">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @if($showingHistorial && $selectedProductHistory === $product->id)
                                <tr class="historial-row">
                                    <td colspan="12" style="padding: 0;">
                                        <div class="historial-content" style="display: none;">
                                            <!-- Información del Producto -->
                                            <div class="mb-4 p-3 border rounded bg-white">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <h6 class="mb-2">Información del Producto</h6>
                                                        <p class="product-name mb-1">
                                                            <strong>Nombre:</strong>
                                                            <span>{{ $this->productHistory['product']->nombre_producto }}</span>
                                                        </p>
                                                        <style>
                                                            .product-name span {
                                                                white-space: pre-wrap; /* Permite saltos de línea */
                                                                word-wrap: break-word; /* Corta las palabras largas si es necesario */
                                                                overflow: hidden; /* Evita desbordamientos */
                                                                display: block; /* Asegura que se comporta como un bloque */
                                                                max-height: 3rem; /* Limita la altura para evitar que empuje otros elementos */
                                                                line-height: 1.5rem; /* Espaciado entre líneas */
                                                            }
                                                        </style>
                                                        <p class="mb-1"><strong>Código:</strong> {{ $this->productHistory['product']->codigo_producto }}</p>
                                                        <p class="mb-1"><strong>Lote:</strong> {{ $this->productHistory['product']->lote }}</p>
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                        <h6 class="mb-2">Stock</h6>
                                                        <p class="mb-1"><strong>Stock Actual:</strong> 
                                                            <span class="badge {{ $stockReal > 0 ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $stockReal }}
                                                            </span>
                                                        </p>
                                                        <p class="mb-1"><strong>Vendidos:</strong> {{ $product->vendido }}</p>
                                                        <p class="mb-1"><strong>Restantes:</strong> {{ $stockReal - $product->vendido }}</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <h6 class="mb-2">Fechas</h6>
                                                        <p class="mb-1"><strong>Vencimiento:</strong> 
                                                            @if($this->productHistory['product']->fecha_vencimiento)
                                                                <span class="badge {{ \Carbon\Carbon::parse($this->productHistory['product']->fecha_vencimiento)->isPast() ? 'bg-danger' : 'bg-success' }}">
                                                                    {{ \Carbon\Carbon::parse($this->productHistory['product']->fecha_vencimiento)->format('d/m/Y') }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary">No definida</span>
                                                            @endif
                                                        </p>
                                                        <p class="mb-1"><strong>Creado:</strong> {{ $this->productHistory['product']->created_at->format('d/m/Y') }}</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <h6 class="mb-2">Precios</h6>
                                                        <p class="mb-1"><strong>Precio Venta:</strong> {{ number_format($this->productHistory['product']->precio, 2) }} Bs</p>
                                                        <p class="mb-1"><strong>Monto Compra:</strong> {{ number_format($this->productHistory['product']->monto_comprado, 2) }} Bs</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tabla de Historial -->
                                            <h6 class="mb-3">Historial de Movimientos</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead class="table-secondary">
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Tipo</th>
                                                            <th>Cantidad</th>
                                                            <th>Stock Anterior</th>
                                                            <th>Stock Nuevo</th>
                                                            <th>Referencia</th>
                                                            <th>Observación</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($this->productHistory['history'] as $history)
                                                            <tr>
                                                                <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                                                <td>
                                                                    @if($history->tipo_movimiento === 'entrada')
                                                                        <span class="badge bg-success">Entrada</span>
                                                                    @else
                                                                        <span class="badge bg-danger">Salida</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $history->cantidad }}</td>
                                                                <td>{{ $history->stock_anterior }}</td>
                                                                <td>{{ $history->stock_nuevo }}</td>
                                                                <td>{{ $history->referencia }}</td>
                                                                <td>{{ $history->observacion }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="7" class="text-center">
                                                                    No hay registros de movimientos
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <span class="badge badge-light-danger">NO SE ENCONTRARON REGISTROS</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación con información -->
            <div class="d-flex justify-content-between align-items-center px-4 py-3">
                <div>
                    Mostrando {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }} de {{ $data->total() }} registros
                </div>
                <div>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Imagen del producto" style="max-width: 100%; max-height: 80vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('show-modal', msg => {
            $('#exampleModal').modal('show');

        });
        window.livewire.on('person-added', msg => {
            $('#exampleModal').modal('hide');

        });
        window.livewire.on('person-updated', msg => {
            $('#exampleModal').modal('hide');

        });
    });
    document.addEventListener('livewire:load', function() {
        Livewire.on('mostrarAlertaSuccess', function(accion, codigo) {
            var mensaje = accion;

            Swal.fire({
                position: 'top-center',
                icon: 'success',
                title: mensaje,
                showConfirmButton: false,
                timer: 2000
            });
        });
    });

    function Confirm(id) {
        Swal.fire({
            icon: 'warning',
            title: '¡Desea cambiar el estado del registro!',
            text: '¡No podrás revertir esto!',
        }).then(function(result) {
            if (result.value) {
                window.livewire.emit('deleteRow', id)
                Swal.close()
            }
        })
    }
    document.addEventListener('livewire:load', function() {
        Livewire.on('show-alert', function(data) {
            const {
                title,
                type
            } = data;
            Swal.fire({
                position: 'top-end',
                icon: type,
                title: title,
                showConfirmButton: false,
                timer: 2000
            });
        });
    });

    function showImageModal(imageUrl, productName) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('imageModalLabel').textContent = productName;
        
        var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }

    // Mantener el efecto hover
    document.addEventListener('DOMContentLoaded', function() {
        const productImages = document.querySelectorAll('.product-image-thumb');
        productImages.forEach(img => {
            img.addEventListener('mouseover', function() {
                this.style.transform = 'scale(1.1)';
                this.style.transition = 'transform 0.3s ease';
            });
            
            img.addEventListener('mouseout', function() {
                this.style.transform = 'scale(1)';
            });
        });
    });

    document.addEventListener('livewire:load', function () {
        Livewire.on('startReportGeneration', () => {
            Swal.fire({
                title: 'Generando Reporte',
                text: 'Por favor espere...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });

        Livewire.on('reportGenerated', () => {
            Swal.close();
        });
    });

    document.addEventListener('livewire:load', function () {
        Livewire.on('toggleHistorial', () => {
            const historialContent = document.querySelector('.historial-content');
            if (historialContent) {
                // Mostrar con animación
                $(historialContent).slideToggle(300);
            }
        });
    });
</script>

<style>
    .product-image-thumb {
        transition: transform 0.3s ease;
    }
    
    .product-image-thumb:hover {
        transform: scale(1.1);
    }

    #imageModal .modal-body {
        padding: 0;
        background-color: #f8f9fa;
    }

    #modalImage {
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .historial-row {
        background-color: #f8f9fa;
    }

    .historial-content {
        padding: 20px;
        border-top: 2px solid #dee2e6;
    }

    /* Animación para el deslizamiento */
    .slide-enter-active {
        transition: all 0.3s ease-out;
    }

    .slide-leave-active {
        transition: all 0.3s ease-in;
    }

    .slide-enter-from,
    .slide-leave-to {
        transform: translateY(-20px);
        opacity: 0;
    }

    .btn-outline-primary:hover {
        transform: rotate(-15deg);
    }
    
    .table-active .btn-outline-primary {
        background-color: #007bff;
        color: white;
        transform: rotate(-15deg);
    }
</style>
