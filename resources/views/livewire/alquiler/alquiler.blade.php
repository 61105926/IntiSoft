<div>

    <!-- Botón Nuevo Alquiler -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button type="button" class="btn btn-warning" wire:click="openNewAlquilerModal">
            <i class="fas fa-plus me-2"></i>Nuevo Alquiler
        </button>
    </div>

    <!-- Mensajes de éxito/error -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas -->
    <div class="row row-cols-1 row-cols-md-5 g-4 mb-4 mt-2">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['total'] }}</p>
                    </div>
                    <i class="fas fa-list fa-2x text-primary"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Activos</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['activos'] }}</p>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-info"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Vencidos</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['vencidos'] }}</p>
                    </div>
                    <i class="fas fa-times-circle fa-2x text-danger"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Vence Hoy</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['venceHoy'] }}</p>
                    </div>
                    <i class="fas fa-clock fa-2x text-warning"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Devueltos</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['devueltos'] }}</p>
                    </div>
                    <i class="fas fa-undo fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar por contrato, cliente o unidad..." wire:model.debounce.300ms="searchTerm">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model="filterEstado">
                        <option value="TODOS">TODOS</option>
                        <option value="ACTIVO">ACTIVO</option>
                        <option value="VENCIDO">VENCIDO</option>
                        <option value="VENCE_HOY">VENCE HOY</option>
                        <option value="PROXIMO">PRÓXIMO</option>
                        <option value="DEVUELTO">DEVUELTO</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model="filterSucursal">
                        <option value="TODAS">Todas las Sucursales</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Alquileres -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Alquileres ({{ $alquileres->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-hover mb-0 table">
                    <thead class="table-light">
                        <tr>
                            <th>Contrato</th>
                            <th>Origen</th>
                            <th>Unidad Educativa</th>
                            <th>Cliente</th>
                            <th>Fechas</th>
                            <th>Estado</th>
                            <th>Productos</th>
                            <th>Total</th>
                            <th>Garantía</th>
                            <th>Vendedor</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alquileres as $alquiler)
                            <tr>
                                <td>{{ $alquiler->numero_contrato }}</td>
                                <td>
                                    @if($alquiler->reserva_id)
                                        <span class="badge bg-info">Reserva</span>
                                        <br><small>{{ $alquiler->reserva->numero_reserva ?? '' }}</small>
                                    @else
                                        <span class="badge bg-secondary">Directo</span>
                                    @endif
                                </td>
                                <td>{{ $alquiler->unidadEducativa->nombre ?? 'N/A' }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $alquiler->cliente->nombres }} {{ $alquiler->cliente->apellidos }}</strong>
                                        <br><small class="text-muted">{{ $alquiler->cliente->telefono }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div>Inicio: {{ $alquiler->fecha_alquiler->format('d/m/Y') }}</div>
                                        <div>Fin: {{ $alquiler->fecha_devolucion_programada->format('d/m/Y') }}</div>
                                        @if($alquiler->fecha_devolucion_real)
                                            <div class="text-success">Real: {{ $alquiler->fecha_devolucion_real->format('d/m/Y') }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @switch($alquiler->estado)
                                        @case('ACTIVO')
                                            @php
                                                $hoy = \Carbon\Carbon::today();
                                                $fechaVencimiento = $alquiler->fecha_devolucion_programada;
                                            @endphp
                                            @if($fechaVencimiento->isToday())
                                                <span class="badge bg-warning">Vence Hoy</span>
                                            @elseif($fechaVencimiento->isPast())
                                                <span class="badge bg-danger">Vencido</span>
                                            @else
                                                <span class="badge bg-info">Activo</span>
                                            @endif
                                        @break
                                        @case('VENCIDO')
                                            <span class="badge bg-danger">Vencido</span>
                                        @break
                                        @case('DEVUELTO')
                                            <span class="badge bg-success">Devuelto</span>
                                        @break
                                        @case('CANCELADO')
                                            <span class="badge bg-secondary">Cancelado</span>
                                        @break
                                        @case('PARCIAL')
                                            <span class="badge bg-warning">Parcial</span>
                                        @break
                                    @endswitch
                                </td>
                                <td class="small">
                                    <!-- Por ahora mostramos placeholder hasta implementar productos -->
                                    <span class="text-muted">{{ $alquiler->dias_alquiler }} días</span>
                                </td>
                                <td>
                                    <div class="small">
                                        <div><strong>Bs. {{ number_format($alquiler->total, 2) }}</strong></div>
                                        @if($alquiler->anticipo_reserva && $alquiler->anticipo_reserva > 0)
                                            <div class="text-info">Reserva: Bs. {{ number_format($alquiler->anticipo_reserva, 2) }}</div>
                                            @if($alquiler->anticipo > $alquiler->anticipo_reserva)
                                                <div class="text-success">Adicional: Bs. {{ number_format($alquiler->anticipo - $alquiler->anticipo_reserva, 2) }}</div>
                                            @endif
                                            <div class="text-success border-top">Total Pagado: Bs. {{ number_format($alquiler->anticipo, 2) }}</div>
                                        @else
                                            <div class="text-success">Anticipo: Bs. {{ number_format($alquiler->anticipo, 2) }}</div>
                                        @endif
                                        <div class="text-warning">Saldo: Bs. {{ number_format($alquiler->saldo_pendiente, 2) }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if($alquiler->tieneGarantia())
                                        <div class="small">
                                            <span class="badge bg-success mb-1">{{ $alquiler->garantia->tipoGarantia->nombre }}</span>
                                            <div class="text-primary fw-bold">{{ $alquiler->garantia->numero_ticket }}</div>
                                            @if($alquiler->garantia->monto > 0)
                                                <div class="text-success">Bs. {{ number_format($alquiler->garantia->monto, 2) }}</div>
                                            @endif
                                            <div class="text-muted">{{ $alquiler->garantia->estado_display }}</div>
                                        </div>
                                    @else
                                        <span class="text-muted small">Sin garantía</span>
                                    @endif
                                </td>
                                <td>{{ $alquiler->usuarioCreacion->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" wire:click="viewAlquiler({{ $alquiler->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" wire:click="printAlquiler({{ $alquiler->id }})">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        @if($alquiler->estado === 'ACTIVO')
                                            @if($alquiler->saldo_pendiente > 0)
                                                <button type="button" class="btn btn-sm btn-info" wire:click="openPaymentModal({{ $alquiler->id }})" title="Registrar Pago">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-success" wire:click="openDevolucionModal({{ $alquiler->id }})">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            @if($alquiler->tieneGarantia())
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="fas fa-shield-alt"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="#" wire:click="openGarantiaModal({{ $alquiler->id }})">
                                                                <i class="fas fa-minus-circle me-2"></i>Aplicar Penalización
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" wire:click="devolverGarantiaCompleta({{ $alquiler->id }})">
                                                                <i class="fas fa-undo me-2"></i>Devolver Garantía
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" wire:click="liberarGarantia({{ $alquiler->id }})">
                                                                <i class="fas fa-unlink me-2"></i>Liberar Garantía
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="py-4 text-center">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>No se encontraron alquileres</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        @if ($alquileres->hasPages())
            <div class="card-footer">
                {{ $alquileres->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Nuevo Alquiler -->
    @if ($showNewAlquilerModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-handshake me-2"></i>Nuevo Contrato de Alquiler
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeNewAlquilerModal"></button>
                    </div>

                    <div class="modal-body p-0">
                        @if (session()->has('errorModal'))
                            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('errorModal') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row g-0">
                            <!-- Panel Izquierdo - Información del Cliente y Alquiler -->
                            <div class="col-lg-8">
                                <div class="p-4 border-end">
                                    <!-- Sección Cliente -->
                                    <div class="card border-0 bg-light mb-4">
                                        <div class="card-header bg-secondary text-white py-2">
                                            <h6 class="mb-0"><i class="fas fa-user me-2"></i>Información del Cliente</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-8">
                                                    <label class="form-label fw-bold">Cliente *</label>
                                                    <select class="form-select form-select-sm select2-cliente @error('cliente_id') is-invalid @enderror" wire:model="cliente_id">
                                                        <option value="">🔍 Seleccione un cliente</option>
                                                        @foreach ($clientes as $cliente)
                                                            <option value="{{ $cliente->id }}">
                                                                {{ $cliente->nombres }} {{ $cliente->apellidos }} - CI: {{ $cliente->carnet_identidad }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('cliente_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">Unidad Educativa</label>
                                                    <select class="form-select form-select-sm" wire:model="unidad_educativa_id">
                                                        <option value="">🏫 Opcional</option>
                                                        @foreach ($unidadesEducativas as $unidad)
                                                            <option value="{{ $unidad->id }}">{{ $unidad->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sección Fechas y Duración -->
                                    <div class="card border-0 bg-light mb-4">
                                        <div class="card-header bg-secondary text-white py-2">
                                            <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Período del Alquiler</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">📅 Fecha Inicio *</label>
                                                    <input type="date" class="form-control form-control-sm @error('fecha_alquiler') is-invalid @enderror" wire:model="fecha_alquiler">
                                                    @error('fecha_alquiler')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">⏱️ Días de Alquiler *</label>
                                                    <input type="number" class="form-control form-control-sm @error('dias_alquiler') is-invalid @enderror" wire:model="dias_alquiler" min="1" placeholder="Ej: 3">
                                                    @error('dias_alquiler')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">📅 Fecha Devolución *</label>
                                                    <input type="date" class="form-control form-control-sm @error('fecha_devolucion_programada') is-invalid @enderror" wire:model="fecha_devolucion_programada">
                                                    @error('fecha_devolucion_programada')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fw-bold">🏪 Sucursal *</label>
                                                    <select class="form-select form-select-sm @error('sucursal_id') is-invalid @enderror" wire:model="sucursal_id">
                                                        <option value="">Seleccionar</option>
                                                        @foreach ($sucursales as $sucursal)
                                                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('sucursal_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">🔗 Conversión desde Reserva</label>
                                                    <select class="form-select form-select-sm" wire:model="reserva_id">
                                                        <option value="">Nuevo alquiler (sin reserva previa)</option>
                                                        @foreach ($reservas as $reserva)
                                                            <option value="{{ $reserva->id }}">
                                                                {{ $reserva->numero_reserva }} - {{ $reserva->cliente->nombres }} {{ $reserva->cliente->apellidos }}
                                                                @if($reserva->anticipo > 0)
                                                                    (Anticipo: Bs. {{ number_format($reserva->anticipo, 2) }})
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="form-text">
                                                        <small class="text-info">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Al seleccionar una reserva se precargará la información del cliente y productos
                                                        </small>
                                                    </div>

                                                    @if($reserva_id)
                                                        @php
                                                            $selectedReserva = $reservas->find($reserva_id);
                                                        @endphp
                                                        @if($selectedReserva)
                                                            <div class="alert alert-success mt-2 mb-0">
                                                                <h6 class="alert-heading mb-2">
                                                                    <i class="fas fa-arrow-right me-2"></i>Convirtiendo Reserva a Alquiler
                                                                </h6>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <small>
                                                                            <strong>Reserva:</strong> {{ $selectedReserva->numero_reserva }}<br>
                                                                            <strong>Cliente:</strong> {{ $selectedReserva->cliente->nombres }} {{ $selectedReserva->cliente->apellidos }}<br>
                                                                            <strong>Fecha Evento:</strong> {{ $selectedReserva->fecha_evento ? \Carbon\Carbon::parse($selectedReserva->fecha_evento)->format('d/m/Y') : 'No definida' }}
                                                                        </small>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <small>
                                                                            @if($selectedReserva->anticipo > 0)
                                                                                <strong>Anticipo Reserva:</strong> Bs. {{ number_format($selectedReserva->anticipo, 2) }}<br>
                                                                            @endif
                                                                            <strong>Total Reserva:</strong> Bs. {{ number_format($selectedReserva->total, 2) }}<br>
                                                                            <strong>Estado:</strong> <span class="badge bg-{{ $selectedReserva->estado === 'CONFIRMADA' ? 'success' : 'warning' }}">{{ $selectedReserva->estado }}</span>
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sección Garantía -->
                                    <div class="card border-0 bg-light mb-4">
                                        <div class="card-header bg-danger text-white py-2">
                                            <h6 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Garantía (Obligatoria)</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">🛡️ Seleccionar Garantía *</label>
                                                    <select class="form-select form-select-sm @error('garantia_id') is-invalid @enderror" wire:model="garantia_id">
                                                        <option value="">Seleccione una garantía obligatoria</option>
                                                        @if($cliente_id)
                                                            @foreach ($garantiasDisponibles->where('cliente_id', $cliente_id) as $garantia)
                                                                <option value="{{ $garantia->id }}">
                                                                    {{ $garantia->numero_ticket }} - {{ $garantia->tipoGarantia->nombre }}
                                                                    @if($garantia->monto > 0)
                                                                        - Bs. {{ number_format($garantia->monto, 2) }}
                                                                    @endif
                                                                    ({{ $garantia->descripcion }})
                                                                </option>
                                                            @endforeach
                                                        @else
                                                            <option value="" disabled>Primero seleccione un cliente</option>
                                                        @endif
                                                    </select>
                                                    @error('garantia_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">
                                                        <small class="text-danger fw-bold">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            OBLIGATORIO: Todo alquiler debe contar con una garantía activa
                                                        </small>
                                                        @if(!$cliente_id)
                                                            <br><small class="text-muted">Primero seleccione un cliente para ver sus garantías</small>
                                                        @elseif($garantiasDisponibles->where('cliente_id', $cliente_id)->isEmpty())
                                                            <br><small class="text-warning">⚠️ Este cliente no tiene garantías activas. Debe crear una antes de continuar.</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Mostrar información de la garantía seleccionada -->
                                            @if($garantia_id)
                                                @php
                                                    $selectedGarantia = $garantiasDisponibles->find($garantia_id);
                                                @endphp
                                                @if($selectedGarantia)
                                                    <div class="alert alert-info mt-3 mb-0">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>Tipo:</strong> {{ $selectedGarantia->tipoGarantia->nombre }}<br>
                                                                <strong>Ticket:</strong> {{ $selectedGarantia->numero_ticket }}<br>
                                                                <strong>Cliente:</strong> {{ $selectedGarantia->cliente->nombres }} {{ $selectedGarantia->cliente->apellidos }}
                                                            </div>
                                                            <div class="col-md-6">
                                                                @if($selectedGarantia->monto > 0)
                                                                    <strong>Monto:</strong> Bs. {{ number_format($selectedGarantia->monto, 2) }}<br>
                                                                    <strong>Disponible:</strong> Bs. {{ number_format($selectedGarantia->monto_disponible, 2) }}<br>
                                                                @endif
                                                                <strong>Vence:</strong> {{ $selectedGarantia->fecha_vencimiento ? \Carbon\Carbon::parse($selectedGarantia->fecha_vencimiento)->format('d/m/Y') : 'Sin vencimiento' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Sección Observaciones -->
                                    <div class="card border-0 bg-light">
                                        <div class="card-header bg-secondary text-white py-2">
                                            <h6 class="mb-0"><i class="fas fa-clipboard me-2"></i>Notas Adicionales</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">📝 Observaciones</label>
                                                    <textarea class="form-control form-control-sm" rows="3" wire:model="observaciones" placeholder="Notas generales..."></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">⚠️ Condiciones Especiales</label>
                                                    <textarea class="form-control form-control-sm" rows="3" wire:model="condiciones_especiales" placeholder="Términos específicos del alquiler..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel Derecho - Productos y Totales -->
                            <div class="col-lg-4">
                                <div class="p-4 bg-light h-100">
                                    <!-- Agregar Productos -->
                                    <div class="card border-secondary mb-4">
                                        <div class="card-header bg-secondary text-white py-2">
                                            <h6 class="mb-0"><i class="fas fa-box me-2"></i>Agregar Productos</h6>
                                        </div>
                                        <div class="card-body p-3" wire:ignore.self>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Producto</label>
                                                <select class="form-select form-select-sm select2-producto" wire:model="currentProductId">
                                                    <option value="">🛍️ Seleccionar producto</option>
                                                    @foreach ($productos as $producto)
                                                        <option value="{{ $producto->id }}">
                                                            {{ $producto->nombre }} 
                                                            <span class="text-muted">(Stock: {{ $producto->stock_disponible ?? 0 }})</span>
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <input type="number" class="form-control form-control-sm" wire:model="currentQuantity" min="1" placeholder="Cant.">
                                                <button type="button" class="btn btn-primary btn-sm px-3" wire:click="addProductToAlquiler" 
                                                        {{ !$currentProductId || $currentQuantity <= 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-plus"></i> Añadir
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Lista de Productos -->
                                    <div class="card border-secondary mb-4">
                                        <div class="card-header bg-secondary text-white py-2">
                                            <h6 class="mb-0"><i class="fas fa-list me-2"></i>Productos Seleccionados</h6>
                                        </div>
                                        <div class="card-body p-2" style="max-height: 250px; overflow-y:auto;">
                                            @if (empty($selectedProducts))
                                                <div class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                                    <p class="small mb-0">Sin productos</p>
                                                </div>
                                            @else
                                                @foreach ($selectedProducts as $index => $producto)
                                                    <div class="card card-sm mb-2 border-light">
                                                        <div class="card-body p-2">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <div class="flex-grow-1">
                                                                    <h6 class="card-title mb-1 small">{{ $producto['nombre'] }}</h6>
                                                                    <p class="card-text small text-muted mb-0">
                                                                        {{ $producto['cantidad'] }}x × Bs. {{ number_format($producto['precio_unitario'], 2) }}
                                                                    </p>
                                                                </div>
                                                                <div class="text-end">
                                                                    <div class="fw-bold text-success">Bs. {{ number_format($producto['subtotal'], 2) }}</div>
                                                                    <button type="button" class="btn btn-outline-danger btn-sm mt-1" wire:click="removeProductFromAlquiler({{ $index }})">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Resumen Financiero -->
                                    <div class="card border-secondary">
                                        <div class="card-header bg-secondary text-white py-2">
                                            <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Resumen de Costos</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">💰 Anticipo (Bs.) *</label>
                                                <input type="number" step="0.01" class="form-control @error('anticipo') is-invalid @enderror" wire:model="anticipo" placeholder="0.00">
                                                @error('anticipo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            @if($anticipo > 0)
                                                <div class="alert alert-info mb-3">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    <strong>Registro de Anticipo:</strong> El anticipo de Bs. {{ number_format($anticipo, 2) }} se registrará automáticamente en la caja seleccionada.
                                                </div>

                                                <div class="card border-warning mb-3">
                                                    <div class="card-header bg-warning text-dark">
                                                        <i class="fas fa-cash-register me-2"></i>Información de Caja
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label">Caja de Destino *</label>
                                                                <select class="form-select select2-caja @error('caja_id') is-invalid @enderror" wire:model="caja_id">
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
                                                            <div class="col-md-6">
                                                                <label class="form-label">Método de Pago *</label>
                                                                <select class="form-select @error('metodo_pago') is-invalid @enderror" wire:model="metodo_pago">
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

                                                        @if($caja_id)
                                                            @php
                                                                $cajaSeleccionadaAlquiler = $cajas->find($caja_id);
                                                            @endphp
                                                            @if($cajaSeleccionadaAlquiler)
                                                                <div class="alert alert-success mt-3 mb-0">
                                                                    <i class="fas fa-check-circle me-2"></i>
                                                                    <strong>Caja Seleccionada:</strong> {{ $cajaSeleccionadaAlquiler->nombre }}<br>
                                                                    <small>
                                                                        📍 Sucursal: {{ $cajaSeleccionadaAlquiler->sucursal->nombre }} |
                                                                        💰 Saldo Actual: Bs. {{ number_format($cajaSeleccionadaAlquiler->saldo_actual, 2) }} |
                                                                        🔄 Nuevo Saldo: Bs. {{ number_format($cajaSeleccionadaAlquiler->saldo_actual + $anticipo, 2) }}
                                                                        @if($cajaSeleccionadaAlquiler->es_caja_principal) | ⭐ Caja Principal @endif
                                                                    </small>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="border-top pt-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="fw-bold">Subtotal:</span>
                                                    <span class="fw-bold text-primary">Bs. {{ number_format($this->calculateSubtotal(), 2) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="fw-bold text-success">Anticipo:</span>
                                                    <span class="fw-bold text-success">Bs. {{ number_format($anticipo, 2) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-top pt-2">
                                                    <span class="fw-bold">Saldo Pendiente:</span>
                                                    <span class="fw-bold fs-5 text-warning">Bs. {{ number_format($this->calculateSubtotal() - $anticipo, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer bg-light">
                        @if(empty($selectedProducts) || !$cliente_id || !$sucursal_id || !$garantia_id)
                            <div class="alert alert-warning mb-3 w-100">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Completar información requerida:</strong>
                                <ul class="mb-0 mt-1">
                                    @if(!$cliente_id)<li>✗ Seleccionar cliente</li>@endif
                                    @if(!$sucursal_id)<li>✗ Seleccionar sucursal</li>@endif
                                    @if(!$garantia_id)<li>✗ Seleccionar garantía (OBLIGATORIO)</li>@endif
                                    @if(empty($selectedProducts))<li>✗ Agregar al menos un producto</li>@endif
                                </ul>
                            </div>
                        @endif

                        <button type="button" class="btn btn-secondary" wire:click="closeNewAlquilerModal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-primary btn-lg" wire:click="saveNewAlquiler"
                                {{ empty($selectedProducts) || !$cliente_id || !$sucursal_id || !$garantia_id ? 'disabled' : '' }}>
                            <i class="fas fa-handshake me-2"></i>Crear Contrato de Alquiler
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Modal Gestión de Garantías -->
    @if ($showGarantiaModal && $selectedAlquiler)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="fas fa-shield-alt me-2"></i>Aplicar Penalización a Garantía
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeGarantiaModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Alquiler:</strong> {{ $selectedAlquiler->numero_contrato }}<br>
                            <strong>Garantía:</strong> {{ $selectedAlquiler->garantia->numero_ticket }}<br>
                            <strong>Monto disponible:</strong> Bs. {{ number_format($selectedAlquiler->garantia->monto_disponible, 2) }}
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Monto a aplicar (Bs.) *</label>
                            <input type="number" step="0.01" class="form-control @error('monto_aplicar_garantia') is-invalid @enderror" 
                                   wire:model="monto_aplicar_garantia" max="{{ $selectedAlquiler->garantia->monto_disponible }}">
                            @error('monto_aplicar_garantia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Motivo de la aplicación *</label>
                            <textarea class="form-control @error('motivo_aplicacion') is-invalid @enderror" rows="3" 
                                      wire:model="motivo_aplicacion" placeholder="Describe el motivo por el cual se aplica este monto..."></textarea>
                            @error('motivo_aplicacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Atención:</strong> Este monto será descontado de la garantía y no podrá ser revertido.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeGarantiaModal">Cancelar</button>
                        <button type="button" class="btn btn-warning" wire:click="aplicarMontoGarantia">
                            <i class="fas fa-check me-1"></i>Aplicar Penalización
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Modal Devolución -->
    @if ($showDevolucionModal && $selectedAlquiler)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-undo me-2"></i>Devolución de Alquiler: {{ $selectedAlquiler->numero_contrato }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeDevolucionModal"></button>
                    </div>

                    <div class="modal-body">
                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Fecha y hora de devolución</label>
                                <input type="datetime-local" class="form-control @error('fecha_devolucion_real') is-invalid @enderror" wire:model="fecha_devolucion_real">
                                @error('fecha_devolucion_real')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Penalización (Bs.)</label>
                                <input type="number" step="0.01" class="form-control @error('penalizacion') is-invalid @enderror" wire:model="penalizacion" placeholder="0.00">
                                @error('penalizacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Observaciones generales</label>
                                <input type="text" class="form-control" wire:model="observaciones_devolucion" placeholder="Notas generales de la devolución">
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-boxes me-2"></i>Detalle de productos</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 35%">Producto</th>
                                                <th style="width: 10%">Cantidad</th>
                                                <th style="width: 20%">Estado devolución</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($devolucionDetalles as $idx => $item)
                                                <tr>
                                                    <td class="align-middle">{{ $item['producto'] }}</td>
                                                    <td class="align-middle">{{ $item['cantidad'] }}</td>
                                                    <td>
                                                        <select class="form-select form-select-sm" wire:model="devolucionDetalles.{{ $idx }}.estado">
                                                            <option value="PENDIENTE">Pendiente</option>
                                                            <option value="DEVUELTO">Devuelto</option>
                                                            <option value="DAÑADO">Dañado</option>
                                                            <option value="PERDIDO">Perdido</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" wire:model="devolucionDetalles.{{ $idx }}.observaciones" placeholder="Observaciones del ítem">
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if (empty($devolucionDetalles))
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-3">Sin detalles para devolver</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeDevolucionModal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-success" wire:click="procesarDevolucion">
                            <i class="fas fa-check me-1"></i>Confirmar Devolución
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Modal Comprobante de Alquiler -->
    @if ($showPrintModal && $selectedAlquiler)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-print me-2"></i>Comprobante de Alquiler - {{ $selectedAlquiler->numero_contrato }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closePrintModal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <!-- Comprobante de Alquiler para Impresora Térmica -->
                        <div id="comprobante-alquiler" class="p-3" style="background: white; font-family: 'Courier New', monospace; width: 300px; margin: 0 auto; font-size: 12px; line-height: 1.2;">
                            <!-- Header Compacto -->
                            <div class="text-center mb-2" style="border-bottom: 1px dashed #000; padding-bottom: 8px;">
                                <div style="font-weight: bold; font-size: 14px; margin-bottom: 2px;">FOLKLORE BOLIVIA</div>
                                <div style="font-size: 10px; margin-bottom: 1px;">Alquiler de Trajes Típicos</div>
                                <div style="font-size: 10px; margin-bottom: 4px;">{{ $selectedAlquiler->sucursal->direccion ?? 'Dirección de la sucursal' }}</div>
                                <div style="font-weight: bold; font-size: 12px; margin-bottom: 2px;">CONTRATO DE ALQUILER</div>
                                <div style="font-weight: bold; font-size: 11px;">Nº {{ $selectedAlquiler->numero_contrato }}</div>
                            </div>

                            <!-- Información Básica -->
                            <div class="mb-2" style="border-bottom: 1px dashed #000; padding-bottom: 6px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                                    <span style="font-weight: bold;">FECHA:</span>
                                    <span>{{ $selectedAlquiler->fecha_alquiler->format('d/m/Y') }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                                    <span style="font-weight: bold;">ENTREGA:</span>
                                    <span>{{ $selectedAlquiler->hora_entrega ?? '09:00' }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                                    <span style="font-weight: bold;">DEVOLUCIÓN:</span>
                                    <span>{{ $selectedAlquiler->fecha_devolucion_programada->format('d/m/Y') }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                                    <span style="font-weight: bold;">HORA DEV:</span>
                                    <span>{{ $selectedAlquiler->hora_devolucion_programada ?? '18:00' }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="font-weight: bold;">DÍAS:</span>
                                    <span>{{ $selectedAlquiler->dias_alquiler }} días</span>
                                </div>
                            </div>

                            <!-- Datos del Cliente -->
                            <div class="mb-2" style="border-bottom: 1px dashed #000; padding-bottom: 6px;">
                                <div class="text-center" style="font-weight: bold; margin-bottom: 4px; font-size: 11px;">DATOS DEL CLIENTE</div>
                                <div style="margin-bottom: 2px;">
                                    <span style="font-weight: bold;">NOMBRE:</span><br>
                                    <span>{{ $selectedAlquiler->cliente->nombres }} {{ $selectedAlquiler->cliente->apellidos }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                                    <div style="width: 48%;">
                                        <span style="font-weight: bold; font-size: 10px;">TEL:</span><br>
                                        <span style="font-size: 10px;">{{ $selectedAlquiler->cliente->telefono ?? 'N/A' }}</span>
                                    </div>
                                    <div style="width: 48%;">
                                        <span style="font-weight: bold; font-size: 10px;">CI:</span><br>
                                        <span style="font-size: 10px;">{{ $selectedAlquiler->cliente->carnet_identidad ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                @if($selectedAlquiler->unidadEducativa)
                                    <div style="margin-bottom: 2px;">
                                        <span style="font-weight: bold; font-size: 10px;">UNIDAD EDUCATIVA:</span><br>
                                        <span style="font-size: 10px;">{{ $selectedAlquiler->unidadEducativa->nombre }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Productos Alquilados -->
                            <div class="mb-2" style="border-bottom: 1px dashed #000; padding-bottom: 6px;">
                                <div class="text-center" style="font-weight: bold; margin-bottom: 4px; font-size: 11px;">PRODUCTOS ALQUILADOS</div>
                                @forelse($selectedAlquiler->detalles as $detalle)
                                    <div style="margin-bottom: 4px;">
                                        <div style="font-weight: bold; font-size: 11px;">{{ $detalle->producto->nombre ?? $detalle->nombre_producto }}</div>
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div style="font-size: 10px;">
                                                {{ $detalle->cantidad }} x Bs. {{ number_format($detalle->precio_unitario, 2) }}
                                            </div>
                                            <div style="font-weight: bold;">Bs. {{ number_format($detalle->subtotal, 2) }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <div style="text-center; color: #666; font-size: 10px;">Sin productos registrados</div>
                                @endforelse
                            </div>

                            <!-- Totales -->
                            <div class="mb-2" style="border-bottom: 1px dashed #000; padding-bottom: 6px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                                    <span style="font-weight: bold;">SUBTOTAL:</span>
                                    <span style="font-weight: bold;">Bs. {{ number_format($selectedAlquiler->subtotal, 2) }}</span>
                                </div>
                                <div style="border-top: 2px solid #000; padding-top: 4px; margin-top: 4px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 2px; font-weight: bold; font-size: 14px;">
                                        <span>TOTAL:</span>
                                        <span>Bs. {{ number_format($selectedAlquiler->total, 2) }}</span>
                                    </div>
                                    @if($selectedAlquiler->anticipo_reserva > 0)
                                        <div style="display: flex; justify-content: space-between; margin-bottom: 2px; color: #0066cc; font-weight: bold;">
                                            <span>ANTICIPO RESERVA:</span>
                                            <span>Bs. {{ number_format($selectedAlquiler->anticipo_reserva, 2) }}</span>
                                        </div>
                                    @endif
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 2px; color: #198754; font-weight: bold;">
                                        <span>ANTICIPO TOTAL:</span>
                                        <span>Bs. {{ number_format($selectedAlquiler->anticipo, 2) }}</span>
                                    </div>
                                    @if($selectedAlquiler->saldo_pendiente > 0)
                                        <div style="display: flex; justify-content: space-between; color: #dc3545; font-weight: bold;">
                                            <span>SALDO PENDIENTE:</span>
                                            <span>Bs. {{ number_format($selectedAlquiler->saldo_pendiente, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Garantía -->
                            @if($selectedAlquiler->garantia)
                                <div class="mb-2" style="border-bottom: 1px dashed #000; padding-bottom: 6px;">
                                    <div class="text-center" style="font-weight: bold; margin-bottom: 4px; font-size: 11px;">GARANTÍA</div>
                                    <div style="margin-bottom: 2px;">
                                        <span style="font-weight: bold; font-size: 10px;">TIPO:</span>
                                        <span style="font-size: 10px;">{{ $selectedAlquiler->garantia->tipoGarantia->nombre ?? 'N/A' }}</span>
                                    </div>
                                    <div style="margin-bottom: 2px;">
                                        <span style="font-weight: bold; font-size: 10px;">TICKET:</span>
                                        <span style="font-size: 10px;">{{ $selectedAlquiler->garantia->numero_ticket }}</span>
                                    </div>
                                    @if($selectedAlquiler->garantia->monto > 0)
                                        <div style="margin-bottom: 2px;">
                                            <span style="font-weight: bold; font-size: 10px;">MONTO:</span>
                                            <span style="font-size: 10px;">Bs. {{ number_format($selectedAlquiler->garantia->monto, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Observaciones -->
                            @if($selectedAlquiler->observaciones || $selectedAlquiler->condiciones_especiales)
                                <div class="mb-2" style="border-bottom: 1px dashed #000; padding-bottom: 6px;">
                                    <div style="font-weight: bold; font-size: 10px; margin-bottom: 2px;">OBSERVACIONES:</div>
                                    @if($selectedAlquiler->observaciones)
                                        <div style="font-size: 9px; margin-bottom: 2px;">{{ $selectedAlquiler->observaciones }}</div>
                                    @endif
                                    @if($selectedAlquiler->condiciones_especiales)
                                        <div style="font-size: 9px; font-weight: bold;">CONDICIONES: {{ $selectedAlquiler->condiciones_especiales }}</div>
                                    @endif
                                </div>
                            @endif

                            <!-- Footer -->
                            <div class="text-center" style="padding-top: 6px;">
                                <div style="font-weight: bold; margin-bottom: 3px; font-size: 10px;">
                                    VENDEDOR: {{ $selectedAlquiler->usuarioCreacion->name ?? 'N/A' }}
                                </div>
                                <div style="font-size: 9px; margin-bottom: 3px;">
                                    {{ $selectedAlquiler->sucursal->direccion ?? 'Dirección de la sucursal' }}<br>
                                    Tel: {{ $selectedAlquiler->sucursal->telefono ?? 'N/A' }}
                                </div>
                                <div style="font-size: 8px; margin-bottom: 3px;">
                                    Impresión: {{ now()->format('d/m/Y H:i:s') }}
                                </div>
                                <div style="font-weight: bold; font-size: 10px; margin-bottom: 2px;">
                                    ¡Cuide los trajes!
                                </div>
                                <div style="font-size: 8px; margin-bottom: 3px;">
                                    Conserve este comprobante
                                </div>
                                <div style="font-size: 8px;">
                                    Tel: 2-2345678 | Cel: 70123456<br>
                                    www.folkloreBolivia.com
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closePrintModal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="imprimirComprobanteAlquiler()">
                            <i class="fas fa-print me-1"></i>Imprimir
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Otros modales (Ver, Devolución, Pago) aquí -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Escuchar eventos de SweetAlert desde Livewire
        window.addEventListener('swal', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                confirmButtonText: 'OK'
            });
        });

        // Función para imprimir comprobante de alquiler
        function imprimirComprobanteAlquiler() {
            const comprobante = document.getElementById('comprobante-alquiler').innerHTML;
            const ventana = window.open('', '_blank', 'width=320,height=600');
            ventana.document.write(`
                <html>
                    <head>
                        <title>Comprobante de Alquiler - Térmica</title>
                        <style>
                            @media print {
                                @page {
                                    size: 80mm auto;
                                    margin: 0;
                                }
                                body {
                                    margin: 0;
                                    padding: 0;
                                    width: 80mm;
                                    font-family: 'Courier New', monospace;
                                    font-size: 12px;
                                    line-height: 1.2;
                                    color: #000;
                                }
                                .no-print { display: none !important; }
                                * {
                                    box-sizing: border-box;
                                }
                            }
                            body {
                                margin: 0;
                                padding: 8px;
                                width: 80mm;
                                max-width: 300px;
                                font-family: 'Courier New', monospace;
                                font-size: 12px;
                                line-height: 1.2;
                                color: #000;
                                background: white;
                            }

                            /* Estilos para impresora térmica */
                            .text-center { text-align: center; }
                            .mb-2 { margin-bottom: 8px; }

                            /* Bordes punteados para separadores */
                            [style*="border-bottom: 1px dashed"] {
                                border-bottom: 1px dashed #000 !important;
                            }

                            /* Líneas sólidas para totales */
                            [style*="border-top: 2px solid"] {
                                border-top: 2px solid #000 !important;
                            }

                            /* Display flex para alineación */
                            [style*="display: flex"] {
                                display: flex !important;
                            }

                            [style*="justify-content: space-between"] {
                                justify-content: space-between !important;
                            }

                            /* Peso de fuente */
                            [style*="font-weight: bold"] {
                                font-weight: bold !important;
                            }

                            /* Colores - todo en negro para impresión térmica */
                            [style*="color: #198754"] {
                                color: #000 !important;
                                font-weight: bold !important;
                            }

                            [style*="color: #dc3545"] {
                                color: #000 !important;
                                font-weight: bold !important;
                            }

                            [style*="color: #0066cc"] {
                                color: #000 !important;
                                font-weight: bold !important;
                            }

                            [style*="color: #666"] {
                                color: #000 !important;
                            }

                            /* Tamaños de fuente específicos */
                            [style*="font-size: 14px"] { font-size: 14px !important; }
                            [style*="font-size: 12px"] { font-size: 12px !important; }
                            [style*="font-size: 11px"] { font-size: 11px !important; }
                            [style*="font-size: 10px"] { font-size: 10px !important; }
                            [style*="font-size: 9px"] { font-size: 9px !important; }
                            [style*="font-size: 8px"] { font-size: 8px !important; }

                            /* Márgenes y padding */
                            [style*="margin-bottom: 2px"] { margin-bottom: 2px !important; }
                            [style*="margin-bottom: 3px"] { margin-bottom: 3px !important; }
                            [style*="margin-bottom: 4px"] { margin-bottom: 4px !important; }
                            [style*="padding-bottom: 6px"] { padding-bottom: 6px !important; }
                            [style*="padding-bottom: 8px"] { padding-bottom: 8px !important; }
                            [style*="padding-top: 4px"] { padding-top: 4px !important; }
                            [style*="padding-top: 6px"] { padding-top: 6px !important; }
                            [style*="margin-top: 4px"] { margin-top: 4px !important; }

                            /* Anchos */
                            [style*="width: 48%"] { width: 48% !important; }

                            /* Eliminar bordes y fondos para impresión térmica */
                            * {
                                border-radius: 0 !important;
                                background: transparent !important;
                                box-shadow: none !important;
                            }
                        </style>
                    </head>
                    <body>
                        ${comprobante}
                    </body>
                </html>
            `);
            ventana.document.close();
            ventana.focus();

            // Configurar impresión automática para impresoras térmicas
            setTimeout(() => {
                ventana.print();
                ventana.close();
            }, 250);
        }
    </script>

    <!-- Modal de Pago -->
    @if($showPaymentModal && $selectedAlquiler)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar Pago - {{ $selectedAlquiler->numero_alquiler }}</h5>
                        <button type="button" class="btn-close" wire:click="closePaymentModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-primary">
                            <i class="fas fa-user me-2"></i>
                            <strong>Cliente:</strong> {{ $selectedAlquiler->cliente->nombres }} {{ $selectedAlquiler->cliente->apellidos }}<br>
                            <strong>Contrato:</strong> {{ $selectedAlquiler->numero_alquiler }}<br>
                            <strong>Saldo Pendiente:</strong> <span class="badge bg-danger fs-6">Bs. {{ number_format($selectedAlquiler->saldo_pendiente, 2) }}</span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Monto a Pagar *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs.</span>
                                        <input type="number" step="0.01" wire:model.live="monto_pago"
                                               class="form-control @error('monto_pago') is-invalid @enderror"
                                               max="{{ $selectedAlquiler->saldo_pendiente }}"
                                               placeholder="0.00">
                                    </div>
                                    @error('monto_pago') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <small class="text-muted">Máximo: Bs. {{ number_format($selectedAlquiler->saldo_pendiente, 2) }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Método de Pago *</label>
                                    <select wire:model="metodo_pago" class="form-select">
                                        <option value="EFECTIVO">💵 Efectivo</option>
                                        <option value="QR">📱 QR</option>
                                        <option value="TARJETA">💳 Tarjeta</option>
                                        <option value="TRANSFERENCIA">🏦 Transferencia</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if($monto_pago > 0)
                            <div class="card border-success mb-3">
                                <div class="card-header bg-success text-white">
                                    <i class="fas fa-cash-register me-2"></i>Destino del Pago
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Caja de Destino *</label>
                                        <select wire:model="caja_id" class="form-select select2-caja @error('caja_id') is-invalid @enderror">
                                            <option value="">Seleccione caja</option>
                                            @foreach($cajas as $caja)
                                                <option value="{{ $caja->id }}">
                                                    🏪 {{ $caja->nombre }} - {{ $caja->sucursal->nombre }}
                                                    💰 Saldo: Bs. {{ number_format($caja->saldo_actual, 2) }}
                                                    @if($caja->es_caja_principal) ⭐ @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('caja_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        @if(count($cajas) == 0)
                                            <div class="text-danger small mt-1">
                                                <i class="fas fa-exclamation-triangle"></i> No hay cajas abiertas disponibles
                                            </div>
                                        @endif
                                    </div>

                                    @if($caja_id)
                                        @php
                                            $cajaSeleccionadaPago = $cajas->find($caja_id);
                                        @endphp
                                        @if($cajaSeleccionadaPago)
                                            <div class="alert alert-info mb-3">
                                                <i class="fas fa-calculator me-2"></i>
                                                <strong>Resumen del Pago:</strong><br>
                                                <small>
                                                    💰 Saldo Actual: Bs. {{ number_format($cajaSeleccionadaPago->saldo_actual, 2) }}<br>
                                                    ➕ Pago a Registrar: Bs. {{ number_format($monto_pago, 2) }}<br>
                                                    🔄 <strong>Nuevo Saldo: Bs. {{ number_format($cajaSeleccionadaPago->saldo_actual + $monto_pago, 2) }}</strong><br>
                                                    📉 Saldo Pendiente Cliente: Bs. {{ number_format($selectedAlquiler->saldo_pendiente - $monto_pago, 2) }}
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
                                    <input type="text" wire:model="referencia_pago" class="form-control" placeholder="Número de transacción, etc.">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Observaciones</label>
                                    <textarea wire:model="observaciones_pago" class="form-control" rows="2" placeholder="Observaciones del pago"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closePaymentModal">Cancelar</button>
                            <button type="button" class="btn btn-success" wire:click="procesarPago"
                                    @if($monto_pago <= 0 || !$caja_id) disabled @endif>
                                <i class="fas fa-dollar-sign me-1"></i>Registrar Pago
                            </button>
                        </div>
                </div>
            </div>
        </div>
    @endif

</div>
