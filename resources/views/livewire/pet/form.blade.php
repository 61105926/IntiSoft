@include('common.modalhead')
<div class="row justify-content mb-4">
    <!-- Foto Section -->
    <div class="col-md-6 col-lg-4">
        <div class="form-group text-center">
            
            <div></div>
            <div>
                <!-- Input para cargar la imagen -->
                <div class="drop-area">
                    <label for="photo1" class="drop-area-label mb-1">
                        <div class="upload-icon">
                        </div>
                        <span class="upload-text">Haz clic o arrastra una imagen aquí</span>
                    </label>
                    <input type="file" id="photo1" wire:model="image" accept="image/png, image/jpeg, image/gif" hidden />
                </div>
                            
                <!-- Mensaje de carga -->
                <div wire:loading wire:target="image">
                    <span id="loading-message">Cargando imagen...</span>
                </div>
            
                <!-- Vista previa de la imagen -->
                <div class="img-preview text mt-2">
                    <img src="https://gestion.portalbiesa.com/redaccio/arxius/imatges/202209/770_1662979063blog_post_coccidiosis_1.jpg"
                        id="img-preview-image" class="img-thumbnail rounded-circle"
                        style="width: 100px; height: 100px; object-fit: cover;" />
                </div>
            </div>
        </div>
    </div>

    <!-- Cliente Section -->
    <div class="col-md-6 col-lg-8">
        <h4 class="text-center mb-3">Seleccione el Dueño</h4>
        <div class="form-group" wire:ignore>
            <label for="client_id">Cliente</label>
            <select id="pet_client" class="form-control" wire:model="client_id">
                <option value="">Seleccione el cliente</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->ci }} | {{ $client->nombre_completo }}</option>
                @endforeach
            </select>
            @error('client_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        @if ($client_data)
            <div class="row mb-4">
                <div class="col-md-12">
                    <h5>Detalles del Cliente</h5>
                    <div class="row">
                        <!-- CI Card -->
                        <div class="col-md-4">
                            <div class="card text-center shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title">CI</h6>
                                    <p class="card-text">{{ $client_data->ci }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Nombre Card -->
                        <div class="col-md-4">
                            <div class="card text-center shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title">Nombre</h6>
                                    <p class="card-text">{{ $client_data->nombre_completo }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Teléfono Card -->
                        <div class="col-md-4">
                            <div class="card text-center shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title">Teléfono</h6>
                                    <p class="card-text">{{ $client_data->numero_telefono }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4 class="text-center mb-3">Información de la Mascota</h4>
        <div class="card p-4 shadow-sm">
            <div class="row">
                <div class="col-md-4">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" wire:model="nombre"
                        placeholder="Nombre de la mascota">
                    @error('nombre')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="especie">Especie</label>
                    <select class="form-control" id="especie" wire:model="especie">
                        <option value="">Seleccionar</option>
                        @if (!empty($especies) && is_iterable($especies))

                            @foreach ($especies as $especie)
                                <option value="{{ $especie->id }}">{{ $especie->nombre }}</option>
                                <!-- Asegúrate de que 'nombre' sea un atributo en tu modelo -->
                            @endforeach
                        @endif
                    </select>
                    @error('especie')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="raza">Raza</label>
                    <select class="form-control" id="raza" wire:model="raza">
                        <option value="">Seleccionar</option>
                        @if (!empty($razas) && is_iterable($razas))
                            @foreach ($razas as $raza)
                                <option value="{{ $raza->id }}">{{ $raza->nombre }}</option>
                                <!-- Asegúrate de que 'nombre' sea un atributo en tu modelo -->
                            @endforeach
                        @endif
                    </select>
                    @error('raza')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label for="sexo">Sexo</label>
                    <select id="sexo" class="form-control" wire:model="sexo">
                        <option value="">Seleccione</option>
                        <option value="Macho">Macho</option>
                        <option value="Hembra">Hembra</option>
                    </select>
                    @error('sexo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" id="fecha_nacimiento" class="form-control" wire:model="fecha_nacimiento">
                    @error('fecha_nacimiento')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="color">Color</label>
                    <input type="text" id="color" class="form-control" wire:model="color"
                        placeholder="Color de la mascota">
                    @error('color')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label for="peso">Peso</label>
                    <input type="text" id="peso" class="form-control" wire:model="peso"
                        placeholder="Peso en kg">
                    @error('peso')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="chip">Chip</label>
                    <input type="text" id="chip" class="form-control" wire:model="chip"
                        placeholder="Número de chip">
                    @error('chip')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="tatuaje">Tatuaje</label>
                    <input type="text" id="tatuaje" class="form-control" wire:model="tatuaje"
                        placeholder="Número de tatuaje">
                    @error('tatuaje')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label for="pasaporte">Pasaporte</label>
                    <input type="text" id="pasaporte" class="form-control" wire:model="pasaporte"
                        placeholder="Número de pasaporte">
                    @error('pasaporte')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="entero_castrado">Entero o Castrado</label>
                    <select id="entero_castrado" class="form-control" wire:model="entero_castrado">
                        <option value="">Seleccione</option>
                        <option value="Entero">Entero</option>
                        <option value="Castrado">Castrado</option>
                    </select>
                    @error('entero_castrado')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="veterinario_habitual">Veterinario Habitual</label>
                    <input type="text" id="veterinario_habitual" class="form-control"
                        wire:model="veterinario_habitual" placeholder="Nombre del veterinario">
                    @error('veterinario_habitual')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>




        </div>
    </div>
</div>

<script>
   document.addEventListener('livewire:load', function () {
    Livewire.on('imageUpdated', () => {
        const inputFile = document.getElementById('photo1');
        const previewImage = document.getElementById('img-preview-image');
        const loadingMessage = document.getElementById('loading-message');

        // Obtener el archivo seleccionado
        const file = inputFile.files[0];

        if (file) {
            const reader = new FileReader();

            // Mostrar mensaje de carga
            loadingMessage.style.display = 'inline';

            reader.onload = function (e) {
                // Actualizar la imagen de vista previa
                previewImage.src = e.target.result;

                // Ocultar mensaje de carga
                loadingMessage.style.display = 'none';
            };

            reader.readAsDataURL(file);
        }
    });
});

</script>
<script>
    document.addEventListener('livewire:load', function() {
        new TomSelect("#pet_client", {
            sortField: {
                field: "text",
                direction: "asc"
            },
            render: {
                no_results: function(data, escape) {
                    return '<div class="no-results">No se encontraron resultados</div>';
                }
            }
        });

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
</style>

@include('common.modalfooter')
