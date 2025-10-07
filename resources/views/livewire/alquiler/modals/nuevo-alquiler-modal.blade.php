{{-- MODAL NUEVO ALQUILER - DISEÑO PROFESIONAL --}}
<div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg">

            {{-- HEADER --}}
            <div class="modal-header bg-primary text-white border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-3 p-2 me-3">
                        <i class="fas fa-tshirt fs-4"></i>
                    </div>
                    <div>
                        <h4 class="modal-title mb-0 fw-bold">Nuevo Alquiler de Trajes</h4>
                        <small class="opacity-75">Complete los datos del alquiler</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" wire:click="closeNewAlquilerModal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body p-4">

                {{-- ALERTAS --}}
                @if (session()->has('errorModal'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('errorModal') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form wire:submit.prevent="saveNewAlquiler">

                    {{-- SECCIÓN 1: INFORMACIÓN DEL CLIENTE --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-user text-primary me-2"></i>
                                1. Información del Cliente
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">Cliente <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <select wire:model="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror">
                                            <option value="">Seleccione un cliente</option>
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id }}">
                                                    {{ $cliente->nombres }} {{ $cliente->apellidos }} - CI: {{ $cliente->carnet_identidad }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" wire:click="openNewClienteModal">
                                            <i class="fas fa-plus"></i> Nuevo
                                        </button>
                                    </div>
                                    @error('cliente_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Unidad Educativa</label>
                                    <select wire:model="unidad_educativa_id" class="form-select">
                                        <option value="">Ninguna</option>
                                        @foreach($unidadesEducativas as $unidad)
                                            <option value="{{ $unidad->id }}">{{ $unidad->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 2: FECHAS Y DURACIÓN --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-calendar text-info me-2"></i>
                                2. Fechas y Duración
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Fecha de Alquiler <span class="text-danger">*</span></label>
                                    <input type="date" wire:model="fecha_alquiler" class="form-control @error('fecha_alquiler') is-invalid @enderror">
                                    @error('fecha_alquiler') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Hora Entrega</label>
                                    <input type="time" wire:model="hora_entrega" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Fecha de Devolución <span class="text-danger">*</span></label>
                                    <input type="date" wire:model="fecha_devolucion_programada" class="form-control @error('fecha_devolucion_programada') is-invalid @enderror">
                                    @error('fecha_devolucion_programada') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Hora Devolución</label>
                                    <input type="time" wire:model="hora_devolucion_programada" class="form-control">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Días <span class="text-danger">*</span></label>
                                    <input type="number" wire:model="dias_alquiler" class="form-control @error('dias_alquiler') is-invalid @enderror" min="1">
                                    @error('dias_alquiler') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 3: CONJUNTOS FOLKLÓRICOS --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-users text-primary me-2"></i>
                                3. Conjuntos Folklóricos a Alquilar
                            </h5>
                            <span class="badge bg-primary">{{ count($selectedConjuntos ?? []) }} conjuntos seleccionados</span>
                        </div>
                        <div class="card-body">

                            {{-- ALERTA SI NO HAY SUCURSAL SELECCIONADA --}}
                            @if(!$sucursal_id)
                                <div class="alert alert-info border-0 mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Importante:</strong> Primero debe seleccionar una <strong>Sucursal</strong> en la sección de "Pago y Detalles Adicionales" más abajo para poder agregar conjuntos folklóricos.
                                </div>
                            @endif

                            {{-- SELECTOR DE CONJUNTOS --}}
                            <div class="row g-3 mb-3">
                                <div class="col-md-10">
                                    <label class="form-label fw-semibold">Seleccionar Conjunto Disponible</label>
                                    <select wire:model="currentConjuntoId" class="form-select" {{ !$sucursal_id ? 'disabled' : '' }}>
                                        <option value="">{{ $sucursal_id ? 'Seleccione un conjunto...' : 'Primero seleccione una sucursal' }}</option>
                                        @foreach($conjuntos ?? [] as $instancia)
                                            <option value="{{ $instancia->id }}">
                                                {{ $instancia->variacionConjunto->conjunto->nombre ?? 'Conjunto' }}
                                                - {{ $instancia->variacionConjunto->nombre_variacion ?? '' }}
                                                ({{ $instancia->numero_serie }})
                                                - Bs. {{ number_format($instancia->variacionConjunto->conjunto->precio_alquiler_dia ?? 0, 2) }}/día
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($sucursal_id && count($conjuntos ?? []) === 0)
                                        <small class="text-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            No hay conjuntos disponibles en esta sucursal.
                                        </small>
                                    @endif
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">&nbsp;</label>
                                    <button type="button" wire:click="addConjuntoToAlquiler" class="btn btn-primary w-100" {{ !$sucursal_id ? 'disabled' : '' }}>
                                        <i class="fas fa-plus me-1"></i> Agregar
                                    </button>
                                </div>
                            </div>

                            {{-- TABLA DE CONJUNTOS SELECCIONADOS --}}
                            @if(count($selectedConjuntos ?? []) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Conjunto</th>
                                                <th>Variación</th>
                                                <th>N° Serie</th>
                                                <th class="text-end">Precio/Día</th>
                                                <th class="text-end">Subtotal ({{ $dias_alquiler }} días)</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($selectedConjuntos as $index => $conjunto)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-primary bg-opacity-10 rounded-2 p-2 me-2">
                                                                <i class="fas fa-users text-primary"></i>
                                                            </div>
                                                            <strong>{{ $conjunto['nombre'] }}</strong>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-info">{{ $conjunto['variacion'] }}</span></td>
                                                    <td><small class="text-muted">{{ $conjunto['numero_serie'] }}</small></td>
                                                    <td class="text-end">Bs. {{ number_format($conjunto['precio_unitario'] ?? 0, 2) }}</td>
                                                    <td class="text-end fw-bold">Bs. {{ number_format($conjunto['subtotal'] ?? 0, 2) }}</td>
                                                    <td class="text-center">
                                                        <button type="button" wire:click="removeConjuntoFromAlquiler({{ $index }})" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                                                <td class="text-end">
                                                    <h5 class="mb-0 text-primary">Bs. {{ number_format(array_sum(array_column($selectedConjuntos, 'subtotal')), 2) }}</h5>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-users fa-3x mb-3 opacity-25"></i>
                                    <p>No hay conjuntos seleccionados</p>
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- SECCIÓN 4: GARANTÍA --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-shield-alt text-warning me-2"></i>
                                4. Garantía
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Tipo de Garantía <span class="text-danger">*</span></label>
                                    <select wire:model="tipo_garantia" class="form-select @error('tipo_garantia') is-invalid @enderror">
                                        <option value="NINGUNA">Sin garantía</option>
                                        <option value="CI">Cédula de Identidad</option>
                                        <option value="EFECTIVO">Efectivo</option>
                                        <option value="QR">QR/Transferencia</option>
                                    </select>
                                    @error('tipo_garantia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                @if($tipo_garantia === 'CI')
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Número de CI <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="documento_garantia" class="form-control @error('documento_garantia') is-invalid @enderror" placeholder="Ej: 12345678 LP">
                                        @error('documento_garantia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                @endif

                                @if(in_array($tipo_garantia, ['EFECTIVO', 'QR']))
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Monto Garantía (Bs.) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" wire:model="monto_garantia" class="form-control @error('monto_garantia') is-invalid @enderror" placeholder="0.00">
                                        @error('monto_garantia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    @if($tipo_garantia === 'QR')
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">N° Transacción/Referencia</label>
                                            <input type="text" wire:model="documento_garantia" class="form-control" placeholder="Opcional">
                                        </div>
                                    @endif
                                @endif

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Observaciones de Garantía</label>
                                    <textarea wire:model="observaciones_garantia" class="form-control" rows="2" placeholder="Notas adicionales sobre la garantía..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 5: PAGO Y DETALLES --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-money-bill text-success me-2"></i>
                                5. Pago y Detalles Adicionales
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Sucursal <span class="text-danger">*</span></label>
                                    <select wire:model="sucursal_id" class="form-select @error('sucursal_id') is-invalid @enderror">
                                        <option value="">Seleccione...</option>
                                        @foreach($sucursales as $sucursal)
                                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('sucursal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Anticipo (Bs.)</label>
                                    <input type="number" step="0.01" wire:model="anticipo" class="form-control @error('anticipo') is-invalid @enderror" placeholder="0.00">
                                    @error('anticipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Lugar de Entrega</label>
                                    <input type="text" wire:model="lugar_entrega" class="form-control" placeholder="Dirección de entrega">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Lugar de Devolución</label>
                                    <input type="text" wire:model="lugar_devolucion" class="form-control" placeholder="Dirección de devolución">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Observaciones</label>
                                    <textarea wire:model="observaciones" class="form-control" rows="3" placeholder="Notas generales del alquiler..."></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Condiciones Especiales</label>
                                    <textarea wire:model="condiciones_especiales" class="form-control" rows="3" placeholder="Condiciones o acuerdos especiales..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer bg-light border-0 d-flex justify-content-between">
                <div class="text-muted">
                    <small><span class="text-danger">*</span> Campos obligatorios</small>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary px-4" wire:click="closeNewAlquilerModal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" wire:click="saveNewAlquiler" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Guardar Alquiler
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- BACKDROP --}}
<div class="modal-backdrop fade show"></div>

<style>
.modal {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
