@php
    $cajasFiltradas = $this->getCajasFiltradas();
    $totalCajas = $cajasFiltradas->count();
    $cajasAbiertas = $cajasFiltradas->where('estado', 'ABIERTA')->count();
    $saldoTotal = $cajasFiltradas->sum('saldo_actual');
@endphp

<div>
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark">Caja</h1>
                <p class="text-muted">Gestión de cajas y movimientos</p>
            </div>
            <div class="d-flex gap-2">
                <button wire:click="abrirModalCaja" class="btn btn-primary d-flex align-items-center">
                    <i class="bi bi-plus-lg me-2"></i>
                    Crear/Abrir Caja
                </button>
                <button wire:click="abrirModalMovimiento" class="btn btn-warning d-flex align-items-center text-dark" 
                        @if($cajasAbiertas === 0) disabled @endif>
                    <i class="bi bi-cash-coin me-2"></i>
                    Nuevo Movimiento
                </button>
            </div>
        </div>

        <!-- Alertas -->
        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Resumen -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <p class="text-muted small mb-1">Total Cajas</p>
                            <h4 class="fw-bold mb-0">{{ $totalCajas }}</h4>
                        </div>
                        <i class="bi bi-wallet2 fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <p class="text-muted small mb-1">Cajas Abiertas</p>
                            <h4 class="fw-bold text-success mb-0">{{ $cajasAbiertas }}</h4>
                        </div>
                        <i class="bi bi-check-circle-fill fs-2 text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between">
                        <div>
                            <p class="text-muted small mb-1">Saldo Total en Cajas</p>
                            <h4 class="fw-bold text-purple mb-0">Bs. {{ number_format($saldoTotal, 2) }}</h4>
                        </div>
                        <i class="bi bi-cash-stack fs-2 text-purple"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="position-relative">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" wire:model.live="busqueda" class="form-control ps-5" placeholder="Buscar caja o movimiento..." />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="filtroEstado" class="form-select">
                            <option value="">Estado</option>
                            <option value="ABIERTA">Abierta</option>
                            <option value="CERRADA">Cerrada</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select wire:model.live="filtroSucursal" class="form-select">
                            <option value="">Todas las Sucursales</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button wire:click="generarReporte" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-file-text me-1"></i>Reporte
                            </button>
                            <button wire:click="exportarPDF" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-file-pdf me-1"></i>PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Cajas -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Cajas ({{ $cajasFiltradas->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @forelse($cajasFiltradas as $caja)
                            <div class="card border-0 shadow-sm mb-3 {{ $caja->estado === 'ABIERTA' ? 'border-start border-success border-3' : '' }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="fw-bold mb-1 d-flex align-items-center">
                                                @if($caja->es_caja_principal)
                                                    <i class="bi bi-star-fill text-warning me-2"></i>
                                                @endif
                                                {{ $caja->nombre }}
                                            </h6>
                                            <small class="text-muted">
                                                <i class="bi bi-building me-1"></i>{{ $caja->sucursal->nombre }}
                                                @if($caja->es_caja_principal)
                                                    <span class="badge bg-warning text-dark ms-2">Principal</span>
                                                @endif
                                            </small>
                                        </div>
                                        <span class="badge {{ $caja->estado === 'ABIERTA' ? 'bg-success' : 'bg-secondary' }} fs-6">
                                            {{ ucfirst(strtolower($caja->estado)) }}
                                        </span>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <div class="text-center p-2 bg-light rounded">
                                                <div class="fw-bold text-muted small">Saldo Inicial</div>
                                                <div class="h6 mb-0">Bs. {{ number_format($caja->saldo_inicial, 2) }}</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center p-2 {{ $caja->estado === 'ABIERTA' ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-light' }} rounded">
                                                <div class="fw-bold {{ $caja->estado === 'ABIERTA' ? 'text-success' : 'text-muted' }} small">Saldo Actual</div>
                                                <div class="h5 mb-0 {{ $caja->estado === 'ABIERTA' ? 'text-success' : '' }}">Bs. {{ number_format($caja->saldo_actual, 2) }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($caja->estado === 'ABIERTA')
                                        <div class="alert alert-success alert-sm py-2 mb-3">
                                            <div class="row small">
                                                <div class="col">
                                                    <i class="bi bi-clock me-1"></i>
                                                    <strong>Abierta:</strong> {{ $caja->fecha_apertura->format('d/m H:i') }}
                                                </div>
                                                <div class="col">
                                                    <i class="bi bi-person me-1"></i>
                                                    <strong>Por:</strong> {{ $caja->usuarioApertura->name }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($caja->fecha_cierre)
                                        <div class="alert alert-secondary alert-sm py-2 mb-3">
                                            <div class="row small">
                                                <div class="col">
                                                    <i class="bi bi-x-circle me-1"></i>
                                                    <strong>Cerrada:</strong> {{ $caja->fecha_cierre->format('d/m H:i') }}
                                                </div>
                                                @if($caja->usuarioCierre)
                                                    <div class="col">
                                                        <i class="bi bi-person me-1"></i>
                                                        <strong>Por:</strong> {{ $caja->usuarioCierre->name }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-between gap-2">
                                        <button wire:click="verMovimientos({{ $caja->id }})" class="btn btn-outline-info btn-sm">
                                            <i class="bi bi-list-ul me-1"></i>Movimientos
                                        </button>
                                        <div class="d-flex gap-1">
                                            @if($caja->estado === 'ABIERTA')
                                                <button wire:click="verResumenCaja({{ $caja->id }})" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-graph-up me-1"></i>Resumen
                                                </button>
                                                <button wire:click="abrirModalCerrarCaja({{ $caja->id }})" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-lock me-1"></i>Cerrar
                                                </button>
                                            @else
                                                <button wire:click="abrirModalAbrirCaja({{ $caja->id }})" class="btn btn-success btn-sm">
                                                    <i class="bi bi-unlock me-1"></i>Abrir
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-inbox display-1 text-muted"></i>
                                <p class="text-muted">No hay cajas disponibles</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Movimientos recientes -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-cash-coin"></i>
                        <h5 class="mb-0">Movimientos Recientes</h5>
                    </div>
                    <div class="card-body">
                        @forelse($movimientosRecientes as $movimiento)
                            <div class="border rounded p-2 mb-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <div>
                                        <p class="fw-semibold mb-0">{{ $movimiento->concepto }}</p>
                                        <small class="text-muted">Caja: {{ $movimiento->caja->nombre }}</small>
                                    </div>
                                    <span class="badge {{ $movimiento->tipo === 'INGRESO' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst(strtolower($movimiento->tipo)) }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <strong>Bs. {{ number_format($movimiento->monto, 2) }}</strong>
                                    <span>{{ $movimiento->fecha_movimiento->format('d/m/Y H:i') }}</span>
                                </div>
                                <small class="text-muted">
                                    Registrado por: {{ $movimiento->usuarioRegistro->name }}
                                </small>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-receipt display-1 text-muted"></i>
                                <p class="text-muted">No hay movimientos recientes</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Caja -->
    @if($mostrarModalCaja)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @if($cajaSeleccionada) Editar Caja @else Nueva Caja @endif
                        </h5>
                        <button type="button" class="btn-close" wire:click="cerrarModalCaja"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="guardarCaja">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre *</label>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                               wire:model="nombre" placeholder="Caja Principal">
                                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Sucursal *</label>
                                        <select class="form-select @error('sucursal_id') is-invalid @enderror" 
                                                wire:model="sucursal_id">
                                            <option value="">Seleccione sucursal</option>
                                            @foreach($sucursales as $sucursal)
                                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('sucursal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control" wire:model="descripcion" 
                                          placeholder="Descripción de la caja" rows="2"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Saldo Inicial *</label>
                                        <input type="number" step="0.01" min="0" 
                                               class="form-control @error('saldo_inicial') is-invalid @enderror" 
                                               wire:model="saldo_inicial" placeholder="0.00">
                                        @error('saldo_inicial') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" wire:model="es_caja_principal" id="esCajaPrincipal">
                                        <label class="form-check-label" for="esCajaPrincipal">
                                            Es caja principal
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Observaciones de Apertura</label>
                                <textarea class="form-control" wire:model="observaciones_apertura" 
                                          placeholder="Observaciones iniciales" rows="2"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModalCaja">Cancelar</button>
                        <button type="button" class="btn btn-primary" wire:click="guardarCaja">
                            @if($cajaSeleccionada) Actualizar @else Crear y Abrir @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Movimiento -->
    @if($mostrarModalMovimiento)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo Movimiento</h5>
                        <button type="button" class="btn-close" wire:click="cerrarModalMovimiento"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="guardarMovimiento">
                            <div class="mb-3">
                                <label class="form-label">Caja *</label>
                                <select class="form-select @error('movimiento_caja_id') is-invalid @enderror" 
                                        wire:model="movimiento_caja_id">
                                    <option value="">Seleccione caja</option>
                                    @foreach($cajas->where('estado', 'ABIERTA') as $caja)
                                        <option value="{{ $caja->id }}">{{ $caja->nombre }} - Bs. {{ number_format($caja->saldo_actual, 2) }}</option>
                                    @endforeach
                                </select>
                                @error('movimiento_caja_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipo *</label>
                                        <select class="form-select @error('tipo') is-invalid @enderror" 
                                                wire:model="tipo" wire:change="actualizarCategorias">
                                            <option value="">Seleccione tipo</option>
                                            <option value="INGRESO">Ingreso</option>
                                            <option value="EGRESO">Egreso</option>
                                        </select>
                                        @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Categoría *</label>
                                        <select class="form-select @error('categoria') is-invalid @enderror" 
                                                wire:model="categoria">
                                            <option value="">Seleccione categoría</option>
                                            @foreach($categoriasDisponibles as $valor => $etiqueta)
                                                <option value="{{ $valor }}">{{ $etiqueta }}</option>
                                            @endforeach
                                        </select>
                                        @error('categoria') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Monto *</label>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('monto') is-invalid @enderror" 
                                       wire:model="monto" placeholder="0.00">
                                @error('monto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Concepto *</label>
                                <input type="text" class="form-control @error('concepto') is-invalid @enderror" 
                                       wire:model="concepto" placeholder="Descripción del movimiento">
                                @error('concepto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Método de Pago</label>
                                <select class="form-select" wire:model="metodo_pago">
                                    <option value="EFECTIVO">Efectivo</option>
                                    <option value="TRANSFERENCIA">Transferencia</option>
                                    <option value="TARJETA">Tarjeta</option>
                                    <option value="CHEQUE">Cheque</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Referencia</label>
                                <input type="text" class="form-control" wire:model="referencia" 
                                       placeholder="Número de factura, recibo, etc.">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Observaciones</label>
                                <textarea class="form-control" wire:model="observaciones" 
                                          placeholder="Observaciones adicionales" rows="2"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModalMovimiento">Cancelar</button>
                        <button type="button" class="btn btn-primary" wire:click="guardarMovimiento">
                            Registrar Movimiento
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Cerrar Caja -->
    @if($mostrarModalCerrarCaja)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cerrar Caja</h5>
                        <button type="button" class="btn-close" wire:click="cerrarModalCerrarCaja"></button>
                    </div>
                    <div class="modal-body">
                        @if($cajaParaCerrar)
                            <div class="alert alert-info">
                                <h6>{{ $cajaParaCerrar->nombre }}</h6>
                                <p class="mb-1">Saldo Sistema: Bs. {{ number_format($cajaParaCerrar->saldo_actual, 2) }}</p>
                                <p class="mb-0">Fecha Apertura: {{ $cajaParaCerrar->fecha_apertura?->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                        
                        <form wire:submit.prevent="cerrarCaja">
                            <div class="mb-3">
                                <label class="form-label">Arqueo Físico *</label>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('arqueo_fisico') is-invalid @enderror" 
                                       wire:model.live="arqueo_fisico" placeholder="0.00">
                                @error('arqueo_fisico') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            @if($arqueo_fisico && $cajaParaCerrar)
                                @php $diferencia = $arqueo_fisico - $cajaParaCerrar->saldo_actual; @endphp
                                <div class="alert {{ $diferencia == 0 ? 'alert-success' : ($diferencia > 0 ? 'alert-warning' : 'alert-danger') }}">
                                    <strong>Diferencia: Bs. {{ number_format($diferencia, 2) }}</strong>
                                    @if($diferencia > 0) (Sobrante) @elseif($diferencia < 0) (Faltante) @else (Cuadrado) @endif
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <label class="form-label">Observaciones de Cierre</label>
                                <textarea class="form-control" wire:model="observaciones_cierre" 
                                          placeholder="Observaciones del cierre" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModalCerrarCaja">Cancelar</button>
                        <button type="button" class="btn btn-danger" wire:click="cerrarCaja">
                            Cerrar Caja
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Movimientos -->
    @if($mostrarModalMovimientos)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Movimientos - {{ $cajaSeleccionada?->nombre }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="cerrarModalMovimientos"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Concepto</th>
                                        <th>Monto</th>
                                        <th>Saldo</th>
                                        <th>Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($movimientosCaja as $movimiento)
                                        <tr>
                                            <td>{{ $movimiento->fecha_movimiento->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge {{ $movimiento->tipo === 'INGRESO' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $movimiento->tipo }}
                                                </span>
                                            </td>
                                            <td>{{ $movimiento->concepto }}</td>
                                            <td class="{{ $movimiento->tipo === 'INGRESO' ? 'text-success' : 'text-danger' }}">
                                                {{ $movimiento->tipo === 'INGRESO' ? '+' : '-' }} Bs. {{ number_format($movimiento->monto, 2) }}
                                            </td>
                                            <td>Bs. {{ number_format($movimiento->saldo_posterior, 2) }}</td>
                                            <td>{{ $movimiento->usuarioRegistro->name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No hay movimientos registrados</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Resumen de Caja -->
    @if($mostrarModalResumen && $cajaSeleccionada)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-graph-up me-2"></i>Resumen de Caja - {{ $cajaSeleccionada->nombre }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="cerrarModalResumen"></button>
                    </div>
                    <div class="modal-body">
                        @if($resumenCaja)
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-success bg-opacity-10 border border-success border-opacity-25">
                                        <div class="card-body text-center">
                                            <i class="bi bi-arrow-down-circle text-success fs-2"></i>
                                            <h4 class="text-success fw-bold">{{ $resumenCaja['cantidad_ingresos'] }}</h4>
                                            <p class="text-success mb-1">Ingresos</p>
                                            <h5 class="text-success fw-bold">Bs. {{ number_format($resumenCaja['ingresos_total'], 2) }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-danger bg-opacity-10 border border-danger border-opacity-25">
                                        <div class="card-body text-center">
                                            <i class="bi bi-arrow-up-circle text-danger fs-2"></i>
                                            <h4 class="text-danger fw-bold">{{ $resumenCaja['cantidad_egresos'] }}</h4>
                                            <p class="text-danger mb-1">Egresos</p>
                                            <h5 class="text-danger fw-bold">Bs. {{ number_format($resumenCaja['egresos_total'], 2) }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-info bg-opacity-10 border border-info border-opacity-25">
                                        <div class="card-body text-center">
                                            <i class="bi bi-wallet2 text-info fs-2"></i>
                                            <h4 class="text-info fw-bold">Balance</h4>
                                            <p class="text-info mb-1">Neto del Día</p>
                                            <h5 class="text-info fw-bold">
                                                Bs. {{ number_format($resumenCaja['ingresos_total'] - $resumenCaja['egresos_total'], 2) }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bi bi-info-circle me-2"></i>Información General
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Saldo Inicial:</strong></td>
                                                    <td>Bs. {{ number_format($resumenCaja['saldo_inicial'], 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Saldo Actual:</strong></td>
                                                    <td class="text-success fw-bold">Bs. {{ number_format($resumenCaja['saldo_actual'], 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Arqueo Sistema:</strong></td>
                                                    <td>Bs. {{ number_format($resumenCaja['arqueo_sistema'], 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Apertura:</strong></td>
                                                    <td>{{ $cajaSeleccionada->fecha_apertura->format('d/m/Y H:i') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Usuario:</strong></td>
                                                    <td>{{ $cajaSeleccionada->usuarioApertura->name }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bi bi-pie-chart me-2"></i>Últimos Movimientos
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div style="max-height: 250px; overflow-y: auto;">
                                                @forelse($cajaSeleccionada->movimientos->take(5) as $movimiento)
                                                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                                        <div>
                                                            <div class="fw-semibold small">{{ $movimiento->concepto }}</div>
                                                            <div class="text-muted" style="font-size: 0.8rem;">{{ $movimiento->fecha_movimiento->format('H:i') }}</div>
                                                        </div>
                                                        <div class="text-end">
                                                            <span class="badge {{ $movimiento->tipo === 'INGRESO' ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $movimiento->tipo === 'INGRESO' ? '+' : '-' }} Bs. {{ number_format($movimiento->monto, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-muted text-center">No hay movimientos</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModalResumen">Cerrar</button>
                        <button type="button" class="btn btn-primary" wire:click="exportarResumenPDF">
                            <i class="bi bi-file-pdf me-1"></i>Exportar PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Abrir Caja -->
    @if($mostrarModalAbrirCaja)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-unlock me-2"></i>Abrir Caja
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="cerrarModalAbrirCaja"></button>
                    </div>
                    <div class="modal-body">
                        @if($cajaParaAbrir)
                            <div class="alert alert-info">
                                <h6><i class="bi bi-info-circle me-2"></i>{{ $cajaParaAbrir->nombre }}</h6>
                                <p class="mb-1">Sucursal: {{ $cajaParaAbrir->sucursal->nombre }}</p>
                                @if($cajaParaAbrir->fecha_cierre)
                                    <p class="mb-0">Última vez cerrada: {{ $cajaParaAbrir->fecha_cierre->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>
                        @endif

                        <form wire:submit.prevent="abrirCaja">
                            <div class="mb-3">
                                <label class="form-label">Saldo Inicial *</label>
                                <input type="number" step="0.01" min="0"
                                       class="form-control @error('saldo_apertura') is-invalid @enderror"
                                       wire:model.live="saldo_apertura" placeholder="0.00">
                                @error('saldo_apertura') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Observaciones de Apertura</label>
                                <textarea class="form-control" wire:model="observaciones_apertura_nueva"
                                          placeholder="Observaciones iniciales" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModalAbrirCaja">Cancelar</button>
                        <button type="button" class="btn btn-success" wire:click="confirmarAbrirCaja">
                            <i class="bi bi-unlock me-1"></i>Abrir Caja
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
</div>
