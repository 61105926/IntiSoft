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
                                                    <select class="form-select form-select-sm @error('cliente_id') is-invalid @enderror" wire:model="cliente_id">
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
                                                <div class="col-md-3">
                                                    <label class="form-label fw-bold">🔗 Reserva</label>
                                                    <select class="form-select form-select-sm" wire:model="reserva_id">
                                                        <option value="">Opcional</option>
                                                        @foreach ($reservas as $reserva)
                                                            <option value="{{ $reserva->id }}">{{ $reserva->numero_reserva }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sección Garantía -->
                                    <div class="card border-0 bg-light mb-4">
                                        <div class="card-header bg-warning text-dark py-2">
                                            <h6 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Garantía (Opcional)</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">🛡️ Seleccionar Garantía</label>
                                                    <select class="form-select form-select-sm" wire:model="garantia_id">
                                                        <option value="">Opcional - Sin garantía</option>
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
                                                    <div class="form-text">
                                                        <small class="text-info">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Solo se muestran garantías activas del cliente seleccionado
                                                        </small>
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
                                        <div class="card-body p-3">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Producto</label>
                                                <select class="form-select form-select-sm" wire:model="currentProductId">
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
                        <button type="button" class="btn btn-secondary" wire:click="closeNewAlquilerModal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-primary btn-lg" wire:click="saveNewAlquiler" 
                                {{ empty($selectedProducts) || !$cliente_id || !$sucursal_id ? 'disabled' : '' }}>
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

    <!-- Otros modales (Ver, Devolución, Pago) aquí -->

    <!-- SweetAlert -->
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
    </script>

</div>
