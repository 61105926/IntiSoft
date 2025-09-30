{{-- MODAL REGISTRAR PAGO --}}
@if($selectedAlquiler)
<div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg">

            {{-- HEADER --}}
            <div class="modal-header bg-gradient-success text-white border-0">
                <div>
                    <h4 class="modal-title mb-1 fw-bold">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Registrar Pago
                    </h4>
                    <p class="mb-0 opacity-75">{{ $selectedAlquiler->numero_contrato }}</p>
                </div>
                <button type="button" class="btn-close btn-close-white" wire:click="closePaymentModal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body p-4">

                <form wire:submit.prevent="savePayment">

                    {{-- RESUMEN FINANCIERO --}}
                    <div class="card border-0 shadow-sm mb-4 bg-light">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <p class="text-muted mb-1 small">Total del Alquiler</p>
                                    <h4 class="mb-0 text-primary">Bs. {{ number_format($selectedAlquiler->total, 2) }}</h4>
                                </div>
                                <div class="col-4">
                                    <p class="text-muted mb-1 small">Total Pagado</p>
                                    <h4 class="mb-0 text-success">Bs. {{ number_format($selectedAlquiler->anticipo, 2) }}</h4>
                                </div>
                                <div class="col-4">
                                    <p class="text-muted mb-1 small">Saldo Pendiente</p>
                                    <h4 class="mb-0 {{ $selectedAlquiler->saldo_pendiente > 0 ? 'text-danger' : 'text-success' }}">
                                        Bs. {{ number_format($selectedAlquiler->saldo_pendiente, 2) }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($selectedAlquiler->saldo_pendiente > 0)

                    {{-- DATOS DEL PAGO --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-credit-card me-2"></i>
                                Información del Pago
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Monto a Pagar <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs.</span>
                                        <input type="number" step="0.01" wire:model="monto_pago" class="form-control" placeholder="0.00" max="{{ $selectedAlquiler->saldo_pendiente }}">
                                    </div>
                                    @error('monto_pago') <small class="text-danger">{{ $message }}</small> @enderror
                                    <div class="form-text">
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-1" wire:click="$set('monto_pago', {{ $selectedAlquiler->saldo_pendiente }})">
                                            Pagar saldo completo
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Fecha de Pago <span class="text-danger">*</span></label>
                                    <input type="date" wire:model="fecha_pago" class="form-control" value="{{ now()->format('Y-m-d') }}">
                                    @error('fecha_pago') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Método de Pago <span class="text-danger">*</span></label>
                                    <select wire:model="metodo_pago" class="form-select">
                                        <option value="">Seleccionar...</option>
                                        <option value="EFECTIVO">💵 Efectivo</option>
                                        <option value="QR">📱 QR/Transferencia</option>
                                        <option value="TARJETA">💳 Tarjeta</option>
                                        <option value="DEPOSITO">🏦 Depósito Bancario</option>
                                    </select>
                                    @error('metodo_pago') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                @if(in_array($metodo_pago, ['QR', 'TARJETA', 'DEPOSITO']))
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Número de Referencia/Transacción</label>
                                    <input type="text" wire:model="referencia_pago" class="form-control" placeholder="Ej: TXN123456">
                                </div>
                                @endif

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Observaciones</label>
                                    <textarea wire:model="observaciones_pago" class="form-control" rows="2" placeholder="Notas adicionales sobre el pago..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- APLICAR GARANTÍA --}}
                    @if($selectedAlquiler->tieneGarantia() && in_array($selectedAlquiler->tipo_garantia, ['EFECTIVO', 'QR']) && $selectedAlquiler->estado_garantia === 'PENDIENTE' && $selectedAlquiler->monto_garantia > 0)
                    <div class="card border-0 shadow-sm mb-4 border-warning">
                        <div class="card-header bg-warning bg-opacity-10">
                            <h6 class="mb-0 fw-semibold text-warning">
                                <i class="fas fa-shield-alt me-2"></i>
                                Garantía Disponible
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning border-0 mb-3">
                                <strong>Monto de Garantía:</strong> Bs. {{ number_format($selectedAlquiler->monto_garantia, 2) }}
                                <br><strong>Tipo:</strong> {{ $selectedAlquiler->tipo_garantia_display }}
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="aplicar_garantia_pago" wire:model="aplicar_garantia_pago">
                                <label class="form-check-label fw-semibold" for="aplicar_garantia_pago">
                                    Aplicar garantía como parte del pago
                                </label>
                            </div>
                            @if($aplicar_garantia_pago)
                            <div class="alert alert-info border-0 mt-3 mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                La garantía de Bs. {{ number_format($selectedAlquiler->monto_garantia, 2) }} se aplicará automáticamente al saldo.
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @else
                    {{-- ALQUILER YA PAGADO --}}
                    <div class="alert alert-success border-0 text-center py-5">
                        <i class="fas fa-check-circle fs-1 text-success mb-3"></i>
                        <h4 class="fw-bold">Alquiler Totalmente Pagado</h4>
                        <p class="text-muted mb-0">No hay saldo pendiente para este alquiler</p>
                    </div>
                    @endif

                    {{-- HISTORIAL DE PAGOS --}}
                    @if($selectedAlquiler->pagos && $selectedAlquiler->pagos->count() > 0)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-history me-2"></i>
                                Historial de Pagos
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Método</th>
                                            <th class="text-end">Monto</th>
                                            <th>Referencia</th>
                                            <th>Usuario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selectedAlquiler->pagos as $pago)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($pago->metodo_pago === 'EFECTIVO')
                                                    <span class="badge bg-success">💵 Efectivo</span>
                                                @elseif($pago->metodo_pago === 'QR')
                                                    <span class="badge bg-info">📱 QR</span>
                                                @elseif($pago->metodo_pago === 'TARJETA')
                                                    <span class="badge bg-primary">💳 Tarjeta</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $pago->metodo_pago }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end fw-bold">Bs. {{ number_format($pago->monto, 2) }}</td>
                                            <td><small class="text-muted">{{ $pago->referencia ?? 'N/A' }}</small></td>
                                            <td><small>{{ $pago->usuario->name ?? 'Sistema' }}</small></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="2">TOTAL PAGADO:</th>
                                            <th class="text-end text-success">Bs. {{ number_format($selectedAlquiler->anticipo, 2) }}</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                </form>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" wire:click="closePaymentModal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                @if($selectedAlquiler->saldo_pendiente > 0)
                <button type="button" wire:click="savePayment" class="btn btn-success">
                    <i class="fas fa-check me-2"></i>Confirmar Pago
                </button>
                @endif
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
.bg-gradient-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}
</style>
