{{-- MODAL REGISTRAR DEVOLUCIÓN PROFESIONAL --}}
@if($selectedAlquiler)
<div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg">

            {{-- HEADER --}}
            <div class="modal-header bg-success text-white border-0">
                <div>
                    <h4 class="modal-title mb-1 fw-bold">
                        <i class="fas fa-undo-alt me-2"></i>
                        Registro de Devolución
                    </h4>
                    <p class="mb-0 opacity-75">{{ $selectedAlquiler->numero_contrato }} - {{ $selectedAlquiler->cliente->nombres }} {{ $selectedAlquiler->cliente->apellidos }}</p>
                </div>
                <button type="button" class="btn-close btn-close-white" wire:click="closeDevolucionModal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body p-4">

                {{-- INFORMACIÓN DEL ALQUILER --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100 bg-info bg-opacity-10">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-day text-info fs-3 mb-2"></i>
                                <p class="text-muted small mb-1">Fecha de Alquiler</p>
                                <h6 class="mb-0">{{ \Carbon\Carbon::parse($selectedAlquiler->fecha_alquiler)->format('d/m/Y') }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100 bg-warning bg-opacity-10">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-check text-warning fs-3 mb-2"></i>
                                <p class="text-muted small mb-1">Devolución Programada</p>
                                <h6 class="mb-0">{{ \Carbon\Carbon::parse($selectedAlquiler->fecha_devolucion_programada)->format('d/m/Y') }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100
                            @if(now()->gt($selectedAlquiler->fecha_devolucion_programada))
                                bg-danger bg-opacity-10
                            @else
                                bg-success bg-opacity-10
                            @endif">
                            <div class="card-body text-center">
                                <i class="fas fa-clock
                                    @if(now()->gt($selectedAlquiler->fecha_devolucion_programada))
                                        text-danger
                                    @else
                                        text-success
                                    @endif fs-3 mb-2"></i>
                                <p class="text-muted small mb-1">Días de Retraso</p>
                                <h6 class="mb-0
                                    @if(now()->gt($selectedAlquiler->fecha_devolucion_programada))
                                        text-danger
                                    @endif">
                                    {{ now()->diffInDays($selectedAlquiler->fecha_devolucion_programada, false) > 0 ? 0 : abs(now()->diffInDays($selectedAlquiler->fecha_devolucion_programada, false)) }} días
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100 bg-primary bg-opacity-10">
                            <div class="card-body text-center">
                                <i class="fas fa-money-bill-wave text-primary fs-3 mb-2"></i>
                                <p class="text-muted small mb-1">Total Alquiler</p>
                                <h6 class="mb-0">Bs. {{ number_format($selectedAlquiler->total, 2) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="procesarDevolucion">

                    {{-- FECHA Y HORA DE DEVOLUCIÓN --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Fecha y Hora de Devolución Real
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Fecha de Devolución Real <span class="text-danger">*</span></label>
                                    <input type="date" wire:model="fecha_devolucion_real" class="form-control" value="{{ now()->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Hora de Devolución</label>
                                    <input type="time" wire:model="hora_devolucion_real" class="form-control" value="{{ now()->format('H:i') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CONJUNTOS A DEVOLVER --}}
                    @php
                        $detallesConConjuntos = $selectedAlquiler->detalles->filter(fn($d) => $d->instanciaConjunto);
                    @endphp

                    @if($detallesConConjuntos->count() === 0)
                        <div class="alert alert-danger border-0">
                            <h5><i class="fas fa-exclamation-triangle me-2"></i>No se puede procesar devolución</h5>
                            <p class="mb-0">Este alquiler no tiene conjuntos folklóricos registrados. Es un registro antiguo sin datos de conjuntos.</p>
                        </div>
                    @else
                        @foreach($selectedAlquiler->detalles as $detalleIndex => $detalle)
                        @if($detalle->instanciaConjunto)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary bg-opacity-10">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    {{ $detalle->instanciaConjunto->variacionConjunto->conjunto->nombre ?? 'Conjunto' }}
                                    <span class="badge bg-info ms-2">{{ $detalle->instanciaConjunto->numero_serie }}</span>
                                </h6>
                                <div>
                                    <span class="badge bg-secondary">{{ $detalle->instanciaConjunto->variacionConjunto->nombre_variacion ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            {{-- ESTADO GENERAL DEL CONJUNTO --}}
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Estado General del Conjunto</label>
                                    <select wire:model="devolucionDetalles.{{ $detalleIndex }}.estado_general" class="form-select">
                                        <option value="COMPLETO">✅ Completo y en buen estado</option>
                                        <option value="INCOMPLETO">⚠️ Incompleto (falta componentes)</option>
                                        <option value="CON_DAÑOS">🔧 Con daños</option>
                                        <option value="PERDIDO">❌ Perdido completamente</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Observaciones Generales</label>
                                    <input type="text" wire:model="devolucionDetalles.{{ $detalleIndex }}.observaciones_generales" class="form-control" placeholder="Notas sobre el conjunto...">
                                </div>
                            </div>

                            {{-- VERIFICACIÓN PRENDA POR PRENDA (COMPONENTES) --}}
                            @if($detalle->instanciaConjunto->instanciaComponentes && $detalle->instanciaConjunto->instanciaComponentes->count() > 0)
                            <div class="border-top pt-3 bg-light rounded p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-semibold mb-0">
                                        <i class="fas fa-tasks me-2 text-success"></i>
                                        Verificación Prenda por Prenda
                                    </h6>
                                    <span class="badge bg-info">{{ $detalle->instanciaConjunto->instanciaComponentes->count() }} prendas/componentes</span>
                                </div>
                                <div class="alert alert-info border-0 mb-3 py-2">
                                    <small><i class="fas fa-info-circle me-1"></i> Marque cada prenda/componente según su estado al momento de la devolución</small>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0 bg-white">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="5%" class="text-center">
                                                    <i class="fas fa-check-circle"></i>
                                                </th>
                                                <th width="30%">Prenda/Componente</th>
                                                <th width="20%">Estado de Devolución</th>
                                                <th width="15%">Costo Daño/Pérdida (Bs.)</th>
                                                <th width="30%">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($detalle->instanciaConjunto->instanciaComponentes as $compIndex => $componente)
                                            <tr class="align-middle">
                                                <td class="text-center">
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input type="checkbox" class="form-check-input"
                                                            wire:model="devolucionDetalles.{{ $detalleIndex }}.componentes.{{ $componente->id }}.presente"
                                                            id="check_{{ $detalleIndex }}_{{ $componente->id }}"
                                                            checked>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-gradient rounded-3 p-2 me-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                            <i class="fas fa-tshirt text-white"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ $componente->componente->nombre ?? 'Componente' }}</div>
                                                            @if($componente->numero_serie_componente)
                                                            <small class="text-muted">
                                                                <i class="fas fa-barcode me-1"></i>S/N: {{ $componente->numero_serie_componente }}
                                                            </small>
                                                            @endif
                                                            @if($componente->componente->descripcion)
                                                            <br><small class="text-muted fst-italic">{{ $componente->componente->descripcion }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <select wire:model="devolucionDetalles.{{ $detalleIndex }}.componentes.{{ $componente->id }}.estado"
                                                        class="form-select form-select-sm"
                                                        wire:change="$refresh">
                                                        <option value="DEVUELTO">✅ Devuelto en Buen Estado</option>
                                                        <option value="DAÑADO_LEVE">⚠️ Daño Leve (reparable)</option>
                                                        <option value="DAÑADO_GRAVE">🔧 Daño Grave (costoso)</option>
                                                        <option value="PERDIDO">❌ Perdido o Extraviado</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    @php
                                                        $estadoComponente = $devolucionDetalles[$detalleIndex]['componentes'][$componente->id]['estado'] ?? 'DEVUELTO';
                                                        $requiereCosto = in_array($estadoComponente, ['DAÑADO_LEVE', 'DAÑADO_GRAVE', 'PERDIDO']);
                                                    @endphp
                                                    @if($requiereCosto)
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-warning text-dark">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </span>
                                                        <input type="number"
                                                            wire:model="devolucionDetalles.{{ $detalleIndex }}.componentes.{{ $componente->id }}.costo_penalizacion"
                                                            class="form-control form-control-sm"
                                                            placeholder="0.00"
                                                            step="0.01"
                                                            min="0">
                                                    </div>
                                                    @else
                                                    <div class="text-center">
                                                        <span class="badge bg-success-subtle text-success">Sin costo</span>
                                                    </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <textarea
                                                        wire:model="devolucionDetalles.{{ $detalleIndex }}.componentes.{{ $componente->id }}.observaciones"
                                                        class="form-control form-control-sm"
                                                        rows="1"
                                                        placeholder="Descripción del daño o motivo..."></textarea>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                        @endif
                        @endforeach
                    @endif

                    {{-- PENALIZACIONES Y MULTAS --}}
                    <div class="card border-0 shadow-sm mb-4 border-warning">
                        <div class="card-header bg-warning bg-opacity-10">
                            <h6 class="mb-0 fw-semibold text-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Penalizaciones y Multas
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Multa por Retraso</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs.</span>
                                        <input type="number" step="0.01" wire:model="penalizacion_retraso" class="form-control" placeholder="0.00">
                                    </div>
                                    <small class="text-muted">Por días de atraso en la devolución</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Multa por Daños</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs.</span>
                                        <input type="number" step="0.01" wire:model="penalizacion_danos" class="form-control" placeholder="0.00">
                                    </div>
                                    <small class="text-muted">Costo de reparación de componentes dañados</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Multa por Pérdida</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs.</span>
                                        <input type="number" step="0.01" wire:model="penalizacion_perdida" class="form-control" placeholder="0.00">
                                    </div>
                                    <small class="text-muted">Costo de reposición de componentes perdidos</small>
                                </div>
                            </div>

                            <div class="alert alert-warning border-0 mt-3 mb-0">
                                <div class="row">
                                    <div class="col-md-9">
                                        <strong><i class="fas fa-calculator me-2"></i>Total de Penalizaciones:</strong>
                                        <p class="mb-0 small">Se sumará al total del alquiler y se descontará de la garantía si aplica</p>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <h4 class="mb-0 text-danger">
                                            Bs. {{ number_format(($penalizacion_retraso ?? 0) + ($penalizacion_danos ?? 0) + ($penalizacion_perdida ?? 0), 2) }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- GARANTÍA --}}
                    @if($selectedAlquiler->tieneGarantia() && $selectedAlquiler->estado_garantia !== 'DEVUELTA')
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-shield-alt me-2 text-warning"></i>
                                Gestión de Garantía
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info border-0 mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Tipo:</strong> {{ $selectedAlquiler->tipo_garantia_display }}<br>
                                        @if($selectedAlquiler->documento_garantia)
                                        <strong>Documento:</strong> {{ $selectedAlquiler->documento_garantia }}<br>
                                        @endif
                                        @if($selectedAlquiler->monto_garantia > 0)
                                        <strong>Monto Original:</strong> Bs. {{ number_format($selectedAlquiler->monto_garantia, 2) }}
                                        @endif
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p class="mb-1 small">Penalizaciones Totales</p>
                                        <h4 class="text-danger">Bs. {{ number_format(($penalizacion_retraso ?? 0) + ($penalizacion_danos ?? 0) + ($penalizacion_perdida ?? 0), 2) }}</h4>
                                        @if($selectedAlquiler->monto_garantia > 0)
                                        <p class="mb-0 small">Monto a Devolver: <strong>Bs. {{ number_format(max(0, $selectedAlquiler->monto_garantia - (($penalizacion_retraso ?? 0) + ($penalizacion_danos ?? 0) + ($penalizacion_perdida ?? 0))), 2) }}</strong></p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="aplicar_penalizaciones_garantia" wire:model="aplicar_penalizaciones_garantia" checked>
                                <label class="form-check-label fw-semibold" for="aplicar_penalizaciones_garantia">
                                    Descontar penalizaciones de la garantía
                                </label>
                            </div>

                            @if($selectedAlquiler->tipo_garantia !== 'EFECTIVO' && $selectedAlquiler->tipo_garantia !== 'QR')
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="devolver_garantia" wire:model="devolver_garantia" checked>
                                <label class="form-check-label fw-semibold" for="devolver_garantia">
                                    Devolver documento de garantía al cliente
                                </label>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- OBSERVACIONES FINALES --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <label class="form-label fw-semibold">Observaciones Finales de la Devolución</label>
                            <textarea wire:model="observaciones_devolucion" class="form-control" rows="3" placeholder="Comentarios generales sobre la devolución, estado de los conjuntos, acuerdos con el cliente, etc."></textarea>
                        </div>
                    </div>

                </form>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" wire:click="closeDevolucionModal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                @php
                    $puedeDevolver = $selectedAlquiler->detalles->filter(fn($d) => $d->instanciaConjunto)->count() > 0;
                @endphp
                <button type="button" wire:click="procesarDevolucion" class="btn btn-success btn-lg" {{ !$puedeDevolver ? 'disabled' : '' }}>
                    <i class="fas fa-check-circle me-2"></i>Confirmar Devolución
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
</style>
