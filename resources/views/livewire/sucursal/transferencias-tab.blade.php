 <!-- TRANSFERENCIAS -->
 <div class="tab-pane fade" id="transferencias" role="tabpanel">
     <div class="mb-3 d-flex justify-content-end">
         <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transferenciaModal">
             <i class="fas fa-exchange-alt me-1"></i> Nueva Transferencia
         </button>
     </div>
     <div class="card">
         <div class="card-header fw-bold">
             Transferencias entre Sucursales
             ({{ count($transferencias) }})
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
                             <td>{{ $t->fecha_solicitud }}</td>
                             <td>{!! $this->getEstadoTransferenciaBadge($t->estado) !!}</td>
                             <td>
                                 <ul class="mb-0 ps-3 small">
                                     @foreach ($t->detalleTransferencias as $p)
                                         <li>
                                             {{ $p->producto->nombre }}
                                             (Solicitada: {{ $p->cantidad_solicitada }},
                                             Enviada: {{ $p->cantidad_enviada ?? '0' }},
                                             Recibida: {{ $p->cantidad_recibida ?? '0' }})
                                         </li>
                                     @endforeach


                                 </ul>
                             </td>
                             <td>{{ $t->motivo }}</td>
                             <td>
                                 <div class="d-flex gap-2">
                                     <button class="btn btn-outline-secondary btn-sm"
                                         wire:click="verDetalles({{ $t->id }})" data-bs-toggle="modal"
                                         data-bs-target="#detalleTransferenciaModal">
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
                 <h5 class="modal-title" id="detalleTransferenciaModalLabel">
                     Detalles de la Transferencia</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
             </div>
             <div class="modal-body">
                 @if ($transferenciaSeleccionada)
                     <p><strong>Número:</strong>
                         {{ $transferenciaSeleccionada->numero_transferencia }}</p>
                     <p><strong>Sucursal Origen:</strong>
                         {{ $transferenciaSeleccionada->sucursalOrigen->nombre }}
                     </p>
                     <p><strong>Sucursal Destino:</strong>
                         {{ $transferenciaSeleccionada->sucursalDestino->nombre }}
                     </p>
                     <p><strong>Solicitante:</strong>
                         {{ $transferenciaSeleccionada->usuarioSolicita->username }}
                     </p>
                     <p><strong>Motivo:</strong>
                         {{ $transferenciaSeleccionada->motivo }}</p>
                     <p><strong>Estado:</strong>
                         {!! $this->getEstadoTransferenciaBadge($t->estado) !!}
                     </p>

                     <h6 class="mt-3">Productos Transferidos</h6>
                     <ul class="list-group">
                         @foreach ($transferenciaSeleccionada->detalleTransferencias as $detalle)
                             <li class="list-group-item d-flex justify-content-between align-items-center">
                                 {{ $detalle->producto->nombre }}
                                 <span
                                     class="badge bg-primary rounded-pill">x{{ $detalle->cantidad_solicitada }}</span>
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
