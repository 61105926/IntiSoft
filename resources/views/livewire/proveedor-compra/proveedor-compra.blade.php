<div>
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <!-- BREADCRUMB -->
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Compras a Proveedor</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lista</li>
                    </ol>
                </nav>
            </div>

            <!-- Card para el botón de añadir y buscador -->
            <div class="card widget-content widget-content-area br-8 mb-4">
                <div class="row align-items-center p-3">
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary btn-lg _effect--ripple waves-effect waves-light"
                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fas fa-plus"></i> Añadir Compra
                        </button>
                    </div>
                    <div class="col-md-6">
                        <label for="search" class="form-label">Buscar por ID, Proveedor o Monto</label>
                        @include('common.searchbox')
                    </div>
                    <div class="col-md-2">
                        <label for="pagination" class="form-label">Mostrar</label>
                        <select wire:model="pagination" class="form-select" id="pagination">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <!-- Filtros de fecha -->
                <div class="row align-items-center p-3">
                    <div class="col-md-3">
                        <label for="startDate" class="form-label">Fecha Inicio</label>
                        <input type="date" id="startDate" class="form-control" wire:model="startDate">
                    </div>
                    <div class="col-md-3">
                        <label for="endDate" class="form-label">Fecha Fin</label>
                        <input type="date" id="endDate" class="form-control" wire:model="endDate">
                    </div>
                    <div class="col-md-3 mt-4">
                        <button class="btn btn-info" wire:click.prevent="generatePurchaseReport" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="generatePurchaseReport">
                                <i class="fas fa-file-pdf"></i> Generar Reporte
                            </span>
                            <span wire:loading wire:target="generatePurchaseReport">
                                <i class="fas fa-spinner fa-spin"></i> Generando...
                            </span>
                        </button>
                    </div>
                </div>

                @include('livewire.proveedor-compra.form')
            </div>

            <!-- Card para la tabla -->
            <div class="card widget-content widget-content-area br-8">
                <div class="table-responsive p-3" style="max-height: 600px; overflow-y: auto;">
                    <table id="invoice-list" class="table table-hover table-bordered mb-0" style="width: 100%;" role="grid">
                        <thead class="table-dark" style="position: sticky; top: 0; z-index: 1020; background-color: white; color: black;">
                             <tr>
                                <th class="text-center">ID</th>
                                <th>Proveedor</th>
                                <th>Fecha de Compra</th>
                                <th>Monto Total (Bs)</th>
                                <th>Estado</th>
                                <th>Productos</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td class="text-center">{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $transaction->proveedor->proveedor_nombre }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y') }}</td>
                                    <td>{{ number_format($transaction->monto_pagado, 2) }} Bs</td>
                                    <td>
                    @if($transaction->estado_pago == 'Anulado')
                        <span class="badge bg-danger">Anulado</span>
                    @else
                        <span class="badge bg-success">Activo</span>
                    @endif
                </td>
                <td>
                    <ul class="list-unstyled mb-0">
                        @foreach ($transaction->details as $detail)
                            <li class="mb-2">
                                <strong>Producto:</strong> {{ $detail->product->nombre_producto }}
                                <br>
                                <strong>Cantidad:</strong> {{ $detail->cantidad }}<br>
                                <strong>Lote:</strong> {{ $detail->lote }}<br>
                                <strong>Fecha de Vencimiento:</strong> {{ $detail->fecha_vencimiento }}<br>
                                <strong>Monto de la Compra:</strong> {{ number_format($detail->cantidad * $detail->precio_compra, 2) }}<br>
                            </li>
                        @endforeach
                    </ul>
                </td>
                
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            @if($transaction->estado_pago != 'Anulado')
                                                <button class="btn btn-danger btn-sm" 
                                                        wire:click="$emit('confirmarAnulacion', {{ $transaction->id }})"
                                                        title="Anular Compra">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            @endif
                                          
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No hay registros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                

                <br>
                {{--  {{ $transactions->links() }}  --}}
            </div>
        </div>
    </div>
</div>

<style>
.table thead th {
    background-color: #650abb;
    color: white;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75rem;
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

.list-group-item {
    font-size: 0.875rem;
}

.table-responsive {
    border-radius: 0.5rem;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

.pagination {
    margin-bottom: 0;
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
    document.addEventListener('livewire:load', function () {
        Livewire.on('confirmarAnulacion', transactionId => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Se anulará la compra y se revertirá el stock",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, anular',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('anularCompra', transactionId);
                }
            });
        });
    });
</script>
