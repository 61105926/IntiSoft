<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-gradient fw-bold">
            <i class="fas fa-mask me-2"></i>Eventos Folklóricos
        </h2>
        <button type="button" class="btn btn-primary" wire:click="openNewEventoModal">
            <i class="fas fa-plus me-2"></i>Nuevo Evento
        </button>
    </div>

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
    <div class="row row-cols-1 row-cols-md-6 g-4 mb-4">
        <div class="col">
            <div class="modern-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Eventos</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['total_eventos'] }}</p>
                    </div>
                    <i class="fas fa-calendar text-primary fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="modern-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Activos</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['eventos_activos'] }}</p>
                    </div>
                    <i class="fas fa-play-circle text-success fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="modern-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Próximos</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['eventos_proximos'] }}</p>
                    </div>
                    <i class="fas fa-clock text-warning fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="modern-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Participantes</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['participantes_totales'] }}</p>
                    </div>
                    <i class="fas fa-users text-info fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="modern-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Eventos Hoy</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['eventos_hoy'] }}</p>
                    </div>
                    <i class="fas fa-calendar-day text-danger fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="modern-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Ingresos</p>
                        <p class="fs-4 fw-bold mb-0">Bs. {{ number_format($estadisticas['ingresos_eventos'], 2) }}</p>
                    </div>
                    <i class="fas fa-dollar-sign text-secondary fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="modern-card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar eventos..."
                               wire:model.debounce.300ms="searchTerm">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model="filterEstado">
                        <option value="TODOS">Todos los Estados</option>
                        <option value="PLANIFICADO">Planificado</option>
                        <option value="CONFIRMADO">Confirmado</option>
                        <option value="EN_CURSO">En Curso</option>
                        <option value="FINALIZADO">Finalizado</option>
                        <option value="CANCELADO">Cancelado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model="filterTipo">
                        <option value="TODOS">Todos los Tipos</option>
                        <option value="FESTIVAL">Festival</option>
                        <option value="CONCURSO">Concurso</option>
                        <option value="PRESENTACION">Presentación</option>
                        <option value="DESFILE">Desfile</option>
                        <option value="ESCOLAR">Escolar</option>
                        <option value="UNIVERSITARIO">Universitario</option>
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
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary w-100" wire:click="openAlertasModal">
                        <i class="fas fa-exclamation-triangle me-1"></i>Alertas
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Eventos -->
    <div class="modern-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Evento</th>
                            <th>Fecha/Hora</th>
                            <th>Tipo</th>
                            <th>Participantes</th>
                            <th>Estado</th>
                            <th>Ingresos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($eventos as $evento)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $evento->nombre_evento }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $evento->numero_evento }}</small>
                                        @if($evento->institucion_organizadora)
                                            <br>
                                            <small class="text-info">{{ $evento->institucion_organizadora }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <i class="fas fa-calendar me-1"></i>{{ $evento->fecha_evento->format('d/m/Y') }}
                                        @if($evento->hora_evento)
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>{{ $evento->hora_evento->format('H:i') }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $evento->tipo_evento }}</span>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong>{{ $evento->obtenerParticipantesConfirmados() }}</strong>
                                        <small class="text-muted">/ {{ $evento->numero_participantes }}</small>
                                        <div class="progress mt-1" style="height: 5px;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: {{ $evento->obtenerProgreso() }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match($evento->estado) {
                                            'PLANIFICADO' => 'bg-warning',
                                            'CONFIRMADO' => 'bg-info',
                                            'EN_CURSO' => 'bg-primary',
                                            'FINALIZADO' => 'bg-success',
                                            'CANCELADO' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $evento->estado }}</span>
                                </td>
                                <td>
                                    <strong>Bs. {{ number_format($evento->obtenerTotalRecaudado(), 2) }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        Est: Bs. {{ number_format($evento->total_estimado, 2) }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-primary" wire:click="viewEvento({{ $evento->id }})"
                                                title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        @if($evento->puedeEditarse())
                                            <button class="btn btn-outline-success"
                                                    wire:click="openParticipanteModal({{ $evento->id }})"
                                                    title="Agregar Participante">
                                                <i class="fas fa-user-plus"></i>
                                            </button>
                                        @endif

                                        @if($evento->estado === 'CONFIRMADO')
                                            <button class="btn btn-outline-warning"
                                                    wire:click="openFinalizarEventoModal({{ $evento->id }})"
                                                    title="Finalizar Evento">
                                                <i class="fas fa-flag-checkered"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No hay eventos registrados</h5>
                                    <p class="text-muted">Crea tu primer evento folklórico</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $eventos->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Evento -->
    @if($showNewEventoModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-plus me-2"></i>Nuevo Evento Folklórico
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeNewEventoModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nombre del Evento *</label>
                                    <input type="text" class="form-control @error('nombre_evento') is-invalid @enderror"
                                           wire:model="nombre_evento" placeholder="Festival de Danzas Folklóricas">
                                    @error('nombre_evento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Evento *</label>
                                    <select class="form-select @error('tipo_evento') is-invalid @enderror"
                                            wire:model="tipo_evento">
                                        <option value="FESTIVAL">🎭 Festival</option>
                                        <option value="CONCURSO">🏆 Concurso</option>
                                        <option value="PRESENTACION">🎪 Presentación</option>
                                        <option value="DESFILE">🚶‍♀️ Desfile</option>
                                        <option value="ESCOLAR">🎓 Escolar</option>
                                        <option value="UNIVERSITARIO">🎓 Universitario</option>
                                    </select>
                                    @error('tipo_evento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" rows="3" wire:model="descripcion"
                                      placeholder="Descripción del evento, objetivos, características especiales..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Institución Organizadora</label>
                                    <input type="text" class="form-control" wire:model="institucion_organizadora"
                                           placeholder="Colegio, Universidad, Grupo Cultural...">
                                </div>
                            </div>
                            <div class="col-md-6">
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
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Fecha del Evento *</label>
                                    <input type="date" class="form-control @error('fecha_evento') is-invalid @enderror"
                                           wire:model="fecha_evento">
                                    @error('fecha_evento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Hora del Evento</label>
                                    <input type="time" class="form-control" wire:model="hora_evento">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-truck me-1"></i>¿Requiere Transporte?
                                    </label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="requiere_transporte">
                                        <label class="form-check-label">Sí, requiere transporte</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Lugar del Evento *</label>
                                    <input type="text" class="form-control @error('lugar_evento') is-invalid @enderror"
                                           wire:model="lugar_evento" placeholder="Teatro, Plaza, Colegio...">
                                    @error('lugar_evento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Dirección Completa</label>
                                    <input type="text" class="form-control" wire:model="direccion_evento"
                                           placeholder="Dirección detallada del lugar">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">N° Participantes *</label>
                                    <input type="number" class="form-control @error('numero_participantes') is-invalid @enderror"
                                           wire:model="numero_participantes" min="1" placeholder="50">
                                    @error('numero_participantes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Costo por Participante (Bs.) *</label>
                                    <input type="number" step="0.01" class="form-control @error('costo_por_participante') is-invalid @enderror"
                                           wire:model="costo_por_participante" min="0" placeholder="150.00">
                                    @error('costo_por_participante')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Total Estimado</label>
                                    <div class="form-control-plaintext bg-light border rounded text-center fw-bold">
                                        Bs. {{ number_format($numero_participantes * $costo_por_participante, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones y Notas Especiales</label>
                            <textarea class="form-control" rows="3" wire:model="observaciones"
                                      placeholder="Requisitos especiales, horarios, contactos, etc."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeNewEventoModal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="saveNewEvento">
                            <i class="fas fa-save me-1"></i>Crear Evento
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Ver Evento -->
    @if($showViewEventoModal && $selectedEvento)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-eye me-2"></i>{{ $selectedEvento->nombre_evento }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeViewEventoModal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Información del Evento -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <i class="fas fa-info-circle me-2"></i>Información del Evento
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Número:</strong> {{ $selectedEvento->numero_evento }}</p>
                                        <p><strong>Tipo:</strong> <span class="badge bg-secondary">{{ $selectedEvento->tipo_evento }}</span></p>
                                        <p><strong>Fecha:</strong> {{ $selectedEvento->fecha_evento->format('d/m/Y') }}</p>
                                        <p><strong>Lugar:</strong> {{ $selectedEvento->lugar_evento }}</p>
                                        <p><strong>Estado:</strong>
                                            <span class="badge bg-info">{{ $selectedEvento->estado }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                                    </div>
                                    <div class="card-body">
                                        @php $stats = $selectedEvento->obtenerEstadisticas(); @endphp
                                        <p><strong>Participantes:</strong> {{ $stats['participantes_confirmados'] }}/{{ $stats['total_participantes'] }}</p>
                                        <p><strong>Progreso:</strong> {{ $stats['progreso_porcentaje'] }}%</p>
                                        <p><strong>Recaudado:</strong> Bs. {{ number_format($stats['total_recaudado'], 2) }}</p>
                                        <p><strong>Vestimentas:</strong> {{ $stats['vestimentas_asignadas'] }} asignadas</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Participantes -->
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-users me-2"></i>Participantes
                                    </h6>
                                    @if($selectedEvento->puedeEditarse())
                                        <button class="btn btn-sm btn-success"
                                                wire:click="openParticipanteModal({{ $selectedEvento->id }})">
                                            <i class="fas fa-user-plus me-1"></i>Agregar
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                @if($selectedEvento->participantes->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Participante</th>
                                                    <th>Talla</th>
                                                    <th>Estado</th>
                                                    <th>Pago</th>
                                                    <th>Vestimentas</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($selectedEvento->participantes as $participante)
                                                    <tr>
                                                        <td>{{ $participante->numero_participante }}</td>
                                                        <td>{{ $participante->nombre_completo }}</td>
                                                        <td><span class="badge bg-info">{{ $participante->talla_general }}</span></td>
                                                        <td><span class="badge bg-primary">{{ $participante->estado_participante }}</span></td>
                                                        <td><span class="badge bg-warning">{{ $participante->estado_pago }}</span></td>
                                                        <td>{{ $participante->vestimentas->count() }} piezas</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                @if($participante->puedeAsignarVestimenta())
                                                                    <button class="btn btn-outline-primary"
                                                                            wire:click="openVestimentaModal({{ $participante->id }})"
                                                                            title="Asignar Vestimenta">
                                                                        <i class="fas fa-tshirt"></i>
                                                                    </button>
                                                                @endif
                                                                @if($participante->estado_participante === 'REGISTRADO')
                                                                    <button class="btn btn-outline-success"
                                                                            wire:click="confirmarParticipante({{ $participante->id }})"
                                                                            title="Confirmar">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                @endif
                                                                @if($participante->puedeCancelarse())
                                                                    <button class="btn btn-outline-danger"
                                                                            wire:click="cancelarParticipante({{ $participante->id }})"
                                                                            title="Cancelar">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">No hay participantes registrados</h6>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeViewEventoModal">
                            <i class="fas fa-times me-1"></i>Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Agregar Participante -->
    @if($showParticipanteModal && $selectedEvento)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-user-plus me-2"></i>Registrar Participante
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeParticipanteModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Evento:</strong> {{ $selectedEvento->nombre_evento }}
                            ({{ $selectedEvento->obtenerParticipantesConfirmados() }}/{{ $selectedEvento->numero_participantes }} participantes)
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Cliente *</label>
                                    <select class="form-select select2-cliente @error('participante_cliente_id') is-invalid @enderror"
                                            wire:model="participante_cliente_id">
                                        <option value="">Seleccione un cliente</option>
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->id }}">
                                                {{ $cliente->nombres }} {{ $cliente->apellidos }} - {{ $cliente->cedula }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('participante_cliente_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nombre Completo *</label>
                                    <input type="text" class="form-control @error('participante_nombre') is-invalid @enderror"
                                           wire:model="participante_nombre" placeholder="Nombre como aparecerá en el evento">
                                    @error('participante_nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Cédula</label>
                                    <input type="text" class="form-control" wire:model="participante_cedula">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" wire:model="participante_telefono">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Edad</label>
                                    <input type="number" class="form-control" wire:model="participante_edad" min="1" max="99">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" wire:model="participante_email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Talla General *</label>
                                    <select class="form-select @error('participante_talla') is-invalid @enderror"
                                            wire:model="participante_talla">
                                        <option value="XS">XS - Extra Pequeña</option>
                                        <option value="S">S - Pequeña</option>
                                        <option value="M">M - Mediana</option>
                                        <option value="L">L - Grande</option>
                                        <option value="XL">XL - Extra Grande</option>
                                        <option value="XXL">XXL - Extra Extra Grande</option>
                                    </select>
                                    @error('participante_talla')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Monto Garantía (Bs.) *</label>
                                    <input type="number" step="0.01" class="form-control @error('participante_monto_garantia') is-invalid @enderror"
                                           wire:model="participante_monto_garantia" min="0" placeholder="200.00">
                                    @error('participante_monto_garantia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Monto Participación (Bs.) *</label>
                                    <input type="number" step="0.01" class="form-control @error('participante_monto_participacion') is-invalid @enderror"
                                           wire:model="participante_monto_participacion" min="0"
                                           value="{{ $selectedEvento->costo_por_participante }}">
                                    @error('participante_monto_participacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones Especiales</label>
                            <textarea class="form-control" rows="3" wire:model="participante_observaciones"
                                      placeholder="Alergias, restricciones, preferencias, etc."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeParticipanteModal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-success" wire:click="registrarParticipante">
                            <i class="fas fa-user-plus me-1"></i>Registrar Participante
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Asignar Vestimenta -->
    @if($showVestimentaModal && $selectedParticipante)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="fas fa-tshirt me-2"></i>Asignar Vestimenta
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeVestimentaModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Participante:</strong> {{ $selectedParticipante->nombre_completo }}<br>
                            <strong>Talla:</strong> {{ $selectedParticipante->talla_general }}<br>
                            <strong>Evento:</strong> {{ $selectedParticipante->evento->nombre_evento }}
                        </div>

                        <!-- Agregar Productos -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Seleccionar Vestimentas</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <select class="form-select select2-producto" wire:model="currentProductId">
                                            <option value="">Seleccione una vestimenta</option>
                                            @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}">
                                                    {{ $producto->nombre }} - {{ $producto->tipo_vestimenta }}
                                                    ({{ $producto->talla }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary w-100"
                                                wire:click="addProductoVestimenta"
                                                {{ !$currentProductId ? 'disabled' : '' }}>
                                            <i class="fas fa-plus me-1"></i>Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Productos Seleccionados -->
                        @if(count($vestimentaProductos) > 0)
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Vestimentas Seleccionadas</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Tipo</th>
                                                    <th>Stock</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($vestimentaProductos as $index => $producto)
                                                    <tr>
                                                        <td>{{ $producto['nombre'] }}</td>
                                                        <td><span class="badge bg-secondary">{{ $producto['tipo_vestimenta'] }}</span></td>
                                                        <td>{{ $producto['stock_disponible'] }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-danger"
                                                                    wire:click="removeProductoVestimenta({{ $index }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Vestimentas Ya Asignadas -->
                        @if($selectedParticipante->vestimentas->count() > 0)
                            <div class="card mt-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Vestimentas Ya Asignadas</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Estado</th>
                                                    <th>Fecha Asignación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($selectedParticipante->vestimentas as $vestimenta)
                                                    <tr>
                                                        <td>{{ $vestimenta->producto->nombre }}</td>
                                                        <td><span class="badge bg-info">{{ $vestimenta->estado_vestimenta }}</span></td>
                                                        <td>{{ $vestimenta->fecha_asignacion->format('d/m/Y H:i') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeVestimentaModal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-warning" wire:click="asignarVestimentas"
                                {{ count($vestimentaProductos) == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-tshirt me-1"></i>Asignar Vestimentas
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Finalizar Evento -->
    @if($showFinalizarEventoModal && $selectedEvento)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-flag-checkered me-2"></i>Finalizar Evento
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeFinalizarEventoModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>¡Atención!</strong> Esta acción finalizará el evento y procesará automáticamente:
                            <ul class="mb-0 mt-2">
                                <li>Devolución de todas las vestimentas asignadas</li>
                                <li>Liberación de todas las garantías</li>
                                <li>Finalización de todos los participantes</li>
                                <li>Cambio de estado del evento a "FINALIZADO"</li>
                            </ul>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Resumen del Evento</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Evento:</strong> {{ $selectedEvento->nombre_evento }}</p>
                                        <p><strong>Fecha:</strong> {{ $selectedEvento->fecha_evento->format('d/m/Y') }}</p>
                                        <p><strong>Participantes:</strong> {{ $selectedEvento->obtenerParticipantesConfirmados() }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Vestimentas Asignadas:</strong> {{ $selectedEvento->vestimentas->count() }}</p>
                                        <p><strong>Total Recaudado:</strong> Bs. {{ number_format($selectedEvento->obtenerTotalRecaudado(), 2) }}</p>
                                        <p><strong>Estado Actual:</strong> <span class="badge bg-primary">{{ $selectedEvento->estado }}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeFinalizarEventoModal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="finalizarEvento">
                            <i class="fas fa-flag-checkered me-1"></i>Finalizar Evento
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Configuración específica para eventos
    document.addEventListener('livewire:load', function () {
        // Auto-rellenar datos del cliente seleccionado
        Livewire.on('clienteSeleccionado', (clienteData) => {
            if (clienteData.nombres) {
                @this.set('participante_nombre', clienteData.nombres + ' ' + (clienteData.apellidos || ''));
            }
            if (clienteData.telefono) {
                @this.set('participante_telefono', clienteData.telefono);
            }
            if (clienteData.email) {
                @this.set('participante_email', clienteData.email);
            }
        });

        // Confirmación para acciones críticas
        Livewire.on('confirmarFinalizacion', () => {
            if (confirm('¿Está seguro de finalizar este evento? Esta acción no se puede deshacer.')) {
                @this.call('finalizarEvento');
            }
        });
    });
</script>
@endpush