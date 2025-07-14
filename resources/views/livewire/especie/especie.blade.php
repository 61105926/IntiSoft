<div>
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <!-- BREADCRUMB -->
            <div class="page-meta">
                <nav class="breadcrumb-style-one"
                    aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Especies</a></li>
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
                            <i class="fa fa-paw"></i> Añadir Especie
                        </button>
                    </div>
                    <div class="col-6">
                        <label for="">Buscar por Nombre </label>
                        @include('common.searchbox')
                    </div>
                </div>

                @include('livewire.especie.form')
            </div>

        </div>
        <div class="widget-content widget-content-area br-8">
            <div id="invoice-list_wrapper"
                class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="table-responsive">
                    <table id="invoice-list"
                        class="table table-hover table-striped table-bordered"
                        style="width: 100%;"
                        role="grid"
                        aria-describedby="invoice-list_info">
                        <thead>
                            <tr role="row">
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $especie)
                                <tr>
                                    <td>{{ $especie->id }}</td>
                                    <td>{{ $especie->nombre }}</td>
                                    <!-- Estado -->
                                    <td class="text-center">
                                        @if ($especie->state == 1)
                                            <span class="badge badge-success">Habilitado</span>
                                        @else
                                            <span class="badge badge-danger">Deshabilitado</span>
                                        @endif
                                    </td>
                                    <td class="text-center"
                                        style="width: 40px;">
                                        <div class="action-btns">
                                            <!-- Botón de editar -->
                                            <a class="btn btn-info bs-tooltip"
                                                wire:click='edit({{ $especie->id }})'
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- Botón de deshabilitar -->
                                            <a class="btn btn-danger bs-tooltip"
                                                onclick="Confirm({{ $especie->id }})"
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
