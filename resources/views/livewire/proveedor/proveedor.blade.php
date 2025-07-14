<div>
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <!-- BREADCRUMB -->
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Proveedor</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lista</li>
                    </ol>
                </nav>
            </div>
            <!-- /BREADCRUMB -->
            <div class="widget-content widget-content-area br-8">
                <div class="row">
                    <div class="col">
                        <button type="button" class="btn btn-primary _effect--ripple waves-effect waves-light"
                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fa fa-user"></i> Añadir Proveedor
                        </button>

                    </div>
                    <div class="col">
                        @include('common.searchbox')
                    </div>
                </div>

                @include('livewire.proveedor.form')
            </div>
        </div>
        <div class="card widget-content widget-content-area br-8">
            <div class="table-responsive p-3" style="max-height: 600px; overflow-y: auto;">
                <table id="invoice-list" class="table table-hover table-bordered mb-0" style="width: 100%;" role="grid">
                    <thead class="table-dark" style="position: sticky; top: 0; z-index: 1020; background-color: white; color: black;">
                         <tr>
                                <th style="position: sticky; left: 0; background-color: white; z-index: 1030;">ID</th>
                                <th style="position: sticky; left: 50px; background-color: white; z-index: 1030;">Nombre</th>
                                <th>NIT</th>
                                <th >Dirección</th>
                                <th>Teléfono 1</th>
                                <th >Teléfono 2</th>
                                <th >Email</th>
                                <th >N° Cuenta</th>
                                <th >Categoría</th>
                                <th >Estado</th>
                                <th >Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proveedores as $proveedor)
                            <tr>
                                <td style="position: sticky; left: 0; background-color: white;" class="text-center">{{ $proveedor->id }}</td>
                                <td style="position: sticky; left: 50px; background-color: white;">{{ $proveedor->proveedor_nombre }}</td>
                                <td >{{ $proveedor->nit }}</td>
                                <td>{{ $proveedor->direccion }}</td>
                                <td class="text-center">{{ $proveedor->telefono1 }}</td>
                                <td class="text-center">{{ $proveedor->telefono2 }}</td>
                                <td>{{ $proveedor->email }}</td>
                                <td>{{ $proveedor->numero_cuenta }}</td>
                                <td>{{ $proveedor->categoria }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $proveedor->estado ? 'bg-success' : 'bg-danger' }}">
                                        {{ $proveedor->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button wire:click="edit({{$proveedor->id}})" 
                                                class="btn btn-sm btn-info mr-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="toggleState({{$proveedor->id}})"
                                                class="btn btn-sm {{ $proveedor->estado ? 'btn-danger' : 'btn-success' }}">
                                            <i class="fas fa-{{ $proveedor->estado ? 'times' : 'check' }}"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <br>
                {{ $proveedores->links() }}
            </div>
        </div>
    </div>
</div>
<style>
    .table {
        font-size: 0.875rem;
    }

    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        padding: 0.75rem;
        vertical-align: middle;
    }

    .table tbody td {
        padding: 0.75rem;
        vertical-align: middle;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.5em 0.75em;
        border-radius: 30px;
    }

    .btn-group {
        gap: 0.25rem;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.2rem;
        transition: all 0.2s;
    }

    .btn-sm:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.02);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.04);
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
    }

    .table-responsive {
        border-radius: 0.5rem;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
</style>

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
    document.addEventListener('livewire:load', function() {
        Livewire.on('mostrarAlertaFaild', function(accion, codigo) {
            var mensaje = accion;

            Swal.fire({
                position: 'top-center',
                icon: 'error',
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
