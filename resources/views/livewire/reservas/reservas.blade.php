<div>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <button type="button"
                class="btn btn-warning"
                wire:click="openNewReservaModal">
            <i class="fas fa-tshirt me-2"></i>Nueva Reserva de Vestimentas
        </button>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show"
             role="alert">
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">
            {{ session('error') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row row-cols-1 row-cols-md-6 g-4 mb-4 mt-2">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['total'] }}</p>
                    </div>
                    <i class="fas fa-calendar text-primary fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Activas</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['activas'] }}</p>
                    </div>
                    <i class="fas fa-check-circle text-info fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Próximas a Vencer</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['proximasVencer'] }}</p>
                    </div>
                    <i class="fas fa-clock text-warning fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Vencidas</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['vencidas'] }}</p>
                    </div>
                    <i class="fas fa-times-circle text-danger fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Confirmadas</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['confirmadas'] }}</p>
                    </div>
                    <i class="fas fa-check-circle text-success fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Efectivo Total</p>
                        <p class="fs-4 fw-bold mb-0">Bs. {{ number_format($estadisticas['montoTotalEfectivo'], 2) }}</p>
                    </div>
                    <i class="fas fa-dollar-sign text-secondary fa-2x"></i>
                </div>
            </div>
        </div>
    </div>


    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text"
                               class="form-control"
                               placeholder="Buscar por número de reserva o cliente..."
                               wire:model.debounce.300ms="searchTerm">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select"
                            wire:model="filterEstado">
                        <option value="TODOS">Todos los Estados</option>
                        <option value="ACTIVA">Activa</option>
                        <option value="PROXIMA_VENCER">Próxima a Vencer</option>
                        <option value="VENCIDA">Vencida</option>
                        <option value="CONFIRMADA">Confirmada</option>
                        <option value="CANCELADA">Cancelada</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select"
                            wire:model="filterTipo">
                        <option value="TODOS">Todos los Tipos</option>
                        <option value="ALQUILER">Alquiler</option>
                        <option value="VENTA">Venta</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select"
                            wire:model="filterSucursal">
                        <option value="TODAS">Todas las Sucursales</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Reservas ({{ $reservas->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-hover mb-0 table">
                    <thead class="table-light">
                        <tr>
                            <th>Reserva</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Fechas</th>
                            <th>Estado</th>
                            <th>Productos</th>
                            <th>Montos</th>
                            <th>Sucursal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservas as $reserva)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @switch($reserva->estado)
                                            @case('ACTIVA')
                                                <i class="fas fa-check-circle text-info me-2"></i>
                                            @break

                                            @case('PROXIMA_VENCER')
                                                <i class="fas fa-clock text-warning me-2"></i>
                                            @break

                                            @case('VENCIDA')
                                                <i class="fas fa-times-circle text-danger me-2"></i>
                                            @break

                                            @case('CONFIRMADA')
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                            @break

                                            @case('CANCELADA')
                                                <i class="fas fa-times-circle text-secondary me-2"></i>
                                            @break
                                        @endswitch
                                        <code class="small">{{ $reserva->numero_reserva }}</code>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $reserva->cliente->nombres }}
                                            {{ $reserva->cliente->apellidos }}</strong>
                                        <br><small class="text-muted">{{ $reserva->cliente->telefono }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if ($reserva->tipo_reserva === 'ALQUILER')
                                        <span class="badge bg-purple">
                                            <i class="fas fa-tshirt me-1"></i>Alquiler Vestimenta
                                        </span>
                                        @if($reserva->alquiler)
                                            <br><small class="text-success fw-bold">✅ Ya convertido</small>
                                        @else
                                            <br><small class="text-warning fw-bold">⏳ Pendiente conversión</small>
                                        @endif
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-shopping-bag me-1"></i>Venta
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        <div><strong>Reserva:</strong> {{ $reserva->fecha_reserva->format('d/m/Y') }}</div>
                                        @if($reserva->fecha_evento)
                                            <div class="text-primary fw-bold">
                                                <i class="fas fa-calendar-star me-1"></i>Evento: {{ \Carbon\Carbon::parse($reserva->fecha_evento)->format('d/m/Y') }}
                                            </div>
                                        @endif
                                        <div><strong>Vence:</strong> {{ $reserva->fecha_vencimiento->format('d/m/Y') }}</div>
                                        @if (!in_array($reserva->estado, ['CONFIRMADA', 'CANCELADA']))
                                            @php
                                                $diasRestantes = $reserva->fecha_vencimiento->diffInDays(now(), false);
                                            @endphp
                                            <div class="fw-bold">
                                                @if ($diasRestantes == 0)
                                                    Vence hoy
                                                @elseif($diasRestantes < 0)
                                                    {{ abs($diasRestantes) }} días restantes
                                                @else
                                                    {{ $diasRestantes }} días vencida
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @switch($reserva->estado)
                                        @case('ACTIVA')
                                            <span class="badge bg-info">Activa</span>
                                        @break

                                        @case('PROXIMA_VENCER')
                                            <span class="badge bg-warning">Próxima a Vencer</span>
                                        @break

                                        @case('VENCIDA')
                                            <span class="badge bg-danger">Vencida</span>
                                        @break

                                        @case('CONFIRMADA')
                                            <span class="badge bg-success">Confirmada</span>
                                        @break

                                        @case('CANCELADA')
                                            <span class="badge bg-secondary">Cancelada</span>
                                        @break
                                    @endswitch
                                </td>
                                <td class="small">
                                    @foreach ($reserva->detalles as $detalle)
                                        <div>{{ $detalle->producto->nombre }} (x{{ $detalle->cantidad }})</div>
                                    @endforeach
                                </td>
                                <td class="small">
                                    <div><strong>Total: Bs. {{ number_format($reserva->total, 2) }}</strong>
                                    </div>
                                    <div class="text-success">Efectivo: Bs.
                                        {{ number_format($reserva->anticipo, 2) }}</div>
                                    <div class="text-warning">Saldo: Bs.
                                        {{ number_format($reserva->total - $reserva->anticipo, 2) }}
                                    </div>
                                </td>
                                <td class="small">
                                    <div>{{ $reserva->sucursal->nombre }}</div>
                                    <div class="text-muted">{{ $reserva->usuarioCreacion->name }}</div>
                                </td>
                                <td>
                                    <div class="btn-group"
                                         role="group">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                wire:click="viewReserva({{ $reserva->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-primary"
                                                wire:click="printReserva({{ $reserva->id }})">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        @if ($reserva->estado === 'ACTIVA')
                                            @if($reserva->anticipo < $reserva->total)
                                                <button type="button" class="btn btn-sm btn-info"
                                                        wire:click="openPaymentModal({{ $reserva->id }})"
                                                        title="Registrar Pago">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </button>
                                            @endif
                                            <button type="button"
                                                    class="btn btn-sm btn-success"
                                                    wire:click="confirmReserva({{ $reserva->id }})">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            @if ($reserva->tipo_reserva === 'ALQUILER' && !$reserva->alquiler)
                                                <button type="button"
                                                        class="btn btn-sm btn-warning fw-bold"
                                                        wire:click="convertToAlquiler({{ $reserva->id }})"
                                                        title="Crear Contrato de Alquiler de Vestimentas">
                                                    <i class="fas fa-tshirt me-1"></i>Alquilar
                                                </button>
                                            @endif
                                            <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="confirmCancelReserva({{ $reserva->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="9"
                                        class="py-4 text-center">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>No se encontraron reservas</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($reservas->hasPages())
                <div class="card-footer">
                    {{ $reservas->links() }}
                </div>
            @endif
        </div>

        @if ($showNewReservaModal)
            <div class="modal fade show"
                 style="display: block;"
                 tabindex="-1">
                <div class="modal-dialog modal-xl">

                    <div class="modal-content">
                        <div class="modal-header">
                            @if (session()->has('errorModal'))
                                <div class="alert alert-danger alert-dismissible fade show"
                                     role="alert">
                                    {{ session('errorModal') }}
                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            <h5 class="modal-title">
                                <i class="fas fa-tshirt me-2 text-warning"></i>Nueva Reserva de Vestimentas Folklóricas
                            </h5>
                            <button type="button"
                                    class="btn-close"
                                    wire:click="closeNewReservaModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">Detalles de la Reserva</h6>

                                    <div class="mb-3">
                                        <label class="form-label">Cliente *</label>
                                        <select class="form-select select2-cliente @error('cliente_id') is-invalid @enderror"
                                                wire:model="cliente_id">
                                            <option value="">Seleccione un cliente</option>
                                            @foreach ($clientes as $cliente)
                                                <option value="{{ $cliente->id }}">
                                                    {{ $cliente->nombres }} {{ $cliente->apellidos }} (CI:
                                                    {{ $cliente->carnet_identidad }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('cliente_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tipo de Reserva *</label>
                                        <select class="form-select @error('tipo_reserva') is-invalid @enderror"
                                                wire:model="tipo_reserva">
                                            <option value="ALQUILER">
                                                🎭 Alquiler de Vestimentas (Temporal con devolución)
                                            </option>
                                            <option value="VENTA">
                                                🛍️ Venta de Vestimentas (Compra definitiva)
                                            </option>
                                        </select>
                                        @error('tipo_reserva')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <small class="text-info">
                                                <i class="fas fa-info-circle me-1"></i>
                                                <strong>Alquiler:</strong> El cliente devuelve las vestimentas después del evento.
                                                <strong>Venta:</strong> El cliente se queda con las vestimentas permanentemente.
                                            </small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Sucursal *</label>
                                        <select class="form-select @error('sucursal_id') is-invalid @enderror"
                                                wire:model="sucursal_id">
                                            <option value="">Seleccione una sucursal</option>
                                            @foreach ($sucursales as $sucursal)
                                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('sucursal_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Fecha Reserva *</label>
                                                <input type="date"
                                                       class="form-control @error('fecha_reserva') is-invalid @enderror"
                                                       wire:model="fecha_reserva">
                                                @error('fecha_reserva')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Fecha Vencimiento *</label>
                                                <input type="date"
                                                       class="form-control @error('fecha_vencimiento') is-invalid @enderror"
                                                       wire:model="fecha_vencimiento">
                                                @error('fecha_vencimiento')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    <small class="text-muted">Fecha límite para confirmar la reserva</small>
                                                </div>
                                            </div>

                                            @if($tipo_reserva === 'ALQUILER')
                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        <i class="fas fa-calendar-star text-primary me-1"></i>Fecha del Evento Folklórico
                                                    </label>
                                                    <input type="date"
                                                           class="form-control @error('fecha_evento') is-invalid @enderror"
                                                           wire:model="fecha_evento"
                                                           placeholder="¿Cuándo es el evento o presentación?">
                                                    @error('fecha_evento')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">
                                                        <small class="text-info">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Fecha de la presentación, festival o evento donde usarán las vestimentas
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        <i class="fas fa-music text-success me-1"></i>Tipo de Evento (Opcional)
                                                    </label>
                                                    <select class="form-select" wire:model="tipo_evento">
                                                        <option value="">Seleccione el tipo de evento</option>
                                                        <option value="FESTIVAL">🎭 Festival Folklórico</option>
                                                        <option value="DANZA">💃 Presentación de Danza</option>
                                                        <option value="DESFILE">🚶‍♀️ Desfile</option>
                                                        <option value="CONCURSO">🏆 Concurso</option>
                                                        <option value="ESCOLAR">🎓 Evento Escolar</option>
                                                        <option value="UNIVERSITARIO">🎓 Evento Universitario</option>
                                                        <option value="BODA">💒 Boda Folklórica</option>
                                                        <option value="OTRO">🎪 Otro Evento</option>
                                                    </select>
                                                    <div class="form-text">
                                                        <small class="text-muted">Ayuda a planificar mejor la entrega y devolución</small>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Monto en Efectivo (Bs.) *</label>
                                        <input type="number"
                                               step="0.01"
                                               class="form-control @error('anticipo') is-invalid @enderror"
                                               wire:model="anticipo"
                                               placeholder="0.00">
                                        @error('anticipo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    @if($anticipo > 0)
                                        <div class="alert alert-info mb-3">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Registro de Anticipo:</strong> El anticipo de Bs. {{ number_format($anticipo, 2) }} se registrará automáticamente en la caja seleccionada.
                                        </div>

                                        <div class="card border-primary mb-3">
                                            <div class="card-header bg-primary text-white">
                                                <i class="fas fa-cash-register me-2"></i>Información de Caja
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Caja de Destino *</label>
                                                            <select class="form-select @error('caja_id') is-invalid @enderror"
                                                                    wire:model="caja_id">
                                                                <option value="">Seleccione una caja</option>
                                                                @foreach ($cajas as $caja)
                                                                    <option value="{{ $caja->id }}">
                                                                        🏪 {{ $caja->nombre }} - {{ $caja->sucursal->nombre }}
                                                                        💰 Saldo: Bs. {{ number_format($caja->saldo_actual, 2) }}
                                                                        @if($caja->es_caja_principal) ⭐ @endif
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('caja_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                            @if(count($cajas) == 0)
                                                                <div class="text-danger small mt-1">
                                                                    <i class="fas fa-exclamation-triangle"></i> No hay cajas abiertas disponibles
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Método de Pago *</label>
                                                            <select class="form-select @error('metodo_pago') is-invalid @enderror"
                                                                    wire:model="metodo_pago">
                                                                <option value="EFECTIVO">💵 Efectivo</option>
                                                                <option value="QR">📱 QR</option>
                                                                <option value="TARJETA">💳 Tarjeta</option>
                                                                <option value="TRANSFERENCIA">🏦 Transferencia</option>
                                                            </select>
                                                            @error('metodo_pago')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($caja_id)
                                                    @php
                                                        $cajaSeleccionada = $cajas->find($caja_id);
                                                    @endphp
                                                    @if($cajaSeleccionada)
                                                        <div class="alert alert-success mb-0">
                                                            <i class="fas fa-check-circle me-2"></i>
                                                            <strong>Caja Seleccionada:</strong> {{ $cajaSeleccionada->nombre }}<br>
                                                            <small>
                                                                📍 Sucursal: {{ $cajaSeleccionada->sucursal->nombre }} |
                                                                💰 Saldo Actual: Bs. {{ number_format($cajaSeleccionada->saldo_actual, 2) }}
                                                                @if($cajaSeleccionada->es_caja_principal) | ⭐ Caja Principal @endif
                                                            </small>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-sticky-note text-warning me-1"></i>Observaciones y Detalles Especiales
                                        </label>
                                        <textarea class="form-control"
                                                  rows="3"
                                                  wire:model="observaciones"
                                                  placeholder="Ej: Tallas específicas, colores preferidos, modificaciones necesarias, instrucciones especiales para el evento..."></textarea>
                                        <div class="form-text">
                                            <small class="text-muted">
                                                Incluya detalles importantes como tallas, colores, modificaciones o instrucciones especiales para las vestimentas
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Sección de Flete y Transporte -->
                                    <div class="card border-warning mb-3">
                                        <div class="card-header bg-warning text-dark">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="checkbox"
                                                       wire:model="requiere_flete" id="requiere_flete">
                                                <label class="form-check-label fw-bold" for="requiere_flete">
                                                    <i class="fas fa-truck me-2"></i>¿Requiere Flete/Transporte?
                                                </label>
                                            </div>
                                        </div>

                                        @if($requiere_flete)
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Dirección de Entrega *</label>
                                                            <textarea class="form-control @error('direccion_entrega') is-invalid @enderror"
                                                                      rows="2"
                                                                      wire:model="direccion_entrega"
                                                                      placeholder="Dirección completa donde se entregarán las vestimentas"></textarea>
                                                            @error('direccion_entrega')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Fecha de Entrega *</label>
                                                            <input type="date"
                                                                   class="form-control @error('fecha_entrega') is-invalid @enderror"
                                                                   wire:model="fecha_entrega">
                                                            @error('fecha_entrega')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Tipo de Transporte *</label>
                                                            <select class="form-select @error('tipo_transporte') is-invalid @enderror"
                                                                    wire:model="tipo_transporte">
                                                                <option value="INTERNO">🚐 Transporte Interno</option>
                                                                <option value="EXTERNO">🚛 Transporte Externo</option>
                                                                <option value="COURIER">📦 Courier/Delivery</option>
                                                            </select>
                                                            @error('tipo_transporte')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Costo de Flete (Bs.) *</label>
                                                            <input type="number"
                                                                   step="0.01"
                                                                   class="form-control @error('costo_flete') is-invalid @enderror"
                                                                   wire:model="costo_flete"
                                                                   placeholder="0.00">
                                                            @error('costo_flete')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Total con Flete</label>
                                                            <div class="form-control-plaintext bg-light border rounded text-center fw-bold">
                                                                Bs. {{ number_format($this->calculateTotal() + $costo_flete, 2) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-0">
                                                    <label class="form-label">Observaciones del Flete</label>
                                                    <textarea class="form-control"
                                                              rows="2"
                                                              wire:model="observaciones_flete"
                                                              placeholder="Instrucciones especiales para la entrega, horarios, contacto..."></textarea>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-tshirt text-primary me-2"></i>Vestimentas a Reservar
                                    </h6>

                                    <div class="row mb-3" wire:ignore.self>
                                        <div class="col-md-6">
                                            <select class="form-select select2-producto"
                                                    wire:model="currentProductId">
                                                <option value="">Seleccione un producto</option>
                                                @foreach ($productos as $producto)
                                                    <option value="{{ $producto->id }}">
                                                        {{ $producto->nombre }} (Disp: {{ $producto->stock_disponible }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number"
                                                   class="form-control"
                                                   placeholder="Cant."
                                                   wire:model="currentQuantity"
                                                   min="1">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button"
                                                    class="btn btn-primary w-100"
                                                    wire:click="addProductToReserva"
                                                    {{ !$currentProductId || $currentQuantity <= 0 ? 'disabled' : '' }}>
                                                Añadir
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3 rounded border p-3"
                                         style="max-height: 300px; overflow-y: auto;">
                                        @if (empty($selectedProducts))
                                            <p class="text-muted text-center">No hay productos añadidos.</p>
                                        @else
                                            @foreach ($selectedProducts as $index => $producto)
                                                <div
                                                     class="d-flex justify-content-between align-items-center bg-light mb-2 rounded p-2">
                                                    <div>
                                                        <strong>{{ $producto['nombre'] }}</strong>
                                                        <br><small class="text-muted">
                                                            Cantidad: {{ $producto['cantidad'] }} x Bs.
                                                            {{ number_format($producto['precio_unitario'], 2) }}
                                                        </small>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <strong class="me-2">Bs.
                                                            {{ number_format($producto['subtotal'], 2) }}</strong>
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger"
                                                                wire:click="removeProductFromReserva({{ $index }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="border-top pt-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fw-bold">Total Estimado:</span>
                                            <span class="fw-bold fs-5">Bs.
                                                {{ number_format($this->calculateTotal(), 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fw-bold">Monto en Efectivo:</span>
                                            <span class="fw-bold fs-5 text-success">Bs.
                                                {{ number_format($anticipo, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-top pt-2">
                                            <span class="fw-bold">Saldo Pendiente:</span>
                                            <span class="fw-bold fs-5 text-warning">Bs.
                                                {{ number_format($this->calculateTotal() - $anticipo, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-secondary"
                                    wire:click="closeNewReservaModal">Cancelar</button>
                            <button type="button"
                                    class="btn btn-warning"
                                    wire:click="saveNewReserva"
                                    {{ empty($selectedProducts) || !$cliente_id || !$sucursal_id ? 'disabled' : '' }}>
                                Crear Reserva
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        @endif

        @if ($showViewReservaModal && $selectedReserva)
            <div class="modal fade show"
                 style="display: block;"
                 tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detalles de Reserva: {{ $selectedReserva->numero_reserva }}</h5>
                            <button type="button"
                                    class="btn-close"
                                    wire:click="closeViewReservaModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">Información General</h6>
                                    <table class="table-sm table">
                                        <tr>
                                            <td><strong>Cliente:</strong></td>
                                            <td>{{ $selectedReserva->cliente->nombres }}
                                                {{ $selectedReserva->cliente->apellidos }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Teléfono:</strong></td>
                                            <td>{{ $selectedReserva->cliente->telefono }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tipo:</strong></td>
                                            <td>
                                                @if ($selectedReserva->tipo_reserva === 'ALQUILER')
                                                    <span class="badge bg-purple">Alquiler</span>
                                                @else
                                                    <span class="badge bg-success">Venta</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sucursal:</strong></td>
                                            <td>{{ $selectedReserva->sucursal->nombre }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha Reserva:</strong></td>
                                            <td>{{ $selectedReserva->fecha_reserva->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha Vencimiento:</strong></td>
                                            <td>{{ $selectedReserva->fecha_vencimiento->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Creado por:</strong></td>
                                            <td>{{ $selectedReserva->usuarioCreacion->name }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">Información Financiera</h6>
                                    <table class="table-sm table">
                                        <tr>
                                            <td><strong>Total Estimado:</strong></td>
                                            <td><strong>Bs.
                                                    {{ number_format($selectedReserva->total, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Monto en Efectivo:</strong></td>
                                            <td><strong class="text-success">Bs.
                                                    {{ number_format($selectedReserva->anticipo, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Saldo Pendiente:</strong></td>
                                            <td><strong class="text-warning">Bs.
                                                    {{ number_format($selectedReserva->total - $selectedReserva->anticipo, 2) }}</strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <h6 class="fw-bold mb-3">Productos Reservados</h6>
                            @foreach ($selectedReserva->detalles as $detalle)
                                <div class="d-flex justify-content-between align-items-center bg-light mb-2 rounded p-3">
                                    <div>
                                        <strong>{{ $detalle->producto->nombre }}</strong>
                                        <br><small class="text-muted">
                                            Cantidad: {{ $detalle->cantidad }} x Bs.
                                            {{ number_format($detalle->precio_unitario, 2) }}
                                        </small>
                                    </div>
                                    <strong>Bs. {{ number_format($detalle->subtotal, 2) }}</strong>
                                </div>
                            @endforeach

                            @if ($selectedReserva->observaciones)
                                <h6 class="fw-bold mb-2">Observaciones</h6>
                                <div class="bg-light rounded p-3">
                                    {{ $selectedReserva->observaciones }}
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-secondary"
                                    wire:click="closeViewReservaModal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        @endif


        @if ($showConfirmReservaModal && $selectedReserva)
            <div class="modal fade show"
                 style="display: block;"
                 tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirmar Reserva: {{ $selectedReserva->numero_reserva }}</h5>
                            <button type="button"
                                    class="btn-close"
                                    wire:click="closeConfirmReservaModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="bg-light mb-3 rounded p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Estimado:</span>
                                    <strong>Bs. {{ number_format($selectedReserva->total, 2) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Monto ya Pagado:</span>
                                    <strong class="text-success">Bs.
                                        {{ number_format($selectedReserva->anticipo, 2) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-2">
                                    <span>Saldo Pendiente:</span>
                                    <strong class="text-warning">Bs.
                                        {{ number_format($this->getSaldoPendienteProperty(), 2) }}</strong>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pago Adicional (Bs.) *</label>
                                <input type="number"
                                       step="0.01"
                                       class="form-control @error('montoAdicional') is-invalid @enderror"
                                       wire:model.live="montoAdicional"
                                       max="{{ $this->getSaldoPendienteProperty() }}">
                                @error('montoAdicional')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Si ingresa un monto, se registrará automáticamente en la caja seleccionada</small>
                            </div>

                            @if($montoAdicional > 0)
                                <div class="card border-success mb-3">
                                    <div class="card-header bg-success text-white">
                                        <i class="fas fa-cash-register me-2"></i>Registro de Pago Adicional
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-warning mb-3">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <strong>Pago Adicional:</strong> Bs. {{ number_format($montoAdicional, 2) }} se registrará en la caja seleccionada.
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="caja_confirmacion" class="form-label">Caja de Destino *</label>
                                                    <select wire:model="caja_confirmacion"
                                                            class="form-select @error('caja_confirmacion') is-invalid @enderror"
                                                            id="caja_confirmacion">
                                                        <option value="">Seleccione caja</option>
                                                        @foreach($cajas as $caja)
                                                            <option value="{{ $caja->id }}">
                                                                🏪 {{ $caja->nombre }} - {{ $caja->sucursal->nombre }}
                                                                💰 Saldo: Bs. {{ number_format($caja->saldo_actual, 2) }}
                                                                @if($caja->es_caja_principal) ⭐ @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('caja_confirmacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                    @if(count($cajas) == 0)
                                                        <div class="text-danger small mt-1">
                                                            <i class="fas fa-exclamation-triangle"></i> No hay cajas abiertas disponibles
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="metodo_pago_confirmacion" class="form-label">Método de Pago *</label>
                                                    <select wire:model="metodo_pago_confirmacion" class="form-select" id="metodo_pago_confirmacion">
                                                        <option value="EFECTIVO">💵 Efectivo</option>
                                                        <option value="QR">📱 QR</option>
                                                        <option value="TARJETA">💳 Tarjeta</option>
                                                        <option value="TRANSFERENCIA">🏦 Transferencia</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        @if($caja_confirmacion)
                                            @php
                                                $cajaConfirmacionSeleccionada = $cajas->find($caja_confirmacion);
                                            @endphp
                                            @if($cajaConfirmacionSeleccionada)
                                                <div class="alert alert-success mb-0">
                                                    <i class="fas fa-check-circle me-2"></i>
                                                    <strong>Destino Confirmado:</strong> {{ $cajaConfirmacionSeleccionada->nombre }}<br>
                                                    <small>
                                                        📍 Sucursal: {{ $cajaConfirmacionSeleccionada->sucursal->nombre }} |
                                                        💰 Saldo Actual: Bs. {{ number_format($cajaConfirmacionSeleccionada->saldo_actual, 2) }} |
                                                        🔄 Nuevo Saldo: Bs. {{ number_format($cajaConfirmacionSeleccionada->saldo_actual + $montoAdicional, 2) }}
                                                    </small>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="bg-info mb-3 rounded bg-opacity-10 p-3">
                                <div class="d-flex justify-content-between">
                                    <span>Saldo Final:</span>
                                    <strong
                                            class="{{ $this->getSaldoFinalProperty() <= 0 ? 'text-success' : 'text-danger' }}">
                                        Bs. {{ number_format($this->getSaldoFinalProperty(), 2) }}
                                    </strong>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Observaciones</label>
                                <textarea class="form-control"
                                          rows="3"
                                          wire:model="observacionesConfirmacion"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-secondary"
                                    wire:click="closeConfirmReservaModal">Cancelar</button>
                            <button type="button"
                                    class="btn btn-success"
                                    wire:click="saveConfirmReserva">
                                Confirmar Reserva
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        @endif

        {{-- Modal de Conversión a Alquiler --}}
        @if ($showConvertToAlquilerModal && $selectedReserva)
            <div class="modal fade show"
                 style="display: block;"
                 tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="d-flex align-items-center text-white w-100">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                        <i class="fas fa-exchange-alt fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">Convertir Reserva a Alquiler</h5>
                                        <small class="opacity-75">{{ $selectedReserva->numero_reserva }} → Nuevo Contrato de Alquiler</small>
                                    </div>
                                </div>
                                <div class="ms-auto text-end">
                                    <div class="small opacity-75">Cliente</div>
                                    <div class="fw-bold">{{ $selectedReserva->cliente->nombres }} {{ $selectedReserva->cliente->apellidos }}</div>
                                </div>
                            </div>
                            <button type="button"
                                    class="btn-close btn-close-white"
                                    wire:click="closeConvertToAlquilerModal"></button>
                        </div>
                        <div class="modal-body">
                            @if (session()->has('errorConversion'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('errorConversion') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                        Información de la Reserva
                                    </h6>
                                    <div class="card border-primary shadow-sm">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-6">
                                                    <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                                        <div class="fs-4 fw-bold text-success mb-1">
                                                            Bs. {{ number_format($selectedReserva->anticipo, 2) }}
                                                        </div>
                                                        <small class="text-muted">Anticipo Actual</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                                        <div class="fs-4 fw-bold text-primary mb-1">
                                                            Bs. {{ number_format($selectedReserva->total, 2) }}
                                                        </div>
                                                        <small class="text-muted">Total Reserva</small>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="text-center p-3 bg-warning bg-opacity-10 rounded border border-warning border-opacity-25">
                                                        <div class="fs-4 fw-bold text-warning mb-1">
                                                            Bs. {{ number_format($selectedReserva->saldo_pendiente, 2) }}
                                                        </div>
                                                        <small class="text-muted">Saldo Pendiente de la Reserva</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h6 class="fw-bold mb-2 mt-3">Productos Reservados</h6>
                                    <div class="card">
                                        <div class="card-body p-2">
                                            @foreach ($selectedReserva->detalles as $detalle)
                                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                                    <div class="small">
                                                        <strong>{{ $detalle->producto->nombre }}</strong>
                                                        <br>Cantidad: {{ $detalle->cantidad }} x Bs. {{ number_format($detalle->precio_unitario, 2) }}
                                                    </div>
                                                    <strong class="small">Bs. {{ number_format($detalle->subtotal, 2) }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-calendar-alt text-info me-2"></i>
                                        Datos del Alquiler
                                    </h6>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <i class="fas fa-calendar-plus text-success me-1"></i>
                                                    Fecha de Entrega *
                                                </label>
                                                <input type="date"
                                                       class="form-control @error('fechaAlquiler') is-invalid @enderror"
                                                       wire:model="fechaAlquiler"
                                                       min="{{ date('Y-m-d') }}">
                                                @error('fechaAlquiler')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <i class="fas fa-calendar-minus text-danger me-1"></i>
                                                    Fecha de Devolución *
                                                </label>
                                                <input type="date"
                                                       class="form-control @error('fechaDevolucion') is-invalid @enderror"
                                                       wire:model="fechaDevolucion"
                                                       min="{{ date('Y-m-d') }}">
                                                @error('fechaDevolucion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <i class="fas fa-clock text-info me-1"></i>
                                                    Días de Alquiler
                                                </label>
                                                <div class="form-control bg-light text-center fw-bold fs-5">
                                                    @if($fechaAlquiler && $fechaDevolucion)
                                                        @php
                                                            $fechaInicio = \Carbon\Carbon::parse($fechaAlquiler);
                                                            $fechaFin = \Carbon\Carbon::parse($fechaDevolucion);
                                                            $diasCalculados = $fechaInicio->diffInDays($fechaFin) + 1;
                                                        @endphp
                                                        {{ $diasCalculados }} día{{ $diasCalculados != 1 ? 's' : '' }}
                                                    @else
                                                        -- días
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Anticipo Adicional (Bs.)</label>
                                                <input type="number"
                                                       step="0.01"
                                                       min="0"
                                                       class="form-control @error('anticipoAdicional') is-invalid @enderror"
                                                       wire:model="anticipoAdicional"
                                                       placeholder="0.00">
                                                @error('anticipoAdicional')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    Monto adicional al anticipo de la reserva
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            @if($requiereDeposito)
                                                <div class="mb-3">
                                                    <label class="form-label">Depósito de Garantía (Bs.)</label>
                                                    <input type="number"
                                                           step="0.01"
                                                           min="0"
                                                           class="form-control @error('depositoGarantia') is-invalid @enderror"
                                                           wire:model="depositoGarantia"
                                                           placeholder="0.00">
                                                    @error('depositoGarantia')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="requiereDeposito"
                                                   wire:model="requiereDeposito">
                                            <label class="form-check-label" for="requiereDeposito">
                                                <i class="fas fa-shield-alt text-secondary me-1"></i>
                                                Requiere Depósito de Garantía
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Observaciones del Alquiler</label>
                                        <textarea class="form-control"
                                                  rows="3"
                                                  wire:model="observacionesAlquiler"
                                                  placeholder="Observaciones adicionales para el alquiler..."></textarea>
                                    </div>

                                    {{-- Garantía --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-shield-alt text-warning me-2"></i>
                                            Garantía (Opcional)
                                        </label>
                                        <select class="form-select" wire:model="garantia_id">
                                            <option value="">🛡️ Sin garantía</option>
                                            @foreach ($garantiasDisponibles as $garantia)
                                                <option value="{{ $garantia->id }}">
                                                    {{ $garantia->numero_ticket }} - {{ $garantia->tipoGarantia->nombre }}
                                                    @if($garantia->monto > 0)
                                                        - Bs. {{ number_format($garantia->monto, 2) }}
                                                    @endif
                                                    ({{ $garantia->descripcion }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-info">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Solo se muestran garantías activas del cliente
                                        </small>

                                        {{-- Mostrar información de la garantía seleccionada --}}
                                        @if($garantia_id)
                                            @php
                                                $selectedGarantia = $garantiasDisponibles->find($garantia_id);
                                            @endphp
                                            @if($selectedGarantia)
                                                <div class="alert alert-info mt-2 mb-0">
                                                    <div class="row small">
                                                        <div class="col-md-6">
                                                            <strong>Tipo:</strong> {{ $selectedGarantia->tipoGarantia->nombre }}<br>
                                                            <strong>Ticket:</strong> {{ $selectedGarantia->numero_ticket }}
                                                        </div>
                                                        <div class="col-md-6">
                                                            @if($selectedGarantia->monto > 0)
                                                                <strong>Monto:</strong> Bs. {{ number_format($selectedGarantia->monto, 2) }}<br>
                                                            @endif
                                                            <strong>Vence:</strong> {{ $selectedGarantia->fecha_vencimiento ? \Carbon\Carbon::parse($selectedGarantia->fecha_vencimiento)->format('d/m/Y') : 'Sin vencimiento' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                    {{-- Resumen Financiero --}}
                                    <div class="card bg-light">
                                        <div class="card-header py-2">
                                            <h6 class="mb-0">
                                                <i class="fas fa-calculator text-success me-2"></i>
                                                Resumen Financiero
                                            </h6>
                                        </div>
                                        <div class="card-body py-2">
                                            <div class="row small">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span>Total del Alquiler:</span>
                                                        <strong>Bs. {{ number_format($selectedReserva->total, 2) }}</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-1 text-success">
                                                        <span>Anticipo de Reserva:</span>
                                                        <strong>Bs. {{ number_format($selectedReserva->anticipo, 2) }}</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-1 text-info">
                                                        <span>Anticipo Adicional:</span>
                                                        <strong>Bs. {{ number_format($anticipoAdicional ?? 0, 2) }}</strong>
                                                    </div>
                                                    @if($requiereDeposito && $depositoGarantia)
                                                        <div class="d-flex justify-content-between mb-1 text-secondary">
                                                            <span>Depósito de Garantía:</span>
                                                            <strong>Bs. {{ number_format($depositoGarantia, 2) }}</strong>
                                                        </div>
                                                    @endif
                                                    <hr class="my-1">
                                                    @php
                                                        $totalAnticipo = $selectedReserva->anticipo + ($anticipoAdicional ?? 0);
                                                        $saldoPendiente = $selectedReserva->total - $totalAnticipo;
                                                    @endphp
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span>Total Pagado:</span>
                                                        <strong class="text-success">Bs. {{ number_format($totalAnticipo, 2) }}</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span>Saldo Pendiente:</span>
                                                        <strong class="{{ $saldoPendiente <= 0 ? 'text-success' : 'text-warning' }}">
                                                            Bs. {{ number_format($saldoPendiente, 2) }}
                                                        </strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-top-0 pt-4">
                            <div class="d-flex justify-content-between w-100">
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="fas fa-info-circle me-2"></i>
                                    La reserva se confirmará automáticamente al crear el alquiler
                                </div>
                                <div>
                                    <button type="button"
                                            class="btn btn-light me-2"
                                            wire:click="closeConvertToAlquilerModal">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </button>
                                    <button type="button"
                                            class="btn btn-primary btn-lg px-4"
                                            wire:click="saveConvertToAlquiler"
                                            wire:loading.attr="disabled">
                                        <span wire:loading.remove>
                                            <i class="fas fa-handshake me-2"></i>Crear Contrato de Alquiler
                                        </span>
                                        <span wire:loading>
                                            <i class="fas fa-spinner fa-spin me-2"></i>Creando Contrato...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        @endif

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Función para confirmar cancelación de reserva
            function confirmCancelReserva(reservaId) {
                Swal.fire({
                    title: '¿Cancelar Reserva?',
                    text: 'Esta acción no se puede deshacer. La reserva se cancelará y el stock se liberará.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, cancelar',
                    cancelButtonText: 'No, mantener',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('cancelReserva', reservaId);
                    }
                });
            }

            // Escuchar eventos de SweetAlert desde Livewire
            window.addEventListener('swal', event => {
                Swal.fire({
                    title: event.detail.title,
                    text: event.detail.text,
                    icon: event.detail.icon,
                    confirmButtonText: 'OK'
                });
            });

            document.addEventListener('livewire:load', function() {
                console.log('hola');
                Livewire.on('printReservaEvent', reservaJson => {
                    console.log('hola');

                    const reserva = JSON.parse(reservaJson);

                    // Generar contenido HTML del ticket dinámicamente (puedes usar template literals)
                    const content = `
<!DOCTYPE html>
<html>
<head>
  <title>Ticket Reserva ${reserva.numero_reserva}</title>
  <style>
    @media print {
      @page {
        size: 80mm auto;
        margin: 0;
      }
      body {
        margin: 0;
        padding: 0;
      }
    }
    body {
      font-family: 'Courier New', monospace;
      font-size: 12px;
      line-height: 1.2;
      margin: 0;
      padding: 8px;
      width: 72mm;
      background: white;
    }
    .header {
      text-align: center;
      border-bottom: 1px dashed #000;
      padding-bottom: 8px;
      margin-bottom: 8px;
    }
    .title {
      font-weight: bold;
      font-size: 14px;
      margin-bottom: 4px;
    }
    .subtitle {
      font-size: 10px;
      margin-bottom: 2px;
    }
    .section {
      margin-bottom: 8px;
      border-bottom: 1px dashed #000;
      padding-bottom: 6px;
    }
    .row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 2px;
    }
    .label {
      font-weight: bold;
    }
    .center {
      text-align: center;
    }
    .right {
      text-align: right;
    }
    .productos {
      margin-bottom: 8px;
    }
    .producto-item {
      margin-bottom: 3px;
      font-size: 11px;
    }
    .total-section {
      border-top: 2px solid #000;
      padding-top: 6px;
      margin-top: 8px;
    }
    .footer {
      text-align: center;
      margin-top: 12px;
      font-size: 10px;
      border-top: 1px dashed #000;
      padding-top: 8px;
    }
    .estado-badge {
      display: inline-block;
      padding: 2px 6px;
      border: 1px solid #000;
      font-size: 10px;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="title">FOLKLORE BOLIVIA</div>
    <div class="subtitle">Sistema de Reservas</div>
    <div class="subtitle">Trajes Típicos y Accesorios</div>
  </div>

  <div class="section">
    <div class="row">
      <span class="label">RESERVA:</span>
      <span>${reserva.numero_reserva}</span>
    </div>
    <div class="row">
      <span class="label">FECHA:</span>
      <span>${new Date().toLocaleDateString("es-BO")} ${new Date().toLocaleTimeString("es-BO", { hour: "2-digit", minute: "2-digit" })}</span>
    </div>
    <div class="row">
      <span class="label">SUCURSAL:</span>
      <span>${reserva.sucursal_nombre}</span>
    </div>
    <div class="row">
      <span class="label">VENDEDOR:</span>
      <span>${reserva.usuario_creacion_nombre}</span>
    </div>
  </div>

  <div class="section">
    <div class="center label">DATOS DEL CLIENTE</div>
    <div class="row">
      <span class="label">NOMBRE:</span>
      <span>${reserva.cliente_nombre}</span>
    </div>
    <div class="row">
      <span class="label">TELÉFONO:</span>
      <span>${reserva.cliente_telefono}</span>
    </div>
  </div>

  <div class="section">
    <div class="row">
      <span class="label">TIPO:</span>
      <span class="estado-badge">${reserva.tipo_reserva}</span>
    </div>
    <div class="row">
      <span class="label">ESTADO:</span>
      <span class="estado-badge">${reserva.estado}</span>
    </div>
    <div class="row">
      <span class="label">F. RESERVA:</span>
      <span>${reserva.fecha_reserva}</span>
    </div>
    <div class="row">
      <span class="label">F. VENCE:</span>
      <span>${reserva.fecha_vencimiento}</span>
    </div>
  </div>

  <div class="productos">
    <div class="center label">PRODUCTOS RESERVADOS</div>
    ${reserva.productos.map(producto => `
                                                              <div class="producto-item">
                                                                <div>${producto.nombre}</div>
                                                               <div class="row">
                              <span>${producto.cantidad} x Bs. ${(Number(producto.precio_unitario) || 0).toFixed(2)}</span>
                              <span>Bs. ${((Number(producto.precio_unitario) || 0) * (Number(producto.cantidad) || 0)).toFixed(2)}</span>
                            </div>
                                                              </div>
                                                            `).join('')}
  </div>

  <div class="total-section">
 <div class="row">
  <span class="label">SUBTOTAL:</span>
  <span>Bs. ${(Number(reserva.total_estimado) || 0).toFixed(2)}</span>
</div>
<div class="row">
  <span class="label">EFECTIVO:</span>
  <span>Bs. ${(Number(reserva.monto_efectivo) || 0).toFixed(2)}</span>
</div>
<div class="row">
  <span class="label">SALDO:</span>
  <span>Bs. ${((Number(reserva.total_estimado) || 0) - (Number(reserva.monto_efectivo) || 0)).toFixed(2)}</span>
</div>
  </div>

  ${reserva.observaciones ? `
                                                            <div class="section">
                                                              <div class="label">OBSERVACIONES:</div>
                                                              <div>${reserva.observaciones}</div>
                                                            </div>
                                                          ` : ''}

  <div class="footer">
    <div>¡Gracias por su preferencia!</div>
    <div>Conserve este ticket como comprobante</div>
    <div>de su reserva</div>
    <div style="margin-top: 8px;">
      <div>Tel: 2-2345678 | Cel: 70123456</div>
      <div>www.folkloreBolivia.com</div>
    </div>
  </div>
</body>
</html>`;

                    let printWindow = window.open('', '', 'width=300,height=600');
                    printWindow.document.write(content);
                    printWindow.document.close();
                    printWindow.focus();

                    // Aquí lanzamos la impresión automática
                    printWindow.print();

                    // Opcionalmente cerramos la ventana luego de imprimir
                    printWindow.close();
                });
            });
        </script>

        <!-- Modal de Pago para Reservas -->
        @if($showPaymentModal && $selectedReserva)
            <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Registrar Pago - {{ $selectedReserva->numero_reserva }}</h5>
                            <button type="button" class="btn-close" wire:click="closePaymentModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-primary">
                                <i class="fas fa-user me-2"></i>
                                <strong>Cliente:</strong> {{ $selectedReserva->cliente->nombres }}<br>
                                <strong>Reserva:</strong> {{ $selectedReserva->numero_reserva }}<br>
                                <strong>Total:</strong> Bs. {{ number_format($selectedReserva->total, 2) }}<br>
                                <strong>Anticipo Actual:</strong> Bs. {{ number_format($selectedReserva->anticipo, 2) }}<br>
                                <strong>Saldo Pendiente:</strong> <span class="badge bg-danger fs-6">Bs. {{ number_format($selectedReserva->total - $selectedReserva->anticipo, 2) }}</span>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Monto a Pagar *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Bs.</span>
                                            <input type="number" step="0.01" wire:model.live="monto_pago_reserva"
                                                   class="form-control @error('monto_pago_reserva') is-invalid @enderror"
                                                   max="{{ $selectedReserva->total - $selectedReserva->anticipo }}"
                                                   placeholder="0.00">
                                        </div>
                                        @error('monto_pago_reserva') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <small class="text-muted">Máximo: Bs. {{ number_format($selectedReserva->total - $selectedReserva->anticipo, 2) }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Método de Pago *</label>
                                        <select wire:model="metodo_pago_reserva" class="form-select">
                                            <option value="EFECTIVO">💵 Efectivo</option>
                                            <option value="QR">📱 QR</option>
                                            <option value="TARJETA">💳 Tarjeta</option>
                                            <option value="TRANSFERENCIA">🏦 Transferencia</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            @if($monto_pago_reserva > 0)
                                <div class="card border-success mb-3">
                                    <div class="card-header bg-success text-white">
                                        <i class="fas fa-cash-register me-2"></i>Destino del Pago
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Caja de Destino *</label>
                                            <select wire:model="caja_pago_reserva" class="form-select select2-caja @error('caja_pago_reserva') is-invalid @enderror">
                                                <option value="">Seleccione caja</option>
                                                @foreach($cajas as $caja)
                                                    <option value="{{ $caja->id }}">
                                                        🏪 {{ $caja->nombre }} - {{ $caja->sucursal->nombre }}
                                                        💰 Saldo: Bs. {{ number_format($caja->saldo_actual, 2) }}
                                                        @if($caja->es_caja_principal) ⭐ @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('caja_pago_reserva') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            @if(count($cajas) == 0)
                                                <div class="text-danger small mt-1">
                                                    <i class="fas fa-exclamation-triangle"></i> No hay cajas abiertas disponibles
                                                </div>
                                            @endif
                                        </div>

                                        @if($caja_pago_reserva)
                                            @php
                                                $cajaSeleccionadaPagoReserva = $cajas->find($caja_pago_reserva);
                                            @endphp
                                            @if($cajaSeleccionadaPagoReserva)
                                                <div class="alert alert-info mb-3">
                                                    <i class="fas fa-calculator me-2"></i>
                                                    <strong>Resumen del Pago:</strong><br>
                                                    <small>
                                                        💰 Saldo Actual Caja: Bs. {{ number_format($cajaSeleccionadaPagoReserva->saldo_actual, 2) }}<br>
                                                        ➕ Pago a Registrar: Bs. {{ number_format($monto_pago_reserva, 2) }}<br>
                                                        🔄 <strong>Nuevo Saldo Caja: Bs. {{ number_format($cajaSeleccionadaPagoReserva->saldo_actual + $monto_pago_reserva, 2) }}</strong><br>
                                                        📉 Saldo Pendiente Reserva: Bs. {{ number_format(($selectedReserva->total - $selectedReserva->anticipo) - $monto_pago_reserva, 2) }}
                                                    </small>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Referencia</label>
                                        <input type="text" wire:model="referencia_pago_reserva" class="form-control" placeholder="Número de transacción, etc.">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Observaciones</label>
                                        <textarea wire:model="observaciones_pago_reserva" class="form-control" rows="2" placeholder="Observaciones del pago"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closePaymentModal">Cancelar</button>
                            <button type="button" class="btn btn-success" wire:click="procesarPagoReserva">
                                <i class="fas fa-dollar-sign me-1"></i>Registrar Pago
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    <style>
        .bg-purple {
            background-color: #6f42c1 !important;
        }
    </style>
