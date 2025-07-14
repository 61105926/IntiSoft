<div>
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <!-- BREADCRUMB -->
            <div class="page-meta">
                <nav class="breadcrumb-style-one"
                    aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Cliente</a></li>
                        <li class="breadcrumb-item active"
                            aria-current="page">Lista</li>
                    </ol>
                </nav>
            </div>
            <!-- /BREADCRUMB -->
            <div class="widget-content widget-content-area br-8">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <button type="button"
                            class="btn btn-primary _effect--ripple waves-effect waves-light"
                            data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            <i class="fa fa-person"></i> Añadir Cliente
                        </button>
                    </div>
                    <div class="col-6">
                        <label for="">Buscar por CI o Nombre</label>
                        @include('common.searchbox')
                    </div>
                </div>

                @include('livewire.client.form')
            </div>


        </div>
        <div class="card widget-content widget-content-area br-8">
            <div class="table-responsive p-3" style="max-height: 600px; overflow-y: auto;">
                <table id="invoice-list" class="table table-hover table-bordered mb-0" style="width: 100%;" role="grid">
                    <thead class="table-dark" style="position: sticky; top: 0; z-index: 1020; background-color: white; color: black;">
                       
                            <tr role="row">
                                <th style="position: sticky; left: 0; background-color: white; z-index: 1030;">ID</th>
                                <th style="position: sticky; left: 60px; background-color: white; z-index: 1030;">C.I.</th>
                                <th style="position: sticky; left: 150px; background-color: white; z-index: 1030;">Nombre Completo</th>
                                <th>Nacionalidad</th>
                                <th>Sexo</th>
                                <th>F\N</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Teléfono 2</th>
                                <th>Correo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $datas)
                                <tr>
                                    <td style="position: sticky; left: 0; background-color: white;">{{ $datas->id }}</td>
                                    <td style="position: sticky; left: 60px; background-color: white;">{{ $datas->ci }}</td>
                                    <td style="position: sticky; left: 150px; background-color: white;">{{ $datas->nombre_completo }}</td>
                                    <td>{{ $datas->nacionalidad }}</td>
                                    <td>{{ $datas->sexo }}</td>
                                    <td>{{ $datas->fecha_nacimiento }}</td>
                                    <td>{{ $datas->direccion }}</td>
                                    <td>{{ $datas->numero_telefono }}</td>
                                    <td>{{ $datas->numero_telefono2 }}</td>
                                    <td>{{ $datas->correo }}</td>
                                    <td class="text-center"
                                        style="width: 40px;">
                                        <div class="action-btns">
                                            <!-- Botón de editar -->
                                            <a class="btn btn-info bs-tooltip"
                                                wire:click='edit({{ $datas->id }})'
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- Botón de deshabilitar -->
                                            <a class="btn btn-danger bs-tooltip"
                                                onclick="Confirm({{ $datas->id }})"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Deshabilitar">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11"
                                        class="text-center">
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
            var mensaje = accion ;

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
