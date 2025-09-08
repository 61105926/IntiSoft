@php
    $totalVentas = $ventas->total();
    $ventasCompletadas = $resumenHoy['completadas'] ?? 0;
    $ventasPendientes = $resumenHoy['pendientes'] ?? 0;
    $ingresosTotales = $resumenHoy['monto_pagado'] ?? 0;
@endphp

<div class="container mx-auto py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">
                <i class="fas fa-cash-register mr-3 text-primary"></i>
                Gestión de Ventas
            </h1>
            <p class="text-muted-foreground">Control completo de ventas y facturación</p>
        </div>
        <x-ui.button wire:click="abrirModalVenta" size="lg">
            <i class="fas fa-plus mr-2"></i>
            Nueva Venta
        </x-ui.button>
    </div>

    <!-- Alerts -->
    @if(session('message'))
        <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
            <div class="flex">
                <i class="fas fa-check-circle text-green-400 mr-3"></i>
                <div class="text-sm font-medium text-green-800">{{ session('message') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                <div class="text-sm font-medium text-red-800">{{ session('error') }}</div>
            </div>
        </div>
    @endif

    <!-- Métricas Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Ventas -->
        <x-ui.card class="bg-gradient-to-br from-blue-600 to-purple-600 text-white border-none">
            <x-ui.card-content class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium uppercase tracking-wider">Total Ventas Hoy</p>
                        <p class="text-2xl font-bold">{{ $resumenHoy['total_ventas'] ?? 0 }}</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-shopping-cart text-xl"></i>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Completadas -->
        <x-ui.card class="bg-gradient-to-br from-green-500 to-emerald-600 text-white border-none">
            <x-ui.card-content class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium uppercase tracking-wider">Completadas</p>
                        <p class="text-2xl font-bold">{{ $ventasCompletadas }}</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Pendientes -->
        <x-ui.card class="bg-gradient-to-br from-orange-500 to-red-500 text-white border-none">
            <x-ui.card-content class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium uppercase tracking-wider">Pendientes</p>
                        <p class="text-2xl font-bold">{{ $ventasPendientes }}</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Ingresos -->
        <x-ui.card class="bg-gradient-to-br from-purple-500 to-pink-600 text-white border-none">
            <x-ui.card-content class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium uppercase tracking-wider">Ingresos Hoy</p>
                        <p class="text-2xl font-bold">Bs. {{ number_format($ingresosTotales, 2) }}</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-dollar-sign text-xl"></i>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <!-- Filtros -->
    <x-ui.card class="mb-8">
        <x-ui.card-header>
            <x-ui.card-title class="flex items-center">
                <i class="fas fa-filter mr-2"></i>
                Filtros de Búsqueda
            </x-ui.card-title>
        </x-ui.card-header>
        <x-ui.card-content>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-2">Búsqueda</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground"></i>
                        <x-ui.input 
                            type="text" 
                            wire:model.live="busqueda" 
                            placeholder="Buscar por número o cliente..." 
                            class="pl-10"
                        />
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-2">Estado</label>
                    <x-ui.select wire:model.live="filtroEstado">
                        <option value="">Todos</option>
                        <option value="PENDIENTE">Pendiente</option>
                        <option value="COMPLETADA">Completada</option>
                        <option value="CANCELADA">Cancelada</option>
                        <option value="DEVUELTA">Devuelta</option>
                    </x-ui.select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-2">Estado Pago</label>
                    <x-ui.select wire:model.live="filtroEstadoPago">
                        <option value="">Todos</option>
                        <option value="PENDIENTE">Pendiente</option>
                        <option value="PARCIAL">Parcial</option>
                        <option value="PAGADO">Pagado</option>
                    </x-ui.select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-2">Desde</label>
                    <x-ui.input type="date" wire:model.live="fechaDesde" />
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-2">Hasta</label>
                    <x-ui.input type="date" wire:model.live="fechaHasta" />
                </div>
            </div>
        </x-ui.card-content>
    </x-ui.card>

    <!-- Tabla de Ventas -->
    <x-ui.card>
        <x-ui.card-header>
            <div class="flex items-center justify-between">
                <x-ui.card-title class="flex items-center">
                    <i class="fas fa-receipt mr-2"></i>
                    Listado de Ventas
                </x-ui.card-title>
                <x-ui.badge variant="secondary" class="px-3 py-1 font-semibold">
                    {{ $ventas->total() }} ventas
                </x-ui.badge>
            </div>
        </x-ui.card-header>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <thead class="table-header">
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
                <tbody class="table-body">
                    @forelse($ventas as $venta)
                        <tr>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="bg-primary/10 rounded-full p-2">
                                        <i class="fas fa-file-invoice text-primary text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold">{{ $venta->numero_venta }}</div>
                                        <div class="text-sm text-muted-foreground">{{ $venta->sucursal->nombre }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="bg-green-100 rounded-full p-2">
                                        <i class="fas fa-user text-green-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $venta->cliente->nombres }} {{ $venta->cliente->apellidos }}</div>
                                        <div class="text-sm text-muted-foreground">{{ $venta->cliente->telefono ?? 'Sin teléfono' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-medium">{{ $venta->fecha_venta->format('d/m/Y') }}</div>
                                <div class="text-sm text-muted-foreground">{{ $venta->fecha_venta->format('H:i') }}</div>
                            </td>
                            <td>
                                @php
                                    $estadoVariant = match($venta->estado) {
                                        'PENDIENTE' => 'secondary',
                                        'COMPLETADA' => 'default',
                                        'CANCELADA' => 'destructive',
                                        default => 'secondary'
                                    };
                                @endphp
                                <x-ui.badge :variant="$estadoVariant">
                                    {{ $venta->estado_display }}
                                </x-ui.badge>
                            </td>
                            <td>
                                @php
                                    $pagoVariant = match($venta->estado_pago) {
                                        'PENDIENTE' => 'destructive',
                                        'PAGADO' => 'default',
                                        'PARCIAL' => 'secondary',
                                        default => 'secondary'
                                    };
                                @endphp
                                <x-ui.badge :variant="$pagoVariant">
                                    {{ $venta->estado_pago_display }}
                                </x-ui.badge>
                            </td>
                            <td>
                                <div class="font-semibold text-lg">Bs. {{ number_format($venta->total, 2) }}</div>
                            </td>
                            <td>
                                @if($venta->saldo_pendiente > 0)
                                    <div class="font-semibold text-destructive">
                                        Bs. {{ number_format($venta->saldo_pendiente, 2) }}
                                    </div>
                                    <div class="text-sm text-muted-foreground">Pendiente</div>
                                @else
                                    <div class="font-semibold text-green-600">Bs. 0.00</div>
                                    <div class="text-sm text-green-600">Pagado</div>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center space-x-1">
                                    <x-ui.button 
                                        wire:click="verDetalle({{ $venta->id }})" 
                                        variant="outline" 
                                        size="sm"
                                        title="Ver detalle"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </x-ui.button>
                                    
                                    @if($venta->estado === 'PENDIENTE')
                                        <x-ui.button 
                                            wire:click="abrirModalVenta({{ $venta->id }})" 
                                            variant="secondary" 
                                            size="sm"
                                            title="Editar"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </x-ui.button>
                                    @endif
                                    
                                    @if($venta->saldo_pendiente > 0)
                                        <x-ui.button 
                                            wire:click="abrirModalPago({{ $venta->id }})" 
                                            variant="default" 
                                            size="sm"
                                            title="Procesar pago"
                                        >
                                            <i class="fas fa-credit-card"></i>
                                        </x-ui.button>
                                    @endif

                                    <x-ui.button 
                                        wire:click="imprimirComprobante({{ $venta->id }})" 
                                        variant="secondary" 
                                        size="sm"
                                        title="Imprimir"
                                    >
                                        <i class="fas fa-print"></i>
                                    </x-ui.button>
                                    
                                    @if($venta->estado === 'PENDIENTE')
                                        <x-ui.button 
                                            wire:click="completarVenta({{ $venta->id }})" 
                                            onclick="return confirm('¿Completar esta venta?')"
                                            variant="default" 
                                            size="sm"
                                            title="Completar"
                                        >
                                            <i class="fas fa-check"></i>
                                        </x-ui.button>
                                        
                                        <x-ui.button 
                                            wire:click="cancelarVenta({{ $venta->id }})" 
                                            onclick="return confirm('¿Cancelar esta venta?')"
                                            variant="destructive" 
                                            size="sm"
                                            title="Cancelar"
                                        >
                                            <i class="fas fa-times"></i>
                                        </x-ui.button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-shopping-cart text-6xl text-muted-foreground mb-4"></i>
                                    <h3 class="text-lg font-medium text-muted-foreground mb-2">No hay ventas registradas</h3>
                                    <p class="text-muted-foreground">Crea tu primera venta usando el botón "Nueva Venta"</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-ui.table>
        </x-ui.card-content>
        
        @if($ventas->hasPages())
            <div class="border-t px-6 py-4">
                {{ $ventas->links() }}
            </div>
        @endif
    </x-ui.card>
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