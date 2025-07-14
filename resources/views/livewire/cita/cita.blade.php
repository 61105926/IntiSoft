<div>
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <!-- BREADCRUMB -->
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-calendar-alt"></i> Citas</a></li>
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
                            <i class="fa fa-plus"></i> <strong>Añadir Citas</strong>
                        </button>
                    </div>
                    <div class="col-6">
                        <label for="">Buscar por CI o Nombre</label>
                        @include('common.searchbox')
                    </div>
                </div>

                @include('livewire.cita.form')
            </div>

            <div class="card widget-content widget-content-area br-8">
                <div class="table-responsive p-3" style="max-height: 600px; overflow-y: auto;">
                    <table id="invoice-list" class="table table-hover table-bordered mb-0" style="width: 100%;" role="grid">
                        <thead class="table-dark" style="position: sticky; top: 0; z-index: 1020; background-color: white; color: black;">
                             <tr role="row">
                                    <th><i class="fas fa-id-badge"></i> Nro Cita</th>
                                    <th><i class="fas fa-user"></i> Cliente</th>
                                    <th><i class="fas fa-paw"></i> Mascota</th>
                                    <th><i class="fas fa-calendar-check"></i> Fecha</th>
                                    <th><i class="fas fa-clock"></i> Hora</th>
                                    <th><i class="fas fa-info-circle"></i> Estado</th>
                                    <th><i class="fas fa-cogs"></i> Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $datas)
                                    <tr>
                                        <td><strong>Nro-{{ $datas->id }}</strong></td>
                                        <td>{{ $datas->client->nombre_completo }}</td>
                                        <td>{{ $datas->pet->nombre }}</td>
                                        <td>{{ \Carbon\Carbon::parse($datas->fecha)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($datas->hora)->format('H:i A') }}</td>
                                        <td>
                                            @if ($datas->estado == 1)
                                                <span class="badge badge-warning px-3 py-2">
                                                    <i class="fas fa-clock"></i> En espera
                                                </span>
                                            @elseif($datas->estado == 0)
                                                <span class="badge badge-danger px-3 py-2">
                                                    <i class="fas fa-times-circle"></i> Anulado
                                                </span>
                                            @elseif($datas->estado == 2)
                                                <span class="badge badge-success px-3 py-2">
                                                    <i class="fas fa-check-circle"></i> Atendido
                                                </span>
                                            @else
                                                <span class="badge badge-secondary px-3 py-2">
                                                    <i class="fas fa-question-circle"></i> Desconocido
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="action-btns d-flex justify-content-around">
                                                <!-- Botón de editar -->
                                                <a class="btn btn-info btn-sm shadow-sm"
                                                    wire:click='edit({{ $datas->id }})' data-toggle="tooltip"
                                                    data-placement="top" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <!-- Botón de deshabilitar -->
                                                <a class="btn btn-danger btn-sm shadow-sm"
                                                    onclick="Confirm({{ $datas->id }})" data-toggle="tooltip"
                                                    data-placement="top" title="Deshabilitar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                                <a class="btn btn-success btn-sm shadow-sm"
                                                href="https://api.whatsapp.com/send?phone={{ $datas->client->numero_telefono }}&text=%F0%9F%91%8B%20%21Hola%2C%20{{ urlencode($datas->client->nombre_completo) }}%21%0A%0A%F0%9F%93%85%20Tienes%20una%20cita%20con%20nosotros%20el%20{{ \Carbon\Carbon::parse($datas->fecha)->format('d/m/Y') }}%20a%20las%20{{ \Carbon\Carbon::parse($datas->hora)->format('H:i A') }}.%0A%0A%F0%9F%90%BE%20%21No%20te%20olvides%20de%20traer%20a%20{{ urlencode($datas->pet->nombre) }}%21%0A%0A%F0%9F%92%96%20Estamos%20muy%20emocionados%20de%20verlos%20y%20cuidar%20de%20tu%20mascota.%0A%0A%F0%9F%8C%9F%20%21Nos%20vemos%20pronto%21%20Si%20tienes%20alguna%20duda%2C%20no%20dudes%20en%20contactarnos."
                                                target="_blank">
                                                <i class="fab fa-whatsapp" style="margin-right: 8px;"></i> Enviar WhatsApp
                                             </a>
                                             
                                             
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <span class="badge badge-light-danger"><i
                                                    class="fas fa-exclamation-circle"></i> NO SE ENCONTRARON
                                                REGISTROS</span>
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
