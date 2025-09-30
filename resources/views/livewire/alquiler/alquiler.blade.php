<div class="container-fluid py-4">

    {{-- HEADER SECTION --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="fas fa-tshirt text-primary me-2"></i>
                        Gestión de Alquileres
                    </h2>
                    <p class="text-muted mb-0">Administra los alquileres de trajes folklóricos</p>
                </div>
                <button type="button" class="btn btn-primary btn-lg shadow" wire:click="openNewAlquilerModal">
                    <i class="fas fa-plus-circle me-2"></i>
                    Nuevo Alquiler
                </button>
            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ESTADÍSTICAS CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-2">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-list fs-4 text-primary"></i>
                        </div>
                        <span class="badge bg-primary">Total</span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $estadisticas['total'] }}</h3>
                    <p class="text-muted small mb-0">Alquileres registrados</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-2">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-play-circle fs-4 text-info"></i>
                        </div>
                        <span class="badge bg-info">En curso</span>
                    </div>
                    <h3 class="fw-bold mb-0 text-info">{{ $estadisticas['activos'] }}</h3>
                    <p class="text-muted small mb-0">Alquileres activos</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-2">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-exclamation-circle fs-4 text-danger"></i>
                        </div>
                        <span class="badge bg-danger">Urgente</span>
                    </div>
                    <h3 class="fw-bold mb-0 text-danger">{{ $estadisticas['vencidos'] }}</h3>
                    <p class="text-muted small mb-0">Alquileres vencidos</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-2">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-hourglass-half fs-4 text-warning"></i>
                        </div>
                        <span class="badge bg-warning text-dark">Hoy</span>
                    </div>
                    <h3 class="fw-bold mb-0 text-warning">{{ $estadisticas['venceHoy'] }}</h3>
                    <p class="text-muted small mb-0">Vencen hoy</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-2">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-check-double fs-4 text-success"></i>
                        </div>
                        <span class="badge bg-success">Completado</span>
                    </div>
                    <h3 class="fw-bold mb-0 text-success">{{ $estadisticas['devueltos'] }}</h3>
                    <p class="text-muted small mb-0">Devueltos</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-2">
            <div class="card border-0 shadow-sm h-100 hover-lift bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-white bg-opacity-25 rounded-3 p-3">
                            <i class="fas fa-money-bill-wave fs-4 text-white"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0">Bs. {{ number_format($estadisticas['totalIngresos'], 2) }}</h3>
                    <p class="text-white-50 small mb-0">Total en ingresos</p>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTROS SECTION --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold small text-muted mb-1">
                        <i class="fas fa-search me-1"></i>Buscar
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control border-start-0 ps-0"
                            placeholder="Buscar por contrato, cliente, CI..."
                            wire:model.debounce.300ms="searchTerm"
                        >
                    </div>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold small text-muted mb-1">
                        <i class="fas fa-filter me-1"></i>Estado
                    </label>
                    <select class="form-select" wire:model="filterEstado">
                        <option value="TODOS">Todos los estados</option>
                        <option value="ACTIVO">🔵 Activos</option>
                        <option value="VENCIDO">🔴 Vencidos</option>
                        <option value="VENCE_HOY">🟡 Vence hoy</option>
                        <option value="PROXIMO">🟠 Próximos</option>
                        <option value="DEVUELTO">🟢 Devueltos</option>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold small text-muted mb-1">
                        <i class="fas fa-money-check me-1"></i>Estado Pago
                    </label>
                    <select class="form-select" wire:model="filterEstadoPago">
                        <option value="TODOS">Todos</option>
                        <option value="PENDIENTE">Pendiente</option>
                        <option value="PARCIAL">Parcial</option>
                        <option value="PAGADO">Pagado</option>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold small text-muted mb-1">
                        <i class="fas fa-store me-1"></i>Sucursal
                    </label>
                    <select class="form-select" wire:model="filterSucursal">
                        <option value="TODAS">Todas</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex align-items-end">
                    <button class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                        <i class="fas fa-redo me-1"></i>Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA DE ALQUILERES --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-table me-2 text-primary"></i>
                    Lista de Alquileres
                    <span class="badge bg-primary ms-2">{{ $alquileres->total() }}</span>
                </h5>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary active">
                        <i class="fas fa-th-list"></i>
                    </button>
                    <button class="btn btn-outline-primary">
                        <i class="fas fa-th"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-muted fw-semibold small px-3">Contrato</th>
                            <th class="text-muted fw-semibold small">Cliente</th>
                            <th class="text-muted fw-semibold small">Fechas</th>
                            <th class="text-muted fw-semibold small">Estado</th>
                            <th class="text-muted fw-semibold small">Items</th>
                            <th class="text-muted fw-semibold small">Total</th>
                            <th class="text-muted fw-semibold small">Pago</th>
                            <th class="text-muted fw-semibold small">Garantía</th>
                            <th class="text-muted fw-semibold small text-end px-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alquileres as $alquiler)
                            <tr class="border-bottom">
                                {{-- CONTRATO --}}
                                <td class="px-3">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-primary">{{ $alquiler->numero_contrato }}</span>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ $alquiler->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </td>

                                {{-- CLIENTE --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $alquiler->cliente->nombres }} {{ $alquiler->cliente->apellidos }}</div>
                                            <small class="text-muted">
                                                <i class="fas fa-id-card me-1"></i>{{ $alquiler->cliente->carnet_identidad ?? 'S/N' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                {{-- FECHAS --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="text-muted mb-1">
                                            <i class="fas fa-calendar-check text-success me-1"></i>
                                            <strong>Inicio:</strong> {{ \Carbon\Carbon::parse($alquiler->fecha_alquiler)->format('d/m/Y') }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-times text-danger me-1"></i>
                                            <strong>Fin:</strong> {{ \Carbon\Carbon::parse($alquiler->fecha_devolucion_programada)->format('d/m/Y') }}
                                        </small>
                                        <small class="badge bg-info mt-1">{{ $alquiler->dias_alquiler }} días</small>
                                    </div>
                                </td>

                                {{-- ESTADO --}}
                                <td>
                                    @if($alquiler->estado === 'ACTIVO')
                                        <span class="badge bg-info px-3 py-2">
                                            <i class="fas fa-play-circle me-1"></i>Activo
                                        </span>
                                    @elseif($alquiler->estado === 'VENCIDO')
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Vencido
                                        </span>
                                    @elseif($alquiler->estado === 'DEVUELTO')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-double me-1"></i>Devuelto
                                        </span>
                                    @elseif($alquiler->estado === 'PARCIAL')
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            <i class="fas fa-clock me-1"></i>Parcial
                                        </span>
                                    @endif
                                </td>

                                {{-- ITEMS --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-box text-muted me-2"></i>
                                        <span class="badge bg-secondary">{{ $alquiler->detalles->count() }} items</span>
                                    </div>
                                </td>

                                {{-- TOTAL --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">Bs. {{ number_format($alquiler->total, 2) }}</span>
                                        @if($alquiler->anticipo > 0)
                                            <small class="text-success">
                                                <i class="fas fa-arrow-down me-1"></i>Anticipo: Bs. {{ number_format($alquiler->anticipo, 2) }}
                                            </small>
                                        @endif
                                        @if($alquiler->saldo_pendiente > 0)
                                            <small class="text-danger">
                                                <i class="fas fa-arrow-up me-1"></i>Saldo: Bs. {{ number_format($alquiler->saldo_pendiente, 2) }}
                                            </small>
                                        @endif
                                    </div>
                                </td>

                                {{-- ESTADO PAGO --}}
                                <td>
                                    @if($alquiler->estado_pago === 'PAGADO')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Pagado
                                        </span>
                                    @elseif($alquiler->estado_pago === 'PARCIAL')
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            <i class="fas fa-hourglass-half me-1"></i>Parcial
                                        </span>
                                    @elseif($alquiler->estado_pago === 'PENDIENTE')
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="fas fa-times-circle me-1"></i>Pendiente
                                        </span>
                                    @endif
                                </td>

                                {{-- GARANTÍA --}}
                                <td>
                                    @if($alquiler->tieneGarantia())
                                        @if($alquiler->tipo_garantia === 'CI')
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-id-card me-1"></i>CI
                                            </span>
                                        @elseif($alquiler->tipo_garantia === 'EFECTIVO')
                                            <span class="badge bg-success">
                                                <i class="fas fa-money-bill me-1"></i>Bs. {{ number_format($alquiler->monto_garantia, 2) }}
                                            </span>
                                        @elseif($alquiler->tipo_garantia === 'QR')
                                            <span class="badge bg-info">
                                                <i class="fas fa-qrcode me-1"></i>Bs. {{ number_format($alquiler->monto_garantia, 2) }}
                                            </span>
                                        @endif
                                        <br>
                                        <small class="badge bg-{{ $alquiler->estado_garantia === 'DEVUELTA' ? 'success' : 'warning' }} mt-1">
                                            {{ $alquiler->estado_garantia }}
                                        </small>
                                    @else
                                        <span class="badge bg-light text-dark">Sin garantía</span>
                                    @endif
                                </td>

                                {{-- ACCIONES --}}
                                <td class="text-end px-3">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-sm btn-outline-primary" wire:click="viewAlquiler({{ $alquiler->id }})" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        @if($alquiler->estado !== 'DEVUELTO')
                                            <button class="btn btn-sm btn-outline-success" wire:click="openDevolucionModal({{ $alquiler->id }})" title="Registrar devolución">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif

                                        @if($alquiler->estado_pago !== 'PAGADO')
                                            <button class="btn btn-sm btn-outline-warning" wire:click="openPaymentModal({{ $alquiler->id }})" title="Registrar pago">
                                                <i class="fas fa-dollar-sign"></i>
                                            </button>
                                        @endif

                                        <button class="btn btn-sm btn-outline-secondary" wire:click="printAlquiler({{ $alquiler->id }})" title="Imprimir">
                                            <i class="fas fa-print"></i>
                                        </button>

                                        <button class="btn btn-sm btn-outline-danger" onclick="confirm('¿Cancelar alquiler?') || event.stopImmediatePropagation()" wire:click="cancelAlquiler({{ $alquiler->id }})" title="Cancelar">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p class="mb-0">No se encontraron alquileres</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top">
            {{ $alquileres->links() }}
        </div>
    </div>

    {{-- MODALS --}}
    @if($showNewAlquilerModal)
        @include('livewire.alquiler.modals.nuevo-alquiler-modal')
    @endif

    @if($showViewAlquilerModal)
        @include('livewire.alquiler.modals.view-alquiler-modal')
    @endif

    @if($showDevolucionModal)
        @include('livewire.alquiler.modals.devolucion-modal')
    @endif

    @if($showPaymentModal)
        @include('livewire.alquiler.modals.payment-modal')
    @endif

    @if($showPrintModal)
        @include('livewire.alquiler.modals.print-modal')
    @endif

</div>

<style>
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.avatar-sm {
    height: 2.5rem;
    width: 2.5rem;
}
.avatar-title {
    align-items: center;
    display: flex;
    height: 100%;
    justify-content: center;
    width: 100%;
}
.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}
</style>

@push('scripts')
<script>
    window.addEventListener('swal', event => {
        Swal.fire({
            title: event.detail.title,
            text: event.detail.text,
            icon: event.detail.icon,
            confirmButtonText: 'Ok'
        });
    });
</script>
@endpush
