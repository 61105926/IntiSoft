{{-- MODAL VER DETALLES DEL ALQUILER --}}
@if($selectedAlquiler)
<div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg">

            {{-- HEADER --}}
            <div class="modal-header bg-gradient-primary text-white border-0">
                <div>
                    <h4 class="modal-title mb-1 fw-bold">
                        <i class="fas fa-file-contract me-2"></i>
                        Detalles del Alquiler
                    </h4>
                    <p class="mb-0 opacity-75">{{ $selectedAlquiler->numero_contrato }}</p>
                </div>
                <button type="button" class="btn-close btn-close-white" wire:click="closeViewAlquilerModal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body p-4">

                {{-- ESTADOS Y ALERTAS --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Estado del Alquiler</h6>
                                @if($selectedAlquiler->estado === 'ACTIVO')
                                    <h3 class="mb-0"><span class="badge bg-info px-4 py-2">ACTIVO</span></h3>
                                @elseif($selectedAlquiler->estado === 'DEVUELTO')
                                    <h3 class="mb-0"><span class="badge bg-success px-4 py-2">DEVUELTO</span></h3>
                                @elseif($selectedAlquiler->estado === 'VENCIDO')
                                    <h3 class="mb-0"><span class="badge bg-danger px-4 py-2">VENCIDO</span></h3>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Estado de Pago</h6>
                                @if($selectedAlquiler->estado_pago === 'PAGADO')
                                    <h3 class="mb-0"><span class="badge bg-success px-4 py-2">PAGADO</span></h3>
                                @elseif($selectedAlquiler->estado_pago === 'PARCIAL')
                                    <h3 class="mb-0"><span class="badge bg-warning text-dark px-4 py-2">PARCIAL</span></h3>
                                @else
                                    <h3 class="mb-0"><span class="badge bg-danger px-4 py-2">PENDIENTE</span></h3>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Total</h6>
                                <h3 class="mb-0 text-primary">Bs. {{ number_format($selectedAlquiler->total, 2) }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Saldo Pendiente</h6>
                                <h3 class="mb-0 {{ $selectedAlquiler->saldo_pendiente > 0 ? 'text-danger' : 'text-success' }}">
                                    Bs. {{ number_format($selectedAlquiler->saldo_pendiente, 2) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INFORMACIÓN DEL CLIENTE --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-user text-primary me-2"></i>
                            Información del Cliente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="text-muted" width="150"><strong>Nombre:</strong></td>
                                        <td>{{ $selectedAlquiler->cliente->nombres }} {{ $selectedAlquiler->cliente->apellidos }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>CI:</strong></td>
                                        <td>{{ $selectedAlquiler->cliente->carnet_identidad ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Teléfono:</strong></td>
                                        <td>{{ $selectedAlquiler->cliente->telefono ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="text-muted" width="150"><strong>Email:</strong></td>
                                        <td>{{ $selectedAlquiler->cliente->email ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Unidad Educativa:</strong></td>
                                        <td>{{ $selectedAlquiler->unidadEducativa->nombre ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Sucursal:</strong></td>
                                        <td>{{ $selectedAlquiler->sucursal->nombre }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FECHAS Y DURACIÓN --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-calendar text-info me-2"></i>
                            Fechas y Duración
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                                        <i class="fas fa-calendar-check fs-4 text-success"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0 small">Fecha de Inicio</p>
                                        <h5 class="mb-0">{{ \Carbon\Carbon::parse($selectedAlquiler->fecha_alquiler)->format('d/m/Y') }}</h5>
                                        <small class="text-muted">{{ $selectedAlquiler->hora_entrega }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-danger bg-opacity-10 rounded-3 p-3 me-3">
                                        <i class="fas fa-calendar-times fs-4 text-danger"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0 small">Fecha de Devolución</p>
                                        <h5 class="mb-0">{{ \Carbon\Carbon::parse($selectedAlquiler->fecha_devolucion_programada)->format('d/m/Y') }}</h5>
                                        <small class="text-muted">{{ $selectedAlquiler->hora_devolucion_programada }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-3 me-3">
                                        <i class="fas fa-clock fs-4 text-info"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0 small">Duración</p>
                                        <h5 class="mb-0">{{ $selectedAlquiler->dias_alquiler }} días</h5>
                                    </div>
                                </div>
                            </div>
                            @if($selectedAlquiler->fecha_devolucion_real)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                                        <i class="fas fa-check-circle fs-4 text-success"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0 small">Devolución Real</p>
                                        <h5 class="mb-0">{{ \Carbon\Carbon::parse($selectedAlquiler->fecha_devolucion_real)->format('d/m/Y H:i') }}</h5>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- CONJUNTOS FOLKLÓRICOS ALQUILADOS --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-users text-primary me-2"></i>
                            Conjuntos Folklóricos Alquilados
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @php
                            $tieneConjuntos = $selectedAlquiler->detalles->filter(fn($d) => $d->instanciaConjunto)->count() > 0;
                        @endphp

                        @if($tieneConjuntos)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Conjunto</th>
                                        <th>Variación</th>
                                        <th>N° Serie</th>
                                        <th class="text-end">Precio</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($selectedAlquiler->detalles as $detalle)
                                        @if($detalle->instanciaConjunto)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 rounded-2 p-2 me-2">
                                                        <i class="fas fa-users text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $detalle->instanciaConjunto->variacionConjunto->conjunto->nombre ?? 'Conjunto' }}</strong>
                                                        @if($detalle->instanciaConjunto->componentesActivos)
                                                        <br><small class="text-muted">{{ $detalle->instanciaConjunto->componentesActivos->count() }} componentes</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $detalle->instanciaConjunto->variacionConjunto->nombre_variacion ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $detalle->instanciaConjunto->numero_serie }}</span>
                                            </td>
                                            <td class="text-end">Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                                            <td class="text-end fw-bold">Bs. {{ number_format($detalle->subtotal, 2) }}</td>
                                            <td class="text-center">
                                                @if($detalle->estado_devolucion === 'DEVUELTO' || $detalle->estado_devolucion === 'COMPLETO')
                                                    <span class="badge bg-success">✅ Devuelto</span>
                                                @elseif($detalle->estado_devolucion === 'PERDIDO')
                                                    <span class="badge bg-danger">❌ Perdido</span>
                                                @elseif($detalle->estado_devolucion === 'CON_DAÑOS')
                                                    <span class="badge bg-warning text-dark">🔧 Con Daños</span>
                                                @elseif($detalle->estado_devolucion === 'INCOMPLETO')
                                                    <span class="badge bg-warning text-dark">⚠️ Incompleto</span>
                                                @else
                                                    <span class="badge bg-secondary">⏳ Pendiente</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-warning border-0 m-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Alquiler sin conjuntos registrados</strong>
                            <p class="mb-0 small">Este es un alquiler antiguo que no tiene conjuntos folklóricos asociados.</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- GARANTÍA --}}
                @if($selectedAlquiler->tieneGarantia())
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-shield-alt text-warning me-2"></i>
                            Garantía
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p class="text-muted mb-1 small">Tipo</p>
                                <h6>{{ $selectedAlquiler->tipo_garantia_display }}</h6>
                            </div>
                            @if($selectedAlquiler->documento_garantia)
                            <div class="col-md-3">
                                <p class="text-muted mb-1 small">Documento</p>
                                <h6>{{ $selectedAlquiler->documento_garantia }}</h6>
                            </div>
                            @endif
                            @if($selectedAlquiler->monto_garantia > 0)
                            <div class="col-md-3">
                                <p class="text-muted mb-1 small">Monto</p>
                                <h6 class="text-success">Bs. {{ number_format($selectedAlquiler->monto_garantia, 2) }}</h6>
                            </div>
                            @endif
                            <div class="col-md-3">
                                <p class="text-muted mb-1 small">Estado</p>
                                <h6>
                                    <span class="badge bg-{{ $selectedAlquiler->estado_garantia === 'DEVUELTA' ? 'success' : 'warning' }}">
                                        {{ $selectedAlquiler->estado_garantia_display }}
                                    </span>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- RESUMEN FINANCIERO --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-calculator text-success me-2"></i>
                            Resumen Financiero
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td class="text-muted">Subtotal:</td>
                                <td class="text-end">Bs. {{ number_format($selectedAlquiler->subtotal, 2) }}</td>
                            </tr>
                            @if($selectedAlquiler->descuento > 0)
                            <tr>
                                <td class="text-muted">Descuento:</td>
                                <td class="text-end text-danger">- Bs. {{ number_format($selectedAlquiler->descuento, 2) }}</td>
                            </tr>
                            @endif
                            @if($selectedAlquiler->penalizacion > 0)
                            <tr>
                                <td class="text-muted">Penalización:</td>
                                <td class="text-end text-danger">+ Bs. {{ number_format($selectedAlquiler->penalizacion, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="table-light">
                                <td><strong>Total:</strong></td>
                                <td class="text-end"><strong>Bs. {{ number_format($selectedAlquiler->total, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Anticipo:</td>
                                <td class="text-end text-success">Bs. {{ number_format($selectedAlquiler->anticipo, 2) }}</td>
                            </tr>
                            <tr class="table-primary">
                                <td><strong>Saldo Pendiente:</strong></td>
                                <td class="text-end"><strong class="{{ $selectedAlquiler->saldo_pendiente > 0 ? 'text-danger' : 'text-success' }}">Bs. {{ number_format($selectedAlquiler->saldo_pendiente, 2) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" wire:click="closeViewAlquilerModal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-primary" wire:click="printAlquiler({{ $selectedAlquiler->id }})">
                    <i class="fas fa-print me-2"></i>Imprimir
                </button>
            </div>

        </div>
    </div>
</div>

{{-- BACKDROP --}}
<div class="modal-backdrop fade show"></div>
@endif

<style>
.modal {
    background-color: rgba(0, 0, 0, 0.5);
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
