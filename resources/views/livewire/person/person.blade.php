<div>
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <!-- BREADCRUMB -->
            <div class="page-meta">
                <nav class="breadcrumb-style-one"
                    aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Persona</a></li>
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
                            <i class="fa fa-person"></i> Añadir Persona
                        </button>

                    </div>
                    <div class="col">
                        {{-- @include('common.searchbox') --}}
                    </div>
                </div>

                @include('livewire.person.form')
            </div>
        </div>
        <div class="card widget-content widget-content-area br-8">
            <div class="table-responsive p-3" style="max-height: 600px; overflow-y: auto;">
                <table id="invoice-list" class="table table-hover table-bordered mb-0" style="width: 100%;" role="grid">
                    <thead class="table-dark" style="position: sticky; top: 0; z-index: 1020; background-color: white; color: black;">
                         <tr role="row">
                                <th>ID</th>
                                <th>Codigo Usuario</th>
                                <th>Nombres</th>
                                <th>Apellido Paterno</th>
                                <th>Apellido Materno</th>
                                <th>Estado</th>
                                <th>Fecha Nacimiento</th>
                                <th>Nacionalidad</th>
                                <th>Domicilio</th>
                                <th>Ciudad</th>
                                <th>Sexo</th>
                                <th>Fecha Registro</td>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($persons as $person)
                                <tr>

                                    <td>{{ $person->id }}</td>
                                    <td><span class="badge badge-light-success"> {{ $person->code }}</span>
                                    <td>{{ $person->names }}</td>
                                    <td>{{ $person->last_name }}</td>
                                    <td>{{ $person->second_last_name }}</td>
                                    <td>
                                        @if ($person->state === 1)
                                            <span class="badge badge-light-success"> Activo</span>
                                        @else
                                            <span class="badge badge-light-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>{{ $person->birthdate }}</td>
                                    <td>{{ $person->nationality }}</td>
                                    <td>{{ $person->address }}</td>
                                    <td>{{ $person->city }}</td>
                                    <td>{{ $person->gender }}</td>
                                    <td>{{ $person->created_at }}</td>
                                    <td class="text-center"
                                        style="width: 40px;">
                                        <div class="action-btns">
                                            <!-- Botón de editar -->
                                            <a class="btn btn-info bs-tooltip"
                                                wire:click='edit({{ $person->id }})'
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- Botón de deshabilitar -->
                                            <a class="btn btn-danger bs-tooltip"
                                                onclick="Confirm({{ $person->id }})"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Deshabilitar">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            <!-- Botón de imprimir -->
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <td class="text-center">
                                    <span class="badge badge-light-danger">NO SE ENCONTRARON REGISTROS</span>
                                </td>
                            @endforelse



                        </tbody>

                    </table>
                </div>
                <br>
                {{ $persons->links() }}
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
