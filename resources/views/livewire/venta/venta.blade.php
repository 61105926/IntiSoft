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
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @if($ventaSeleccionada) Editar Venta @else Nueva Venta @endif
                        </h5>
                        <button type="button" class="btn-close" wire:click="cerrarModalVenta"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="guardarVenta">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Cliente *</label>
                                        <select wire:model="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror">
                                            <option value="">Seleccione cliente</option>
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Sucursal *</label>
                                        <select wire:model="sucursal_id" class="form-select @error('sucursal_id') is-invalid @enderror">
                                            <option value="">Seleccione sucursal</option>
                                            @foreach($sucursales as $sucursal)
                                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('sucursal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Fecha Venta *</label>
                                        <input type="datetime-local" wire:model="fecha_venta" 
                                               class="form-control @error('fecha_venta') is-invalid @enderror">
                                        @error('fecha_venta') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Método de Pago</label>
                                        <select wire:model="metodo_pago" class="form-select">
                                            <option value="EFECTIVO">Efectivo</option>
                                            <option value="TARJETA">Tarjeta</option>
                                            <option value="TRANSFERENCIA">Transferencia</option>
                                            <option value="CHEQUE">Cheque</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Productos -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Productos</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <select wire:model.live="productoSeleccionado" class="form-select">
                                                <option value="">Seleccione producto</option>
                                                @foreach($productos as $producto)
                                                    <option value="{{ $producto->id }}">{{ $producto->nombre }} - Bs. {{ $producto->precio_venta }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" wire:model="cantidadProducto" 
                                                   class="form-control" placeholder="Cantidad" min="1">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" wire:model="precioProducto" step="0.01"
                                                   class="form-control" placeholder="Precio">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" wire:model="descuentoProducto" step="0.01"
                                                   class="form-control" placeholder="Descuento">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" wire:click="agregarProducto" class="btn btn-primary">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    @if(count($productosEnVenta) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th>Cantidad</th>
                                                        <th>Precio Unit.</th>
                                                        <th>Descuento</th>
                                                        <th>Subtotal</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($productosEnVenta as $index => $producto)
                                                        <tr>
                                                            <td>{{ $producto['nombre'] }}</td>
                                                            <td>{{ $producto['cantidad'] }}</td>
                                                            <td>Bs. {{ number_format($producto['precio_unitario'], 2) }}</td>
                                                            <td>Bs. {{ number_format($producto['descuento_unitario'], 2) }}</td>
                                                            <td>Bs. {{ number_format($producto['subtotal'], 2) }}</td>
                                                            <td>
                                                                <button type="button" wire:click="eliminarProducto({{ $index }})" 
                                                                        class="btn btn-outline-danger btn-sm">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                                        <td><strong>Bs. {{ number_format($this->calcularTotalVenta(), 2) }}</strong></td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Descuento General</label>
                                        <input type="number" step="0.01" wire:model="descuento" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Impuestos</label>
                                        <input type="number" step="0.01" wire:model="impuestos" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Observaciones</label>
                                <textarea wire:model="observaciones" class="form-control" rows="2"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModalVenta">Cancelar</button>
                        <button type="button" class="btn btn-primary" wire:click="guardarVenta">
                            @if($ventaSeleccionada) Actualizar @else Crear @endif Venta
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
                        
                        <div class="mb-3">
                            <label class="form-label">Caja *</label>
                            <select wire:model="cajaParaPago" class="form-select @error('cajaParaPago') is-invalid @enderror">
                                <option value="">Seleccione caja</option>
                                @foreach($cajas as $caja)
                                    <option value="{{ $caja->id }}">{{ $caja->nombre }} - Bs. {{ number_format($caja->saldo_actual, 2) }}</option>
                                @endforeach
                            </select>
                            @error('cajaParaPago') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Método de Pago</label>
                            <select wire:model="metodoPagoVenta" class="form-select">
                                <option value="EFECTIVO">Efectivo</option>
                                <option value="TARJETA">Tarjeta</option>
                                <option value="TRANSFERENCIA">Transferencia</option>
                                <option value="CHEQUE">Cheque</option>
                            </select>
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

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
</div>