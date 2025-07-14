<div>
    <div class="container">
        <!-- BREADCRUMB -->
        <div class="page-meta">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Administrativo</li>
                </ol>
            </nav>
        </div>
  <!-- Alertas y Notificaciones -->
        @if($stats['productos']['stock_bajo'] > 0 || $stats['productos']['por_vencer'] > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Alertas del Sistema</h5>
                    </div>
                    <div class="card-body">
                        @if($stats['productos']['stock_bajo'] > 0)
                        <div class="alert alert-warning mb-2">
                            <h6><i class="fas fa-box"></i> Productos con Stock Bajo</h6>
                            <button class="btn btn-secondary btn-sm mb-2" onclick="printTable('stockBajoTable')">
                                <i class="fas fa-print"></i> Imprimir
                            </button>
                            <div class="table-responsive mt-2">
                                <table class="table table-sm table-bordered" id="stockBajoTable">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Producto</th>
                                            <th>Stock Actual</th>
                                        </tr>
                                    </thead>
                                    {{-- {{ $stats['productos']['stock_bajo_detalles'] }} --}}
                                    <tbody>
                                        @foreach(collect($stats['productos']['stock_bajo_detalles']) as $producto)
                                            <tr>
                                                <td>{{ $producto->codigo_producto }}</td>
                                                <td>{{ $producto->nombre_producto }}</td>
                                                <td class="text-danger">{{ $producto->stock }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- {{ $stats['productos']['stock_bajo']->links() }} --}}

                            </div>
                        </div>
                    @endif
                    
                    @if($stats['productos']['por_vencer'] > 0)
                        <div class="alert alert-danger mb-2">
                            <h6><i class="fas fa-calendar-times"></i> Productos Próximos a Vencer</h6>
                            <input type="text" class="form-control form-control-sm mb-2" id="searchInput" onkeyup="filterTable('vencerTable', this.value)" placeholder="Buscar producto...">
                            <button class="btn btn-secondary btn-sm mb-2" onclick="printTable('vencerTable')">
                                <i class="fas fa-print"></i> Imprimir
                            </button>
                            <div class="table-responsive mt-2">
                                <table class="table table-sm table-bordered" id="vencerTable">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Producto</th>
                                            <th>Fecha Vencimiento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats['productos']['por_vencer_detalles'] as $producto)
                                            <tr>
                                                <td>{{ $producto->codigo_producto }}</td>
                                                <td>{{ $producto->nombre_producto }}</td>
                                                <td class="text-danger">{{ \Carbon\Carbon::parse($producto->fecha_vencimiento)->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    
                    <script>
                        // Filtrar tabla
                        function filterTable(tableId, searchValue) {
                            const table = document.getElementById(tableId);
                            const rows = table.getElementsByTagName('tr');
                            const lowerSearch = searchValue.toLowerCase();
                    
                            for (let i = 1; i < rows.length; i++) {
                                const cells = rows[i].getElementsByTagName('td');
                                let match = false;
                    
                                for (let j = 0; j < cells.length; j++) {
                                    if (cells[j].textContent.toLowerCase().includes(lowerSearch)) {
                                        match = true;
                                        break;
                                    }
                                }
                    
                                rows[i].style.display = match ? '' : 'none';
                            }
                        }
                    
                        // Imprimir tabla
                        function printTable(tableId) {
                            const table = document.getElementById(tableId).outerHTML;
                            const newWindow = window.open('', '_blank');
                            newWindow.document.write(`
                                <html>
                                    <head>
                                        <title>Imprimir Tabla</title>
                                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
                                    </head>
                                    <body>
                                        ${table}
                                    </body>
                                </html>
                            `);
                            newWindow.document.close();
                            newWindow.print();
                        }
                    </script>
                    
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- Tarjetas de Resumen -->
        <div class="row mb-4">
            <!-- Ventas -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card bg-gradient-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-dark">Ventas Hoy</h6>
                                <h4 class="text-dark mb-0">Bs. {{ number_format($stats['ventas']['hoy'], 2) }}</h4>
                            </div>
                            <div class="icon-box">
                                <i class="fas fa-shopping-cart text-dark fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="{{ $stats['ventas']['tendencia'] >= 0 ? 'text-success' : 'text-danger' }} bg-light px-2 py-1 rounded">
                                <i class="fas {{ $stats['ventas']['tendencia'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                {{ abs(round($stats['ventas']['tendencia'], 1)) }}%
                            </span>
                            <span class="text-dark ms-2">vs mes anterior</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clientes -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card bg-gradient-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-dark">Clientes Totales</h6>
                                <h4 class="text-dark mb-0">{{ $stats['clientes']['total'] }}</h4>
                            </div>
                            <div class="icon-box">
                                <i class="fas fa-users text-dark fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-dark">{{ $stats['clientes']['nuevos'] }} nuevos este mes</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mascotas -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-dark">Mascotas</h6>
                                <h4 class="text-dark mb-0">{{ $stats['mascotas']['total'] }}</h4>
                            </div>
                            <div class="icon-box">
                                <i class="fas fa-paw text-dark fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-dark">{{ $stats['mascotas']['nuevas'] }} nuevas este mes</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Productos -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card bg-gradient-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-dark">Productos</h6>
                                <h4 class="text-dark mb-0">{{ $stats['productos']['total'] }}</h4>
                            </div>
                            <div class="icon-box">
                                <i class="fas fa-box text-dark fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-danger bg-light px-2 py-1 rounded">
                                {{ $stats['productos']['stock_bajo'] }} con stock bajo
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Caja -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-cash-register"></i> Estado de Caja
                            <span class="float-end">
                                Actualizado: {{ now()->format('H:i') }}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Balance Actual -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Balance Actual</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    Bs. {{ number_format($stats['caja']['stats']['balance_actual'] ?? 0, 2) }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Entradas -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Entradas</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    Bs. {{ number_format($stats['caja']['stats']['entradas']['total'], 2) }}
                                                </div>
                                                <div class="text-xs text-muted">
                                                    Hoy: Bs. {{ number_format($stats['caja']['stats']['entradas']['hoy'], 2) }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-arrow-up fa-2x text-success"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Salidas -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-danger h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                    Salidas</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    Bs. {{ number_format($stats['caja']['stats']['salidas']['total'], 2) }}
                                                </div>
                                                <div class="text-xs text-muted">
                                                    Hoy: Bs. {{ number_format($stats['caja']['stats']['salidas']['hoy'], 2) }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-arrow-down fa-2x text-danger"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Operaciones -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    Operaciones</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    Entradas: Bs. {{ number_format($stats['caja']['stats']['operaciones']['total'], 2) }}
                                                </div>
                                                <div class="text-xs text-muted">
                                                    Salidas: Bs. {{ number_format($stats['caja']['stats']['operaciones_salida']['total'], 2) }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-exchange-alt fa-2x text-info"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resumen Mensual -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tipo</th>
                                                <th class="text-end">Hoy</th>
                                                <th class="text-end">Este Mes</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Entradas</td>
                                                <td class="text-end text-success">
                                                    Bs. {{ number_format($stats['caja']['stats']['entradas']['hoy'], 2) }}
                                                </td>
                                                <td class="text-end text-success">
                                                    Bs. {{ number_format($stats['caja']['stats']['entradas']['mes'], 2) }}
                                                </td>
                                                <td class="text-end text-success">
                                                    Bs. {{ number_format($stats['caja']['stats']['entradas']['total'], 2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Salidas</td>
                                                <td class="text-end text-danger">
                                                    Bs. {{ number_format($stats['caja']['stats']['salidas']['hoy'], 2) }}
                                                </td>
                                                <td class="text-end text-danger">
                                                    Bs. {{ number_format($stats['caja']['stats']['salidas']['mes'], 2) }}
                                                </td>
                                                <td class="text-end text-danger">
                                                    Bs. {{ number_format($stats['caja']['stats']['salidas']['total'], 2) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      

        <!-- Productos Más Vendidos -->
        <div class="row mb-4">
            <div class="col-xl-6 col-lg-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Productos Más Vendidos</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Código</th>
                                        <th>Vendidos</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['productos_populares'] as $producto)
                                        <tr>
                                            <td>{{ $producto->producto->nombre_producto }}</td>
                                            <td>{{ $producto->producto->codigo_producto }}</td>
                                            <td>{{ $producto->total_vendido }}</td>
                                            <td>
                                                <span class="badge bg-{{ $producto->producto->stock <= 10 ? 'danger' : 'success' }}">
                                                    {{ $producto->producto->stock }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos existentes -->
            <div class="col-xl-6 col-lg-12">
                <div id="chartColumnStacked" class="layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-header">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h4>Reporte Citas</h4>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div id="s-col-stackeds"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Ventas -->
        <div class="row">
            <div class="col-12">
                <div id="chartColumns" class="layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-header">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h4>Reporte Ingresos Mensuales</h4>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div id="s-col-das"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Después de las tarjetas principales -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Entradas de Efectivo</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['caja']['stats']['entradas'] as $key => $entrada)
                                        <tr>
                                            <td>{{ ucfirst($key) }}</td>
                                            <td>Bs. {{ number_format($entrada, 2) }}</td>
                                            <td>{{ now()->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr style="background-color: #f2f2f2; border-top: 2px solid #000;">
                                        <td style="text-align: right; font-weight: bold;">Total:</td>
                                        <td colspan="2" style="font-weight: bold;">
                                            Bs. {{ number_format($stats['caja']['stats']['entradas']['total'], 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Salidas de Efectivo</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['caja']['stats']['salidas'] as $key => $salida)
                                        <tr>
                                            <td>{{ ucfirst($key) }}</td>
                                            <td>Bs. {{ number_format($salida, 2) }}</td>
                                            <td>{{ now()->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr style="background-color: #f2f2f2; border-top: 2px solid #000;">
                                        <td style="text-align: right; font-weight: bold;">Total:</td>
                                        <td colspan="2" style="font-weight: bold;">
                                            Bs. {{ number_format($stats['caja']['stats']['salidas']['total'], 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Operaciones</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['caja']['stats']['operaciones'] as $key => $operacion)
                                        <tr>
                                            <td>{{ ucfirst($key) }}</td>
                                            <td>Entradas. {{ number_format($operacion, 2) }}</td>
                                            <td>{{ now()->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr style="background-color: #f2f2f2; border-top: 2px solid #000;">
                                        <td style="text-align: right; font-weight: bold;">Total:</td>
                                        <td colspan="2" style="font-weight: bold;">
                                            Entradas. {{ number_format($stats['caja']['stats']['operaciones']['total'], 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Salidas por Operaciones</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['caja']['stats']['operaciones_salida'] as $key => $operacionSalida)
                                        <tr>
                                            <td>{{ ucfirst($key) }}</td>
                                            <td>Bs. {{ number_format($operacionSalida, 2) }}</td>
                                            <td>{{ now()->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr style="background-color: #f2f2f2; border-top: 2px solid #000;">
                                        <td style="text-align: right; font-weight: bold;">Total:</td>
                                        <td colspan="2" style="font-weight: bold;">
                                            Bs. {{ number_format($stats['caja']['stats']['operaciones_salida']['total'], 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #ffffff, #e3f2fd);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #ffffff, #e8f5e9);
}

.bg-gradient-warning {
    background: linear-gradient(45deg, #ffffff, #fff3e0);
}

.bg-gradient-danger {
    background: linear-gradient(45deg, #ffffff, #ffebee);
}

.icon-box {
    padding: 10px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.3);
}

.text-dark {
    color: #2c3e50 !important;
}
</style>

<!-- Mantener los scripts existentes -->

@push('scripts')
<script src="{{ asset('plugins/apex/apexcharts.min.js') }}"></script>

<script>
    document.addEventListener('livewire:load', function() {
        // Gráfico de Ingresos Mensuales
        var options1 = {
            chart: {
                height: 350,
                type: 'bar',
                toolbar: {
                    show: false,
                }
            },
            series: [{
                name: '2024',
                data: @json($montosActual ?? [])
            }, {
                name: '2023',
                data: @json($montosAnterior ?? [])
            }],
            xaxis: {
                categories: @json($fechas ?? []),
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            colors: ['#650abb', '#805dca'],
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return 'Bs ' + val.toFixed(2)
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return 'Bs ' + val.toFixed(2)
                    }
                }
            }
        }

        // Gráfico de Citas
        var options2 = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: true,
                toolbar: {
                    show: false,
                }
            },
            series: @json($series ?? []),
            xaxis: {
                categories: @json($fechas ?? []),
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                },
            },
            dataLabels: {
                enabled: false
            },
            colors: ['#00ab55', '#ffc107', '#dc3545'],
            fill: {
                opacity: 1
            }
        }

        try {
            if(document.querySelector("#s-col-das")) {
                const chart1 = new ApexCharts(
                    document.querySelector("#s-col-das"),
                    options1
                );
                chart1.render();
            }

            if(document.querySelector("#s-col-stackeds")) {
                const chart2 = new ApexCharts(
                    document.querySelector("#s-col-stackeds"),
                    options2
                );
                chart2.render();
            }
        } catch (error) {
            console.error('Error al renderizar gráficos:', error);
        }
    });
</script>
@endpush
