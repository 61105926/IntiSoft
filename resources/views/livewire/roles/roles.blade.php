<div>
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <!-- BREADCRUMB -->
            <div class="page-meta">
                <nav class="breadcrumb-style-one"
                    aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Roles</a></li>
                        <li class="breadcrumb-item active"
                            aria-current="page">Lista</li>
                    </ol>
                </nav>
            </div>
            <!-- /BREADCRUMB -->
            <div class="widget-content widget-content-area br-8">
                <div class="row">
                    <div class="col">
                        <button type="button"
                            class="btn btn-primary _effect--ripple waves-effect waves-light"
                            data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            <i class="fa fa-user"></i> Añadir Rol
                        </button>
                    </div>
                    <div class="col">
                        @include('common.searchbox')
                    </div>
                </div>

                @include('livewire.roles.form')
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
                                <th class="sorting"
                                    style="width: 63px;">id</th>
                                <th class="sorting"
                                    tabindex="0"
                                    aria-controls="invoice-list"
                                    rowspan="1"
                                    colspan="1"
                                    aria-label="Name: activate to sort column ascending"
                                    style="width: 63px;">Nombre</th>

                                <th class="sorting"
                                    tabindex="0"
                                    aria-controls="invoice-list"
                                    rowspan="1"
                                    colspan="1"
                                    aria-label="Actions: activate to sort column ascending"
                                    style="width: 84px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>

                                    <td>{{ $role->name }}</td>
                                    <td class="text-center"
                                        style="width: 40px;">
                                        <div class="action-btns">
                                            <!-- Botón de editar -->
                                            <a wire:click='edit({{ $role->id }})'
                                                href="javascript:void(0);"class="btn btn-info bs-tooltip"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Botón de eliminar -->
                                            <button class="btn btn-danger bs-tooltip"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Eliminar"
                                                onclick="Confirm({{ $role->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach



                        </tbody>

                    </table>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('show-modal', msg => {
            $('#exampleModal').modal('show');

        });
        window.livewire.on('user-added', msg => {
            $('#exampleModal').modal('hide');

        });
        window.livewire.on('user-updated', msg => {
            $('#exampleModal').modal('hide');

        });
    });

    function Confirm(id) {
        Swal.fire({
            icon: 'warning',
            title: '¡Desea eliminar el registro!',
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
