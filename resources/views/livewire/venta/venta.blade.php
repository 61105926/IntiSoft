<div>
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <!-- BREADCRUMB -->
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Ventas</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lista</li>
                    </ol>
                </nav>
            </div>
            <!-- /BREADCRUMB -->

            <!-- Card para el botón de añadir producto y buscador -->
            <div class="card widget-content widget-content-area br-8 mb-4">
                <div class="row align-items-center p-3">
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary btn-lg _effect--ripple waves-effect waves-light"
                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fa fa-plus"></i> Añadir Venta
                        </button>
                    </div>
                    <div class="col-md-6">
                        <label for="search" class="form-label">Buscar por Id Venta, Nombre cliente, Monto</label>
                        @include('common.searchbox')
                    </div>
                    <div class="col-md-2">

                        <label for="pagination" class="form-label">Mostrar</label>
                        <select wire:model="pagination" class="form-select" id="pagination">
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="0">Todo</option>
                        </select>
                    </div>
                </div>


                <div class="row align-items-center p-3">
                    <div class="col-md-3">
                        <label for="startDate" class="form-label">Fecha Inicio</label>
                        <input type="date" id="startDate" class="form-control" wire:model="startDate">
                    </div>
                    <div class="col-md-3">
                        <label for="endDate" class="form-label">Fecha Fin</label>
                        <input type="date" id="endDate" class="form-control" wire:model="endDate">
                    </div>
                </div>
                <form wire:submit.prevent="generateSalesReportPdf">
                    <button type="submit" class="btn btn-primary">
                        Descargar Reporte de Ventas
                    </button>
                </form>


                @include('livewire.venta.form')
            </div>
        </div>

        <!-- Card para la tabla de productos -->
        <div class="card widget-content widget-content-area br-8">
            <div class="table-responsive p-3" style="max-height: 600px; overflow-y: auto;">
                <table id="invoice-list" class="table table-hover table-bordered mb-0" style="width: 100%;"
                    role="grid">
                    <thead class="table-dark"
                        style="position: sticky; top: 0; z-index: 1020; background-color: white; color: black;">
                        <tr role="row">
                            <th>ID Venta</th>
                            <th>Fecha Venta</th> <!-- Nueva columna para la fecha -->
                            <th>Cliente</th>
                            <th>Subtotal</th>
                            <th>Descuento</th>
                            <th>Monto Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $venta)
                            <tr>
                                <td>{{ $venta->id }}</td>
                                <td>{{ $venta->created_at ? $venta->created_at->format('d/m/Y') : '' }}</td>
                                <!-- Muestra la fecha de venta -->
                                <td>
                                    <div class="avatar ">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7127/7127352.png"
                                            alt="Cliente" class="rounded-circle">
                                        <br>
                                        {{ $venta->cliente->nombre_completo }}
                                    </div>
                                </td>
                                <td>{{ $venta->subtotal }}</td>
                                <td>{{ $venta->descuento }}</td>
                                <td>{{ number_format($venta->total, 2) }} Bs</td>
                                <td>
                                    @if ($venta->estado == 1)
                                        <span class="badge badge-success px-3 py-2">
                                            <i class="fas fa-clock"></i> Vigente
                                        </span>
                                    @elseif($venta->estado == 0)
                                        <span class="badge badge-danger px-3 py-2">
                                            <i class="fas fa-times-circle"></i> Anulado
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('generate.pdf', ['id' => $venta->id]) }}"
                                        class="btn btn-primary bs-tooltip" data-toggle="tooltip" data-placement="top"
                                        title="Abrir en nueva pestaña" target="_blank">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a class="btn btn-danger btn-sm" onclick="Confirm({{ $venta->id }})"
                                        data-toggle="tooltip" data-placement="top" title="Anular">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
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
