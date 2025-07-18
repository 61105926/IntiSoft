<!-- TRANSFERENCIAS -->
<div class="tab-pane fade {{ $tabActivo === 'transferencias' ? 'show active' : '' }}" id="transferencias" role="tabpanel">
    <div class="mb-3 d-flex justify-content-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transferenciaModal">
            <i class="fas fa-exchange-alt me-1"></i> Nueva Transferencia
        </button>
    </div>
    @if ($mensajeExito)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $mensajeExito }}
            <button type="button" class="btn-close" wire:click="$set('mensajeExito', null)"
                aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header fw-bold">
            Transferencias entre Sucursales ({{ count($transferencias) }})
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Origen → Destino</th>
                        <th>Solicitante</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Productos</th>
                        <th>Motivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transferencias as $t)
                        <tr>
                            <td class="font-monospace">{{ $t->numero_transferencia }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span>{{ $t->sucursalOrigen->nombre }}</span>
                                    <i class="fas fa-right-left text-muted"></i>
                                    <span>{{ $t->sucursalDestino->nombre }}</span>
                                </div>
                            </td>
                            <td>{{ $t->usuarioSolicita->username }}</td>
                            <td>{{ date('Y-m-d', strtotime($t->fecha_solicitud)) }}</td>
                            <td>{!! $this->getEstadoTransferenciaBadge($t->estado) !!}</td>
                            <td>
                                <ul class="mb-0 ps-3 small">
                                    @foreach ($t->detalleTransferencias as $p)
                                        <li>
                                            {{ $p->producto->nombre }}
                                            (Solicitada: {{ $p->cantidad_solicitada }}

                                        </li>)
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $t->motivo }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- Cambio aquí: removemos data-bs-toggle para abrir modal desde JS -->
                                    <button class="btn btn-outline-secondary btn-sm"
                                        wire:click.prevent="verDetalles({{ $t->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    @if ($t->estado === 'SOLICITADA')
                                        <button class="btn btn-success btn-sm"
                                            wire:click="autorizarTransferencia({{ $t->id }})">
                                            Autorizar
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade" id="detalleTransferenciaModal" tabindex="-1"
    aria-labelledby="detalleTransferenciaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="detalleTransferenciaModalLabel">Detalles de la Transferencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                @if ($transferenciaSeleccionada)
                    <p><strong>Número:</strong> {{ $transferenciaSeleccionada->numero_transferencia }}</p>
                    <p><strong>Sucursal Origen:</strong> {{ $transferenciaSeleccionada->sucursalOrigen->nombre }}</p>
                    <p><strong>Sucursal Destino:</strong> {{ $transferenciaSeleccionada->sucursalDestino->nombre }}</p>
                    <p><strong>Solicitante:</strong> {{ $transferenciaSeleccionada->usuarioSolicita->username }}</p>
                    <p><strong>Motivo:</strong> {{ $transferenciaSeleccionada->motivo }}</p>
                    <p><strong>Estado:</strong> {!! $this->getEstadoTransferenciaBadge($transferenciaSeleccionada->estado) !!}</p>

                    <h6 class="mt-3">Productos Transferidos</h6>
                    <ul class="list-group">
                        @foreach ($transferenciaSeleccionada->detalleTransferencias as $detalle)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $detalle->producto->nombre }}
                                <span class="badge bg-primary rounded-pill">x{{ $detalle->cantidad_solicitada }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('cerrar-modal-transferencia', event => {
            const modalEl = document.getElementById('transferenciaModal');
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.hide();
        });

        window.addEventListener('abrir-modal-detalle', event => {
            const modalEl = document.getElementById('detalleTransferenciaModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        });
    </script>
@endpush
