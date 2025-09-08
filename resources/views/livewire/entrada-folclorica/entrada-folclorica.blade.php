<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z"/>
                            <path d="M17 4a2 2 0 0 0 2 2a2 2 0 0 0 -2 2a2 2 0 0 0 -2 -2a2 2 0 0 0 2 -2"/>
                            <path d="M19 11h2m-1 -1v2"/>
                        </svg>
                        Entradas Folclóricas
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <button class="btn btn-primary" wire:click="abrirModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5l0 14"/>
                                <path d="M5 12l14 0"/>
                            </svg>
                            Nueva Entrada
                        </button>
                        <button class="btn btn-outline-secondary" wire:click="actualizar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/>
                                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/>
                            </svg>
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="page-body">
        <div class="container-xl">
            <!-- Filtros -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label">Estado</label>
                                    <select class="form-select" wire:model="filtroEstado" wire:change="aplicarFiltros">
                                        <option value="">Todos los estados</option>
                                        <option value="ACTIVO">Activo</option>
                                        <option value="DEVUELTO_PARCIAL">Devuelto Parcial</option>
                                        <option value="DEVUELTO_COMPLETO">Devuelto Completo</option>
                                        <option value="VENCIDO">Vencido</option>
                                        <option value="CANCELADO">Cancelado</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Fecha Evento</label>
                                    <input type="date" class="form-control" wire:model="filtroFecha" wire:change="aplicarFiltros">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Buscar</label>
                                    <input type="text" class="form-control" placeholder="Número, evento, contacto..." wire:model="busqueda" wire:keyup.debounce.500ms="aplicarFiltros">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button class="btn btn-outline-secondary w-100" wire:click="limpiarFiltros">Limpiar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Lista de Entradas -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Número</th>
                                        <th>Evento</th>
                                        <th>Fecha Evento</th>
                                        <th>Responsable</th>
                                        <th>Participantes</th>
                                        <th>Total Garantías</th>
                                        <th>Estado</th>
                                        <th class="w-1">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($entradas as $entrada)
                                    <tr>
                                        <td>
                                            <strong>{{ $entrada->numero_entrada }}</strong>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $entrada->nombre_evento }}</strong>
                                                <div class="text-muted">{{ Str::limit($entrada->descripcion_evento, 50) }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>{{ $entrada->fecha_evento ? $entrada->fecha_evento->format('d/m/Y') : '' }}</div>
                                            <div class="text-muted">{{ $entrada->hora_evento }}</div>
                                        </td>
                                        <td>
                                            <div>{{ $entrada->contacto_nombre }}</div>
                                            <div class="text-muted">{{ $entrada->contacto_telefono }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $entrada->cantidad_participantes }} personas</span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>Bs. {{ number_format($entrada->total_garantias, 2) }}</strong>
                                                @if($entrada->garantias_devueltas > 0)
                                                <div class="text-muted">Devuelto: Bs. {{ number_format($entrada->garantias_devueltas, 2) }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $entrada->estado_badge_class }}">
                                                {{ $entrada->estado_display }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" wire:click="verDetalle({{ $entrada->id }})" title="Ver Detalle">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                                    </svg>
                                                </button>
                                                <a href="{{ route('entrada-folklorica.participantes', $entrada->id) }}" class="btn btn-sm btn-outline-success" title="Gestionar Participantes">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/>
                                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('entrada-folklorica.devoluciones', $entrada->id) }}" class="btn btn-sm btn-outline-warning" title="Gestionar Devoluciones">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M9 14l-4 -4l4 -4"/>
                                                        <path d="M5 10h11a4 4 0 1 1 0 8h-1"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No hay entradas registradas</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Nueva Entrada -->
    @if($showModal)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Entrada Folclórica</h5>
                    <button type="button" class="btn-close" wire:click="cerrarModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Sucursal *</label>
                            <select class="form-select @error('sucursal_id') is-invalid @enderror" wire:model="sucursal_id">
                                <option value="">Seleccione sucursal</option>
                                @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                @endforeach
                            </select>
                            @error('sucursal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cliente Responsable *</label>
                            <select class="form-select @error('cliente_responsable_id') is-invalid @enderror" wire:model="cliente_responsable_id">
                                <option value="">Seleccione cliente</option>
                                @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                            @error('cliente_responsable_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-8">
                            <label class="form-label">Nombre del Evento *</label>
                            <input type="text" class="form-control @error('nombre_evento') is-invalid @enderror" wire:model="nombre_evento">
                            @error('nombre_evento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lugar del Evento *</label>
                            <input type="text" class="form-control @error('lugar_evento') is-invalid @enderror" wire:model="lugar_evento">
                            @error('lugar_evento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <label class="form-label">Descripción del Evento</label>
                            <textarea class="form-control" rows="2" wire:model="descripcion_evento"></textarea>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">Fecha del Evento *</label>
                            <input type="date" class="form-control @error('fecha_evento') is-invalid @enderror" wire:model="fecha_evento">
                            @error('fecha_evento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Hora *</label>
                            <input type="time" class="form-control @error('hora_evento') is-invalid @enderror" wire:model="hora_evento">
                            @error('hora_evento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha Entrega *</label>
                            <input type="date" class="form-control @error('fecha_entrega') is-invalid @enderror" wire:model="fecha_entrega">
                            @error('fecha_entrega') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha Devolución *</label>
                            <input type="date" class="form-control @error('fecha_devolucion_programada') is-invalid @enderror" wire:model="fecha_devolucion_programada">
                            @error('fecha_devolucion_programada') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Contacto Responsable *</label>
                            <input type="text" class="form-control @error('contacto_nombre') is-invalid @enderror" wire:model="contacto_nombre">
                            @error('contacto_nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" wire:model="contacto_telefono">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" wire:model="contacto_email">
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Cantidad de Participantes *</label>
                            <input type="number" class="form-control @error('cantidad_participantes') is-invalid @enderror" wire:model="cantidad_participantes" min="1">
                            @error('cantidad_participantes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Monto Garantía Individual (Bs.) *</label>
                            <input type="number" step="0.01" class="form-control @error('monto_garantia_individual') is-invalid @enderror" wire:model="monto_garantia_individual" min="0">
                            @error('monto_garantia_individual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <label class="form-label">Condiciones Especiales</label>
                            <textarea class="form-control" rows="2" wire:model="condiciones_especiales"></textarea>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" rows="2" wire:model="observaciones"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cerrarModal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="guardar" wire:loading.attr="disabled">
                        <span wire:loading.remove>Crear Entrada</span>
                        <span wire:loading>Guardando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
    
    <!-- Modal Detalle -->
    @if($showDetalleModal && $entradaSeleccionada)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Detalle Entrada: {{ $entradaSeleccionada->numero_entrada }}
                    </h5>
                    <button type="button" class="btn-close" wire:click="cerrarDetalleModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr><td><strong>Evento:</strong></td><td>{{ $entradaSeleccionada->nombre_evento }}</td></tr>
                                <tr><td><strong>Fecha:</strong></td><td>{{ $entradaSeleccionada->fecha_evento ? $entradaSeleccionada->fecha_evento->format('d/m/Y') : '' }} {{ $entradaSeleccionada->hora_evento }}</td></tr>
                                <tr><td><strong>Lugar:</strong></td><td>{{ $entradaSeleccionada->lugar_evento }}</td></tr>
                                <tr><td><strong>Responsable:</strong></td><td>{{ $entradaSeleccionada->contacto_nombre }}</td></tr>
                                <tr><td><strong>Teléfono:</strong></td><td>{{ $entradaSeleccionada->contacto_telefono }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr><td><strong>Participantes:</strong></td><td>{{ $entradaSeleccionada->cantidad_participantes }}</td></tr>
                                <tr><td><strong>Garantía Individual:</strong></td><td>Bs. {{ number_format($entradaSeleccionada->monto_garantia_individual, 2) }}</td></tr>
                                <tr><td><strong>Total Garantías:</strong></td><td>Bs. {{ number_format($entradaSeleccionada->total_garantias, 2) }}</td></tr>
                                <tr><td><strong>Estado:</strong></td><td><span class="badge {{ $entradaSeleccionada->estado_badge_class }}">{{ $entradaSeleccionada->estado_display }}</span></td></tr>
                                <tr><td><strong>Devolución:</strong></td><td>{{ $entradaSeleccionada->fecha_devolucion_programada ? $entradaSeleccionada->fecha_devolucion_programada->format('d/m/Y') : '' }}</td></tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($entradaSeleccionada->detalles->count() > 0)
                    <hr>
                    <h6>Participantes y Productos</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Participante</th>
                                    <th>Producto</th>
                                    <th>Talla</th>
                                    <th>Precio</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entradaSeleccionada->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->nombre_participante }}</td>
                                    <td>{{ $detalle->nombre_producto }}</td>
                                    <td>{{ $detalle->talla_solicitada }}</td>
                                    <td>Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td><span class="badge {{ $detalle->estado_badge_class }}">{{ $detalle->estado_display }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    
                    @if($entradaSeleccionada->garantias->count() > 0)
                    <hr>
                    <h6>Garantías Individuales</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Participante</th>
                                    <th>Número Garantía</th>
                                    <th>Monto</th>
                                    <th>Disponible</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entradaSeleccionada->garantias as $garantia)
                                <tr>
                                    <td>{{ $garantia->nombre_participante }}</td>
                                    <td>{{ $garantia->numero_garantia }}</td>
                                    <td>Bs. {{ number_format($garantia->monto_garantia, 2) }}</td>
                                    <td>Bs. {{ number_format($garantia->monto_disponible, 2) }}</td>
                                    <td><span class="badge {{ $garantia->estado_badge_class }}">{{ $garantia->estado_display }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cerrarDetalleModal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>