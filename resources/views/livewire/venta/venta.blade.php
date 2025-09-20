@php
    $totalVentas = $ventas->total();
    $ventasCompletadas = $resumenHoy['completadas'] ?? 0;
    $ventasPendientes = $resumenHoy['pendientes'] ?? 0;
    $ingresosTotales = $resumenHoy['monto_pagado'] ?? 0;
@endphp

<div>
    <div class="container-fluid py-4">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark">Ventas</h1>
                <p class="text-muted">Gestión de ventas y facturación</p>
            </div>
            <button wire:click="abrirModalVenta" class="btn btn-warning text-dark d-flex align-items-center">
                <i class="bi bi-plus me-2"></i>
                Nueva Venta
            </button>
        </div>

        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Cards métricas -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">Total Ventas Hoy</p>
                            <h4 class="fw-bold mb-0">{{ $resumenHoy['total_ventas'] ?? 0 }}</h4>
                        </div>
                        <i class="bi bi-cart fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">Ventas Completadas</p>
                            <h4 class="fw-bold text-success mb-0">{{ $ventasCompletadas }}</h4>
                        </div>
                        <i class="bi bi-check-circle fs-2 text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">Ventas Pendientes</p>
                            <h4 class="fw-bold text-warning mb-0">{{ $ventasPendientes }}</h4>
                        </div>
                        <i class="bi bi-clock fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">Ingresos Hoy</p>
                            <h4 class="fw-bold text-purple mb-0">Bs. {{ number_format($ingresosTotales, 2) }}</h4>
                        </div>
                        <i class="bi bi-currency-dollar fs-2 text-purple"></i>
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
                            <input type="text" wire:model.live="busqueda" class="form-control ps-5" 
                                   placeholder="Buscar por número o cliente..." />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="filtroEstado" class="form-select">
                            <option value="">Estado</option>
                            <option value="PENDIENTE">Pendiente</option>
                            <option value="COMPLETADA">Completada</option>
                            <option value="CANCELADA">Cancelada</option>
                            <option value="DEVUELTA">Devuelta</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="filtroEstadoPago" class="form-select">
                            <option value="">Pago</option>
                            <option value="PENDIENTE">Pendiente</option>
                            <option value="PARCIAL">Parcial</option>
                            <option value="PAGADO">Pagado</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" wire:model.live="fechaDesde" class="form-control" />
                    </div>
                    <div class="col-md-2">
                        <input type="date" wire:model.live="fechaHasta" class="form-control" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla Ventas -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Ventas ({{ $ventas->total() }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Número</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Pago</th>
                                <th>Total</th>
                                <th>Saldo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ventas as $venta)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $venta->numero_venta }}</div>
                                        <small class="text-muted">{{ $venta->sucursal->nombre }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $venta->cliente->nombre }}</div>
                                        <small class="text-muted">{{ $venta->cliente->telefono ?? 'Sin teléfono' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $venta->fecha_venta->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $venta->fecha_venta->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $venta->estado_badge_class }}">
                                            {{ $venta->estado_display }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $venta->estado_pago_badge_class }}">
                                            {{ $venta->estado_pago_display }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">Bs. {{ number_format($venta->total, 2) }}</td>
                                    <td>
                                        @if($venta->saldo_pendiente > 0)
                                            <span class="text-danger fw-semibold">
                                                Bs. {{ number_format($venta->saldo_pendiente, 2) }}
                                            </span>
                                        @else
                                            <span class="text-success">Pagado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button wire:click="verDetalle({{ $venta->id }})" 
                                                    class="btn btn-outline-primary btn-sm" title="Ver detalle">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            
                                            <button wire:click="verComprobante({{ $venta->id }})" 
                                                    class="btn btn-outline-info btn-sm" title="Ver comprobante">
                                                <i class="bi bi-receipt"></i>
                                            </button>
                                            
                                            @if($venta->estado === 'PENDIENTE')
                                                <button wire:click="abrirModalVenta({{ $venta->id }})" 
                                                        class="btn btn-outline-secondary btn-sm" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            @endif
                                            
                                            @if($venta->saldo_pendiente > 0)
                                                <button wire:click="abrirModalPago({{ $venta->id }})" 
                                                        class="btn btn-outline-success btn-sm" title="Procesar pago">
                                                    <i class="bi bi-credit-card"></i>
                                                </button>
                                            @endif
                                            
                                            @if($venta->estado === 'PENDIENTE')
                                                <button wire:click="completarVenta({{ $venta->id }})" 
                                                        onclick="return confirm('¿Completar esta venta?')"
                                                        class="btn btn-outline-success btn-sm" title="Completar">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                                
                                                <button wire:click="cancelarVenta({{ $venta->id }})" 
                                                        onclick="return confirm('¿Cancelar esta venta?')"
                                                        class="btn btn-outline-danger btn-sm" title="Cancelar">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bi bi-inbox display-1 text-muted"></i>
                                        <p class="text-muted">No hay ventas registradas</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($ventas->hasPages())
                <div class="card-footer">
                    {{ $ventas->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Nueva/Editar Venta -->
    @if($mostrarModalVenta)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @if($ventaSeleccionada) Editar Venta @else Nueva Venta @endif
                        </h5>
                        <button type="button" class="btn-close" wire:click="cerrarModalVenta" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Complete los detalles para registrar una nueva venta.</p>

                        <div class="row">
                            <!-- Columna izquierda -->
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Detalles de la Venta</h6>
                                
                                <div class="mb-3 row">
                                    <label for="cliente_id" class="col-sm-4 col-form-label text-end">Cliente</label>
                                    <div class="col-sm-8">
                                        <select wire:model="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" id="cliente_id">
                                            <option value="">Seleccione cliente</option>
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="metodo_pago" class="col-sm-4 col-form-label text-end">Tipo de Pago</label>
                                    <div class="col-sm-8">
                                        <select wire:model="metodo_pago" class="form-select" id="metodo_pago">
                                            <option value="QR">QR</option>
                                            <option value="EFECTIVO">Efectivo</option>
                                            <option value="TARJETA">Tarjeta</option>
                                            <option value="TRANSFERENCIA">Transferencia</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="sucursal_id" class="col-sm-4 col-form-label text-end">Sucursal</label>
                                    <div class="col-sm-8">
                                        <select wire:model="sucursal_id" class="form-select @error('sucursal_id') is-invalid @enderror" id="sucursal_id">
                                            <option value="">Seleccione sucursal</option>
                                            @foreach($sucursales as $sucursal)
                                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('sucursal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="fecha_venta" class="col-sm-4 col-form-label text-end">Fecha Venta</label>
                                    <div class="col-sm-8">
                                        <input type="date" wire:model="fecha_venta" class="form-control @error('fecha_venta') is-invalid @enderror" id="fecha_venta">
                                        @error('fecha_venta') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="observaciones" class="col-sm-4 col-form-label text-end">Observaciones</label>
                                    <div class="col-sm-8">
                                        <input type="text" wire:model="observaciones" class="form-control" id="observaciones" placeholder="Opcional">
                                    </div>
                                </div>

                                <!-- Campos de pago y caja -->
                                <div class="mb-3 row">
                                    <label for="pago_inicial" class="col-sm-4 col-form-label text-end">Pago Inicial</label>
                                    <div class="col-sm-8">
                                        <input type="number" step="0.01" wire:model.live="pago_inicial"
                                               class="form-control @error('pago_inicial') is-invalid @enderror"
                                               id="pago_inicial" placeholder="0.00">
                                        @error('pago_inicial') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <small class="text-muted">Si ingresa un monto, se registrará automáticamente en la caja seleccionada</small>
                                    </div>
                                </div>

                                @if($pago_inicial > 0)
                                    <div class="card border-success mb-3">
                                        <div class="card-header bg-success text-white">
                                            <i class="fas fa-cash-register me-2"></i>Registro de Pago Inicial
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-warning mb-3">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Pago Inicial:</strong> Bs. {{ number_format($pago_inicial, 2) }} se registrará automáticamente en la caja seleccionada.
                                            </div>

                                            <div class="mb-3">
                                                <label for="caja_id" class="form-label">Caja de Destino *</label>
                                                <select wire:model="caja_id" class="form-select @error('caja_id') is-invalid @enderror" id="caja_id">
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
                                                    $cajaSeleccionadaVenta = $cajas->find($caja_id);
                                                @endphp
                                                @if($cajaSeleccionadaVenta)
                                                    <div class="alert alert-info mb-0">
                                                        <i class="fas fa-check-circle me-2"></i>
                                                        <strong>Destino Confirmado:</strong> {{ $cajaSeleccionadaVenta->nombre }}<br>
                                                        <small>
                                                            📍 Sucursal: {{ $cajaSeleccionadaVenta->sucursal->nombre }} |
                                                            💰 Saldo Actual: Bs. {{ number_format($cajaSeleccionadaVenta->saldo_actual, 2) }} |
                                                            🔄 Nuevo Saldo: Bs. {{ number_format($cajaSeleccionadaVenta->saldo_actual + $pago_inicial, 2) }}
                                                            @if($cajaSeleccionadaVenta->es_caja_principal) | ⭐ Caja Principal @endif
                                                        </small>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Columna derecha -->
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Productos a Vender</h6>

                                <div class="d-flex gap-2 mb-3">
                                    <select wire:model.live="productoSeleccionado" class="form-select flex-grow-1">
                                        <option value="">Seleccione un producto</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->id }}">{{ $producto->nombre }} - Bs. {{ $producto->precio_venta }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" wire:model="cantidadProducto" class="form-control w-25" min="1" placeholder="Cant.">
                                    <button type="button" wire:click="agregarProducto" class="btn btn-primary" @if(!$productoSeleccionado) disabled @endif>Añadir</button>
                                </div>

                                <div class="border rounded p-2 mb-3" style="max-height: 180px; overflow-y: auto;">
                                    @if(count($productosEnVenta) > 0)
                                        @foreach($productosEnVenta as $index => $producto)
                                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                                <div class="flex-grow-1">
                                                    <div class="fw-medium">{{ $producto['nombre'] }}</div>
                                                    <small class="text-muted">{{ $producto['cantidad'] }} x Bs. {{ number_format($producto['precio_unitario'], 2) }}</small>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="fw-bold">Bs. {{ number_format($producto['subtotal'], 2) }}</span>
                                                    <button type="button" wire:click="eliminarProducto({{ $index }})" class="btn btn-outline-danger btn-sm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted mb-0">No hay productos añadidos.</p>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between border-top pt-2 fw-bold">
                                    <span>Total:</span>
                                    <span>Bs. {{ number_format($this->calcularTotalVenta(), 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModalVenta">Cancelar</button>
                        <button type="button" class="btn btn-warning text-dark" wire:click="guardarVenta">
                            @if($ventaSeleccionada) Actualizar @else Registrar @endif Venta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Detalle Venta -->
    @if($mostrarModalDetalle && $ventaSeleccionada)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalle de Venta {{ $ventaSeleccionada->numero_venta }}</h5>
                        <button type="button" class="btn-close" wire:click="cerrarModalDetalle"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Cliente:</strong> {{ $ventaSeleccionada->cliente->nombre }}<br>
                                <strong>Sucursal:</strong> {{ $ventaSeleccionada->sucursal->nombre }}<br>
                                <strong>Usuario:</strong> {{ $ventaSeleccionada->usuario->name }}
                            </div>
                            <div class="col-md-6">
                                <strong>Fecha:</strong> {{ $ventaSeleccionada->fecha_venta->format('d/m/Y H:i') }}<br>
                                <strong>Estado:</strong> <span class="badge {{ $ventaSeleccionada->estado_badge_class }}">{{ $ventaSeleccionada->estado_display }}</span><br>
                                <strong>Pago:</strong> <span class="badge {{ $ventaSeleccionada->estado_pago_badge_class }}">{{ $ventaSeleccionada->estado_pago_display }}</span>
                            </div>
                        </div>

                        <h6>Productos:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ventaSeleccionada->detalles as $detalle)
                                        <tr>
                                            <td>{{ $detalle->nombre_producto }}</td>
                                            <td>{{ $detalle->cantidad }}</td>
                                            <td>Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                                            <td>Bs. {{ number_format($detalle->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"><strong>Subtotal:</strong></td>
                                        <td><strong>Bs. {{ number_format($ventaSeleccionada->subtotal, 2) }}</strong></td>
                                    </tr>
                                    @if($ventaSeleccionada->descuento > 0)
                                        <tr>
                                            <td colspan="3">Descuento:</td>
                                            <td>Bs. {{ number_format($ventaSeleccionada->descuento, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="3"><strong>Total:</strong></td>
                                        <td><strong>Bs. {{ number_format($ventaSeleccionada->total, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">Pagado:</td>
                                        <td>Bs. {{ number_format($ventaSeleccionada->monto_pagado, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">Saldo:</td>
                                        <td class="{{ $ventaSeleccionada->saldo_pendiente > 0 ? 'text-danger' : 'text-success' }}">
                                            Bs. {{ number_format($ventaSeleccionada->saldo_pendiente, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Procesar Pago -->
    @if($mostrarModalPago && $ventaSeleccionada)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Procesar Pago - {{ $ventaSeleccionada->numero_venta }}</h5>
                        <button type="button" class="btn-close" wire:click="cerrarModalPago"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Saldo Pendiente:</strong> Bs. {{ number_format($ventaSeleccionada->saldo_pendiente, 2) }}
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Monto a Pagar *</label>
                            <input type="number" step="0.01" wire:model="montoPago" 
                                   class="form-control @error('montoPago') is-invalid @enderror">
                            @error('montoPago') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="card border-primary mb-3">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-cash-register me-2"></i>Destino del Pago
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Caja de Destino *</label>
                                            <select wire:model="cajaParaPago" class="form-select @error('cajaParaPago') is-invalid @enderror">
                                                <option value="">Seleccione caja</option>
                                                @foreach($cajas as $caja)
                                                    <option value="{{ $caja->id }}">
                                                        🏪 {{ $caja->nombre }} - {{ $caja->sucursal->nombre }}
                                                        💰 Saldo: Bs. {{ number_format($caja->saldo_actual, 2) }}
                                                        @if($caja->es_caja_principal) ⭐ @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('cajaParaPago') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                                            <select wire:model="metodoPagoVenta" class="form-select">
                                                <option value="EFECTIVO">💵 Efectivo</option>
                                                <option value="QR">📱 QR</option>
                                                <option value="TARJETA">💳 Tarjeta</option>
                                                <option value="TRANSFERENCIA">🏦 Transferencia</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                @if($cajaParaPago)
                                    @php
                                        $cajaSeleccionadaPagoVenta = $cajas->find($cajaParaPago);
                                    @endphp
                                    @if($cajaSeleccionadaPagoVenta)
                                        <div class="alert alert-success mb-0">
                                            <i class="fas fa-calculator me-2"></i>
                                            <strong>Resumen del Pago:</strong><br>
                                            <small>
                                                💰 Saldo Actual: Bs. {{ number_format($cajaSeleccionadaPagoVenta->saldo_actual, 2) }}<br>
                                                ➕ Pago a Registrar: Bs. {{ number_format($montoPago, 2) }}<br>
                                                🔄 <strong>Nuevo Saldo: Bs. {{ number_format($cajaSeleccionadaPagoVenta->saldo_actual + $montoPago, 2) }}</strong><br>
                                                📉 Saldo Pendiente Cliente: Bs. {{ number_format($ventaSeleccionada->saldo_pendiente - $montoPago, 2) }}
                                            </small>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModalPago">Cancelar</button>
                        <button type="button" class="btn btn-success" wire:click="procesarPago">
                            Procesar Pago
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Comprobante de Venta -->
    @if($mostrarModalComprobante && $ventaSeleccionada)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Comprobante de Venta</h5>
                        <button type="button" class="btn-close" wire:click="cerrarModalComprobante"></button>
                    </div>
                    <div class="modal-body p-0">
                        <!-- Comprobante de Venta para Impresora Térmica -->
                        <div id="comprobante-venta" class="p-3" style="background: white; font-family: 'Courier New', monospace; width: 300px; margin: 0 auto; font-size: 12px; line-height: 1.2;">
                            <!-- Header Compacto -->
                            <div class="text-center mb-2" style="border-bottom: 1px dashed #000; padding-bottom: 8px;">
                                <div style="font-weight: bold; font-size: 14px; margin-bottom: 2px;">FOLKLORE BOLIVIA</div>
                                <div style="font-size: 10px; margin-bottom: 1px;">Sistema de Ventas</div>
                                <div style="font-size: 10px; margin-bottom: 4px;">Trajes Típicos y Accesorios</div>
                                <div style="font-weight: bold; font-size: 12px; margin-bottom: 2px;">COMPROBANTE DE VENTA</div>
                                <div style="font-weight: bold; font-size: 11px;">Nº {{ $ventaSeleccionada->numero_venta }}</div>
                            </div>

                            <!-- Información Básica -->
                            <div class="mb-2" style="border-bottom: 1px dashed #000; padding-bottom: 6px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                                    <span style="font-weight: bold;">FECHA:</span>
                                    <span>{{ $ventaSeleccionada->fecha_venta->format('d/m/Y H:i') }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                                    <span style="font-weight: bold;">SUCURSAL:</span>
                                    <span>{{ $ventaSeleccionada->sucursal->nombre }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                                    <span style="font-weight: bold;">VENDEDOR:</span>
                                    <span>{{ $ventaSeleccionada->usuario->name }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="font-weight: bold;">ESTADO:</span>
                                    <span>{{ $ventaSeleccionada->estado_display }}</span>
                                </div>
                            </div>

                            <!-- Datos del Cliente -->
                            <div class="mb-2" style="border-bottom: 1px dashed #000; padding-bottom: 6px;">
                                <div class="text-center" style="font-weight: bold; margin-bottom: 4px; font-size: 11px;">DATOS DEL CLIENTE</div>
                                <div style="margin-bottom: 2px;">
                                    <span style="font-weight: bold;">NOMBRE:</span><br>
                                    <span>{{ $ventaSeleccionada->cliente->nombre }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <div style="width: 48%;">
                                        <span style="font-weight: bold; font-size: 10px;">TEL:</span><br>
                                        <span style="font-size: 10px;">{{ $ventaSeleccionada->cliente->telefono ?? 'N/A' }}</span>
                                    </div>
                                    <div style="width: 48%;">
                                        <span style="font-weight: bold; font-size: 10px;">CI/NIT:</span><br>
                                        <span style="font-size: 10px;">{{ $ventaSeleccionada->cliente->carnet_identidad ?? $ventaSeleccionada->cliente->nit ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Productos -->
                            <div class="mb-2" style="border-bottom: 1px dashed #000; padding-bottom: 6px;">
                                <div class="text-center" style="font-weight: bold; margin-bottom: 4px; font-size: 11px;">PRODUCTOS VENDIDOS</div>
                                @foreach($ventaSeleccionada->detalles as $detalle)
                                    <div style="margin-bottom: 4px;">
                                        <div style="font-weight: bold; font-size: 11px;">{{ $detalle->nombre_producto }}</div>
                                        @if($detalle->codigo_producto)
                                            <div style="font-size: 9px; color: #666;">Código: {{ $detalle->codigo_producto }}</div>
                                        @endif
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div style="font-size: 10px;">
                                                {{ $detalle->cantidad }} x Bs. {{ number_format($detalle->precio_unitario, 2) }}
                                                @if($detalle->descuento_unitario > 0)
                                                    <br><span style="color: #dc3545;">Desc: Bs. {{ number_format($detalle->descuento_unitario, 2) }}</span>
                                                @endif
                                            </div>
                                            <div style="font-weight: bold;">Bs. {{ number_format($detalle->subtotal, 2) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Totales -->
                            <div class="mb-2" style="border-bottom: 1px dashed #000; padding-bottom: 6px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                                    <span style="font-weight: bold;">SUBTOTAL:</span>
                                    <span style="font-weight: bold;">Bs. {{ number_format($ventaSeleccionada->subtotal, 2) }}</span>
                                </div>
                                @if($ventaSeleccionada->descuento > 0)
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 2px; color: #dc3545;">
                                        <span>DESCUENTO:</span>
                                        <span>Bs. {{ number_format($ventaSeleccionada->descuento, 2) }}</span>
                                    </div>
                                @endif
                                @if($ventaSeleccionada->impuestos > 0)
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                                        <span>IMPUESTOS:</span>
                                        <span>Bs. {{ number_format($ventaSeleccionada->impuestos, 2) }}</span>
                                    </div>
                                @endif
                                <div style="border-top: 2px solid #000; padding-top: 4px; margin-top: 4px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 2px; font-weight: bold; font-size: 14px;">
                                        <span>TOTAL:</span>
                                        <span>Bs. {{ number_format($ventaSeleccionada->total, 2) }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 2px; color: #198754; font-weight: bold;">
                                        <span>EFECTIVO:</span>
                                        <span>Bs. {{ number_format($ventaSeleccionada->monto_pagado, 2) }}</span>
                                    </div>
                                    @if($ventaSeleccionada->saldo_pendiente > 0)
                                        <div style="display: flex; justify-content: space-between; color: #dc3545; font-weight: bold;">
                                            <span>SALDO:</span>
                                            <span>Bs. {{ number_format($ventaSeleccionada->saldo_pendiente, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Observaciones -->
                            @if($ventaSeleccionada->observaciones)
                                <div class="mb-2" style="border-bottom: 1px dashed #000; padding-bottom: 6px;">
                                    <div style="font-weight: bold; font-size: 10px; margin-bottom: 2px;">OBSERVACIONES:</div>
                                    <div style="font-size: 9px;">{{ $ventaSeleccionada->observaciones }}</div>
                                </div>
                            @endif

                            <!-- Footer -->
                            <div class="text-center" style="padding-top: 6px;">
                                <div style="font-weight: bold; margin-bottom: 3px; font-size: 10px;">
                                    MÉTODO DE PAGO: {{ $ventaSeleccionada->metodo_pago }}
                                </div>
                                <div style="font-size: 9px; margin-bottom: 3px;">
                                    {{ $ventaSeleccionada->sucursal->direccion ?? 'Dirección de la sucursal' }}<br>
                                    Tel: {{ $ventaSeleccionada->sucursal->telefono ?? 'N/A' }}
                                </div>
                                <div style="font-size: 8px; margin-bottom: 3px;">
                                    Impresión: {{ now()->format('d/m/Y H:i:s') }}
                                </div>
                                <div style="font-weight: bold; font-size: 10px; margin-bottom: 2px;">
                                    ¡Gracias por su compra!
                                </div>
                                <div style="font-size: 8px; margin-bottom: 3px;">
                                    Conserve este ticket como comprobante
                                </div>
                                <div style="font-size: 8px;">
                                    Tel: 2-2345678 | Cel: 70123456<br>
                                    www.folkloreBolivia.com
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModalComprobante">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="imprimirComprobante()">
                            <i class="bi bi-printer me-2"></i>Imprimir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Script para imprimir -->
    <script>
        function imprimirComprobante() {
            const comprobante = document.getElementById('comprobante-venta').innerHTML;
            const ventana = window.open('', '_blank', 'width=320,height=600');
            ventana.document.write(`
                <html>
                    <head>
                        <title>Comprobante de Venta - Térmica</title>
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

                            /* Colores */
                            [style*="color: #198754"] {
                                color: #000 !important; /* Negro para impresión térmica */
                                font-weight: bold !important;
                            }

                            [style*="color: #dc3545"] {
                                color: #000 !important; /* Negro para impresión térmica */
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
</div>