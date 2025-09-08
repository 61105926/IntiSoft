@php
    $totalVentas = $ventas->total();
    $ventasCompletadas = $resumenHoy['completadas'] ?? 0;
    $ventasPendientes = $resumenHoy['pendientes'] ?? 0;
    $ingresosTotales = $resumenHoy['monto_pagado'] ?? 0;
@endphp

<div>
    <div class="container-fluid py-4">
        <!-- Encabezado elegante -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-6 fw-bold text-primary mb-2">
                            <i class="fas fa-cash-register me-3"></i>
                            Gestión de Ventas
                        </h1>
                        <p class="text-muted lead">Control completo de ventas y facturación</p>
                    </div>
                    <div>
                        <button wire:click="abrirModalVenta" class="btn btn-primary btn-lg shadow-lg">
                            <i class="fas fa-plus-circle me-2"></i>
                            Nueva Venta
                        </button>
                    </div>
                </div>
            </div>
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

        <!-- Cards métricas elegantes -->
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-lg h-100 metric-card gradient-primary">
                    <div class="card-body text-white p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-2 text-white-50 text-uppercase small fw-bold">Total Ventas Hoy</p>
                                <h2 class="mb-0 fw-bold">{{ $resumenHoy['total_ventas'] ?? 0 }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-circle p-3 icon-container">
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-2 text-white-50 text-uppercase small fw-bold">Completadas</p>
                                <h2 class="mb-0 fw-bold">{{ $ventasCompletadas }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-2 text-white-50 text-uppercase small fw-bold">Pendientes</p>
                                <h2 class="mb-0 fw-bold">{{ $ventasPendientes }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <div class="card-body text-white p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-2 text-white-50 text-uppercase small fw-bold">Ingresos Hoy</p>
                                <h2 class="mb-0 fw-bold">Bs. {{ number_format($ingresosTotales, 2) }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros elegantes -->
        <div class="card border-0 shadow-lg mb-5">
            <div class="card-header bg-gradient text-white py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-filter me-2"></i>
                    Filtros de Búsqueda
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Búsqueda</label>
                        <div class="position-relative">
                            <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" wire:model.live="busqueda" class="form-control form-control-lg ps-5 border-0 bg-light" 
                                   placeholder="Buscar por número o cliente..." />
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Estado</label>
                        <select wire:model.live="filtroEstado" class="form-select form-select-lg border-0 bg-light">
                            <option value="">Todos</option>
                            <option value="PENDIENTE">Pendiente</option>
                            <option value="COMPLETADA">Completada</option>
                            <option value="CANCELADA">Cancelada</option>
                            <option value="DEVUELTA">Devuelta</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Estado Pago</label>
                        <select wire:model.live="filtroEstadoPago" class="form-select form-select-lg border-0 bg-light">
                            <option value="">Todos</option>
                            <option value="PENDIENTE">Pendiente</option>
                            <option value="PARCIAL">Parcial</option>
                            <option value="PAGADO">Pagado</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Desde</label>
                        <input type="date" wire:model.live="fechaDesde" class="form-control form-control-lg border-0 bg-light" />
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Hasta</label>
                        <input type="date" wire:model.live="fechaHasta" class="form-control form-control-lg border-0 bg-light" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla Ventas elegante -->
        <div class="card border-0 shadow-lg">
            <div class="card-header border-0 py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-white fw-bold">
                        <i class="fas fa-receipt me-2"></i>
                        Listado de Ventas
                    </h4>
                    <span class="badge bg-white text-dark px-3 py-2 fw-bold fs-6">
                        {{ $ventas->total() }} ventas
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 fw-bold text-muted small text-uppercase">Número</th>
                                <th class="px-4 py-3 fw-bold text-muted small text-uppercase">Cliente</th>
                                <th class="px-4 py-3 fw-bold text-muted small text-uppercase">Fecha</th>
                                <th class="px-4 py-3 fw-bold text-muted small text-uppercase">Estado</th>
                                <th class="px-4 py-3 fw-bold text-muted small text-uppercase">Pago</th>
                                <th class="px-4 py-3 fw-bold text-muted small text-uppercase">Total</th>
                                <th class="px-4 py-3 fw-bold text-muted small text-uppercase">Saldo</th>
                                <th class="px-4 py-3 fw-bold text-muted small text-uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ventas as $venta)
                                <tr class="border-bottom">
                                    <td class="px-4 py-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="fas fa-file-invoice text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $venta->numero_venta }}</div>
                                                <small class="text-muted">{{ $venta->sucursal->nombre }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="fas fa-user text-success"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $venta->cliente->nombres }} {{ $venta->cliente->apellidos }}</div>
                                                <small class="text-muted">{{ $venta->cliente->telefono ?? 'Sin teléfono' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="fw-semibold">{{ $venta->fecha_venta->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $venta->fecha_venta->format('H:i') }}</small>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="badge {{ $venta->estado_badge_class }} px-3 py-2 fw-semibold">
                                            {{ $venta->estado_display }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="badge {{ $venta->estado_pago_badge_class }} px-3 py-2 fw-semibold">
                                            {{ $venta->estado_pago_display }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="fw-bold text-dark fs-6">Bs. {{ number_format($venta->total, 2) }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($venta->saldo_pendiente > 0)
                                            <div class="fw-bold text-danger fs-6">
                                                Bs. {{ number_format($venta->saldo_pendiente, 2) }}
                                            </div>
                                            <small class="text-muted">Pendiente</small>
                                        @else
                                            <div class="fw-bold text-success fs-6">Bs. 0.00</div>
                                            <small class="text-success">Pagado</small>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="btn-group" role="group">
                                            <button wire:click="verDetalle({{ $venta->id }})" 
                                                    class="btn btn-primary btn-sm shadow-sm" title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            @if($venta->estado === 'PENDIENTE')
                                                <button wire:click="abrirModalVenta({{ $venta->id }})" 
                                                        class="btn btn-warning btn-sm shadow-sm" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endif
                                            
                                            @if($venta->saldo_pendiente > 0)
                                                <button wire:click="abrirModalPago({{ $venta->id }})" 
                                                        class="btn btn-success btn-sm shadow-sm" title="Procesar pago">
                                                    <i class="fas fa-credit-card"></i>
                                                </button>
                                            @endif

                                            <button wire:click="imprimirComprobante({{ $venta->id }})" 
                                                    class="btn btn-info btn-sm shadow-sm" title="Imprimir comprobante">
                                                <i class="fas fa-print"></i>
                                            </button>
                                            
                                            @if($venta->estado === 'PENDIENTE')
                                                <button wire:click="completarVenta({{ $venta->id }})" 
                                                        onclick="return confirm('¿Completar esta venta?')"
                                                        class="btn btn-success btn-sm shadow-sm" title="Completar">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                
                                                <button wire:click="cancelarVenta({{ $venta->id }})" 
                                                        onclick="return confirm('¿Cancelar esta venta?')"
                                                        class="btn btn-danger btn-sm shadow-sm" title="Cancelar">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="py-5">
                                            <i class="fas fa-shopping-cart display-1 text-muted mb-3"></i>
                                            <h4 class="text-muted">No hay ventas registradas</h4>
                                            <p class="text-muted">Crea tu primera venta usando el botón "Nueva Venta"</p>
                                        </div>
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
                                                <option value="{{ $cliente->id }}">{{ $cliente->nombres }} {{ $cliente->apellidos }}</option>
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
                                                    @php
                                                        $stock = $producto->stocks->where('sucursal_id', $sucursal_id)->first();
                                                        $precio = $stock ? $stock->precio_venta_sucursal : 0;
                                                    @endphp
                                                    <option value="{{ $producto->id }}">
                                                        {{ $producto->nombre }} - Bs. {{ number_format($precio, 2) }}
                                                        (Stock: {{ $stock ? $stock->stock_actual : 0 }})
                                                    </option>
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

    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Estilos personalizados -->
    <link href="{{ asset('css/ventas-style.css') }}" rel="stylesheet" />
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        window.livewire.on('printVentaEvent', function (ventaDataJson) {
            const ventaData = JSON.parse(ventaDataJson);
            imprimirComprobante(ventaData);
        });
    });

    function imprimirComprobante(venta) {
        const ventanaImpresion = window.open('', '_blank', 'width=300,height=500');
        
        const contenidoHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Comprobante de Venta</title>
                <style>
                    body { font-family: 'Courier New', monospace; font-size: 12px; margin: 0; padding: 10px; }
                    .centro { text-align: center; }
                    .derecha { text-align: right; }
                    .linea { border-top: 1px dashed #000; margin: 5px 0; }
                    .espaciado { margin: 10px 0; }
                    table { width: 100%; border-collapse: collapse; }
                    td { padding: 2px 0; }
                    .producto { border-bottom: 1px dotted #ccc; }
                </style>
            </head>
            <body>
                <div class="centro">
                    <h3>COMPROBANTE DE VENTA</h3>
                    <p><strong>${venta.numero_venta}</strong></p>
                </div>
                
                <div class="linea"></div>
                
                <table>
                    <tr><td>Fecha:</td><td class="derecha">${venta.fecha_venta}</td></tr>
                    <tr><td>Sucursal:</td><td class="derecha">${venta.sucursal}</td></tr>
                    <tr><td>Vendedor:</td><td class="derecha">${venta.usuario}</td></tr>
                </table>
                
                <div class="linea"></div>
                
                <p><strong>CLIENTE:</strong></p>
                <p>${venta.cliente.nombres} ${venta.cliente.apellidos}</p>
                ${venta.cliente.carnet_identidad ? `<p>C.I.: ${venta.cliente.carnet_identidad}</p>` : ''}
                ${venta.cliente.telefono ? `<p>Telf.: ${venta.cliente.telefono}</p>` : ''}
                
                <div class="linea"></div>
                
                <p><strong>PRODUCTOS:</strong></p>
                ${venta.productos.map(producto => `
                    <div class="producto">
                        <table>
                            <tr>
                                <td colspan="2"><strong>${producto.nombre}</strong></td>
                            </tr>
                            <tr>
                                <td>Cod: ${producto.codigo || 'N/A'}</td>
                                <td class="derecha">Cant: ${producto.cantidad}</td>
                            </tr>
                            <tr>
                                <td>P.Unit: Bs. ${parseFloat(producto.precio_unitario).toFixed(2)}</td>
                                <td class="derecha">Desc: Bs. ${parseFloat(producto.descuento_unitario || 0).toFixed(2)}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="derecha"><strong>Bs. ${parseFloat(producto.subtotal).toFixed(2)}</strong></td>
                            </tr>
                        </table>
                    </div>
                `).join('')}
                
                <div class="linea"></div>
                
                <table class="espaciado">
                    <tr><td>Subtotal:</td><td class="derecha">Bs. ${parseFloat(venta.subtotal).toFixed(2)}</td></tr>
                    ${parseFloat(venta.descuento) > 0 ? `<tr><td>Descuento:</td><td class="derecha">-Bs. ${parseFloat(venta.descuento).toFixed(2)}</td></tr>` : ''}
                    ${parseFloat(venta.impuestos) > 0 ? `<tr><td>Impuestos:</td><td class="derecha">Bs. ${parseFloat(venta.impuestos).toFixed(2)}</td></tr>` : ''}
                    <tr><td><strong>TOTAL:</strong></td><td class="derecha"><strong>Bs. ${parseFloat(venta.total).toFixed(2)}</strong></td></tr>
                </table>
                
                <div class="linea"></div>
                
                <table class="espaciado">
                    <tr><td>Método de Pago:</td><td class="derecha">${venta.metodo_pago}</td></tr>
                    <tr><td>Monto Pagado:</td><td class="derecha">Bs. ${parseFloat(venta.monto_pagado).toFixed(2)}</td></tr>
                    ${parseFloat(venta.saldo_pendiente) > 0 ? `<tr><td>Saldo Pendiente:</td><td class="derecha text-danger">Bs. ${parseFloat(venta.saldo_pendiente).toFixed(2)}</td></tr>` : ''}
                </table>
                
                <div class="linea"></div>
                
                <table>
                    <tr><td>Estado:</td><td class="derecha">${venta.estado}</td></tr>
                    <tr><td>Estado Pago:</td><td class="derecha">${venta.estado_pago}</td></tr>
                </table>
                
                ${venta.observaciones ? `
                    <div class="linea"></div>
                    <p><strong>OBSERVACIONES:</strong></p>
                    <p>${venta.observaciones}</p>
                ` : ''}
                
                <div class="espaciado centro">
                    <p>¡Gracias por su compra!</p>
                    <p>Sistema IntiSoft</p>
                </div>
            </body>
            </html>
        `;
        
        ventanaImpresion.document.write(contenidoHTML);
        ventanaImpresion.document.close();
        
        ventanaImpresion.onload = function() {
            ventanaImpresion.print();
            ventanaImpresion.close();
        };
    }
</script>
@endpush