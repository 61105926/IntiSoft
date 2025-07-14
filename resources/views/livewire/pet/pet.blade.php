<div>
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <!-- BREADCRUMB -->
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Mascotas</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lista</li>
                    </ol>
                </nav>
            </div>
            <!-- /BREADCRUMB -->
            <div class="widget-content widget-content-area br-8">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary _effect--ripple waves-effect waves-light"
                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fa fa-paw"></i> Añadir Mascota
                        </button>
                    </div>
                    <div class="col-6">
                        <label for="">Buscar por Nombre o Chip</label>
                        @include('common.searchbox')
                    </div>
                </div>

                @include('livewire.pet.form')
            </div>

        </div>
        <div class="widget-content widget-content-area br-8">
            <div id="invoice-list_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table id="invoice-list" class="table table-hover table-striped table-bordered" style="width: 100%;"
                        role="grid" aria-describedby="invoice-list_info">
                        <thead style="position: sticky; top: 0; z-index: 1020; background-color: white; color: black;">
                            <tr role="row">
                                <th style="position: sticky; left: 0; background-color: white; z-index: 1030;">ID</th>
                                <th style="position: sticky; left: 50px; background-color: white; z-index: 1030;">Nombre
                                </th>
                                <th style="position: sticky; left: 200px; background-color: white; z-index: 1030;">
                                    Especie</th>
                                <th>Raza</th>
                                <th>Sexo</th>
                                <th>F.N.</th>
                                <th>Color</th>
                                <th>Peso</th>
                                <th>Chip</th>
                                <th>Tatuaje</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $pet)
                                <tr>
                                    <td style="position: sticky; left: 0; background-color: white;">{{ $pet->id }}
                                    </td>

                                    <td
                                        style="position: sticky; left: 50px; background-color: white; text-align: center; vertical-align: middle;">
                                        {{ $pet->nombre }}
                                        <br>
                                        <img src="{{ $pet->image ? asset('storage/pet/' . $pet->image) : 'https://images.unsplash.com/photo-1507146426996-ef05306b995a?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDF8fHxlbnwwfHx8fHw%3D' }}"
                                            alt="Vista previa de la imagen" id="img-preview-image" class="img-thumbnail"
                                            style="display: block; margin: 0 auto; width: 100px; height: 100px; object-fit: cover;" />
                                    </td>

                                    <td style="position: sticky; left: 200px;">{{ $pet->especie }}</td>
                                    <td>{{ $pet->raza }}</td>
                                    <td>{{ $pet->sexo }}</td>
                                    <td>{{ $pet->fecha_nacimiento }}</td>
                                    <td>{{ $pet->color }}</td>
                                    <td>{{ $pet->peso }}</td>
                                    <td>{{ $pet->chip }}</td>
                                    <td>{{ $pet->tatuaje }}</td>
                                    <td class="text-center" style="width: 40px;">
                                        <div class="action-btns">
                                            <!-- Botón de perfil -->
                                            <a class="btn btn-secondary bs-tooltip"
                                                href="{{ route('pet.detail', $pet->id) }}" data-toggle="tooltip"
                                                data-placement="top" title="Perfil">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <!-- Botón de editar -->
                                            <a class="btn btn-info bs-tooltip" wire:click='edit({{ $pet->id }})'
                                                data-toggle="tooltip" data-placement="top" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- Botón de deshabilitar -->
                                            <a class="btn btn-danger bs-tooltip" onclick="Confirm({{ $pet->id }})"
                                                data-toggle="tooltip" data-placement="top" title="Deshabilitar">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">
                                        <span class="badge badge-light-danger">NO SE ENCONTRARON REGISTROS</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <br>
                {{ $data->links() }}
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
</script>
