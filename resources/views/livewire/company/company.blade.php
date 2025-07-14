<div class="container mt-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="doc-container">
            <div class="row">
                <div class="col-xl-12">

                    <!-- Título Principal -->
                    <div class="text-center mb-4">
                        <h2 style="color: #008080; font-weight: bold; text-transform: uppercase; letter-spacing: 2px;">
                            Datos de la Empresa</h2>
                    </div>

                    <div class="invoice-content">
                        <div class="invoice-detail-body p-4"
                            style="background-color: #f7fdfc; border-radius: 15px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">

                            <!-- Sección de Título e Imagen -->
                            <div class="invoice-detail-title d-flex justify-content-between align-items-center mb-4">

                            </div>

                            <!-- Sección de Datos de la Empresa -->
                            <div class="invoice-detail-header">
                                <div class="row justify-content-center">
                                    <div class="custom-profile-image mt-4 pe-md-4">
                                        <div class="drop-area"
                                            id="drop-area">
                                            <label for="photo1"
                                                id="photo1-label"
                                                class="drop-area-label">
                                                Arrastra y suelta tu foto o <span class="explore-action">Explorar</span>
                                            </label>
                                            <input type="file"
                                                id="photo1"
                                                class="file-input"
                                                wire:model='image'
                                                accept="image/png, image/jpeg, image/gif" />
                                        </div>
                                        <div class="img-preview"
                                            id="img-preview"
                                            wire:ignore>
                                            @if($logo)
                                            <img src="{{ asset('storage/company/' .$logo) }}"
                                                alt="Vista previa de la imagen"
                                                id="img-preview-image"
                                                class="img-thumbnail"
                                                style="display: block; width: 200px; height: 200px; object-fit: cover;" />
                                        @else
                                            <!-- Si no hay imagen cargada, oculta la vista previa -->
                                            <img  src="" alt="Vista previa de la imagen" id="img-preview-image" class="img-thumbnail" style="display: none;" />
                                        @endif
                                        </div>
                                       
                                    </div>

                                    <!-- Formulario para la información -->
                                    <div class="col-xl-8 col-lg-10 col-md-12">
                                        <div class="invoice-address-company-fields">

                                            <!-- Campo Nombre -->
                                            <div class="form-group row">
                                                <label for="company-name"
                                                    class="col-sm-3 col-form-label col-form-label-lg">Nombre</label>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                        class="form-control form-control-lg"
                                                        id="company-name"
                                                        placeholder="Nombre de la Empresa"
                                                        wire:model="name"
                                                        style="background-color: #e6f9f5;">
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Campo Teléfono -->
                                            <div class="form-group row">
                                                <label for="company-phone"
                                                    class="col-sm-3 col-form-label col-form-label-lg">Teléfono</label>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                        class="form-control form-control-lg"
                                                        id="company-phone"
                                                        placeholder="(123) 456 789"
                                                        wire:model="phone"
                                                        style="background-color: #e6f9f5;">
                                                    @error('phone')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Campo Email -->
                                            <div class="form-group row">
                                                <label for="company-email"
                                                    class="col-sm-3 col-form-label col-form-label-lg">Email</label>
                                                <div class="col-sm-9">
                                                    <input type="email"
                                                        class="form-control form-control-lg"
                                                        id="company-email"
                                                        placeholder="email@empresa.com"
                                                        wire:model="email"
                                                        style="background-color: #e6f9f5;">
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Campo Dirección -->
                                            <div class="form-group row">
                                                <label for="company-address"
                                                    class="col-sm-3 col-form-label col-form-label-lg">Dirección</label>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                        class="form-control form-control-lg"
                                                        id="company-address"
                                                        placeholder="123 Calle de Mascotas"
                                                        wire:model="address"
                                                        style="background-color: #e6f9f5;">
                                                    @error('address')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Campo NIT -->
                                            <div class="form-group row">
                                                <label for="company-nit"
                                                    class="col-sm-3 col-form-label col-form-label-lg">NIT</label>
                                                <div class="col-sm-9">
                                                    <input type="text"
                                                        class="form-control form-control-lg"
                                                        id="company-nit"
                                                        placeholder="123456789"
                                                        wire:model="nit"
                                                        style="background-color: #e6f9f5;">
                                                    @error('nit')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de Actualizar -->
                            <div class="text-center mt-4">
                                <button class="btn btn-success px-5 py-2"
                                    style="border-radius: 50px; background-color: #008080; border: none; font-weight: bold; font-size: 1.2rem;"
                                    wire:click="updateCompany">Actualizar Empresa</button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .custom-profile-image {
        text-align: center;
    }

    .drop-area {
        border: 2px dashed #008080;
        border-radius: 10px;
        padding: 20px;
        background-color: #f7fdfc;
        transition: background-color 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .drop-area:hover {
        background-color: #e6f9f5;
    }

    .drop-area-label {
        font-size: 1.2rem;
        color: #008080;
    }

    .file-input {
        display: none;
        /* Oculta el input file */
    }

    .img-preview {
        margin-top: 15px;
        border: 2px solid #008080;
        border-radius: 10px;
        padding: 5px;
        background-color: #ffffff;
        display: inline-block;
    }

    .img-thumbnail {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }
</style>

<script>
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
</script>
