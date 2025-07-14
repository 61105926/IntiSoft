@include('common.modalhead')


<div class="row">
    <div class="col-md-12">
        <h4 class="text-center mb-3">Información del Producto</h4>
        <div class="card p-4 shadow-sm">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre_producto">Nombre del Producto</label>
                    <input type="text" class="form-control" id="nombre_producto" wire:model="nombre"
                        placeholder="Nombre del producto">
                    @error('nombre')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="categoria">Categoría</label>
                    <div>
                        <select class="form-control" id="categoria" wire:model="categoria">
                            <option value="">Seleccionar</option>
                            <option value="producto">Producto</option>
                            <option value="servicio">Servicio</option>
                            <option value="desparacitación interna">Desparacitación Interna</option>
                            <option value="desparacitación externa">Desparacitación Externa</option>
                            <option value="internación">Internación</option>
                            <option value="farmacia">Farmacia</option>
                            <option value="baño y peluquería">Baño y Peluquería</option>
                            <option value="vacunas">Vacunas</option>
                        </select>

                     
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="stock">Stock</label>
                    <input type="number" class="form-control text-center" id="stock" wire:model="stock"
                        placeholder="Cantidad en stock">
                    {{-- @if(!$selected_id)
                        @error('stock')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    @else
                        <small class="text-muted">
                            El stock solo puede modificarse mediante compras o ventas
                        </small>
                    @endif --}}
                </div>
                <div class="col-md-4 mb-3">
                    <label for="precio">Precio</label>
                    <input type="number" step="0.01" id="precio" class="form-control" wire:model="precio"
                        placeholder="Precio del producto">
                    @error('precio')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="monto_comprado">Monto Comprado</label>
                    <input type="number" step="0.01" id="monto_comprado" class="form-control"
                        wire:model="monto_comprado" placeholder="Monto de compra">
                    @error('monto_comprado')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="lote">Lote</label>
                    <input type="text" id="lote" class="form-control" wire:model="lote"
                        placeholder="Número de lote">
                    @error('lote')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                    <input type="date" id="fecha_vencimiento" class="form-control" wire:model="fecha_vencimiento">
                    @error('fecha_vencimiento')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Imagen del Producto</label>
                    <div class="custom-file-upload">
                        <label for="image" class="drop-area-label">
                            <div class="text-center">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <p class="mb-0">Arrastra y suelta una imagen aquí o</p>
                                <span class="explore-action">Explora tus archivos</span>
                            </div>
                            <input type="file" wire:model="image" class="file-input" id="image" accept="image/*">
                        </label>
                        
                        <div class="img-preview text-center" wire:loading.remove wire:target="image">
                            @if ($image)
                                @if (is_object($image))
                                    <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail preview-image" alt="Vista previa">
                                @else
                                    <img src="{{ asset('storage/Product/' . $image) }}" class="img-thumbnail preview-image" alt="Imagen actual">
                                @endif
                            @endif
                        </div>

                        <div wire:loading wire:target="image" class="text-center mt-2">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>

                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $("input[name='stock']").TouchSpin({
        initval: 40
    });
    document.getElementById('photo1').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewImage = document.getElementById('img-preview-image');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            previewImage.src = '';
            previewImage.style.display = 'none';
        }
    });
</script>

<style>
    .drop-area-label {
        display: block;
        padding: 15px;
        border: 2px dashed #007bff;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        color: #007bff;
        transition: background-color 0.3s;
    }

    .drop-area-label:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }

    .explore-action {
        font-weight: bold;
    }

    .file-input {
        display: none;
    }

    .img-preview {
        margin-left: 30%;
        margin-top: 20px;
    }

    .img-thumbnail {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        /* Hacer la imagen redonda */
        transition: transform 0.2s;
    }

    .img-thumbnail:hover {
        transform: scale(1.05);
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .custom-file-upload {
        margin-bottom: 20px;
    }

    .drop-area-label {
        display: block;
        padding: 20px;
        border: 2px dashed #3b3f5c;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .drop-area-label:hover {
        border-color: #650abb;
        background-color: rgba(67, 97, 238, 0.05);
    }

    .file-input {
        display: none;
    }

    .preview-image {
        max-width: 200px;
        max-height: 200px;
        margin-top: 15px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .explore-action {
        color: #650abb;
        text-decoration: underline;
        font-weight: 500;
    }
</style>

@include('common.modalfooter')
