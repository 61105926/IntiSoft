@extends('layouts.print')

@section('content')
    <div class="header" style="text-align: center; margin-bottom: 20px;">
        <div class="logo">
            <div class="logo-image">
                <img src="img/logo.jpg" alt="Logo" width="150" height="150"
                    style="border-radius: 50%; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            </div>
        </div>
        <div class="texto-centro"
            style="font-size: 30px; color: #e91e63; font-weight: bold; letter-spacing: 2px; margin-top: 10px;">
            Reporte de Inventario
        </div>
        <div class="texto-derecha-abajo" style="font-size: 14px; color: #d81b60; margin-top: 10px;">
            Fecha:
            @if ($dateFrom && $dateTo)
                {{ $dateFrom }} hasta {{ $dateTo }}
            @else
                Actualidad
            @endif
        </div>
    </div>

    <br>

    <!-- Resumen General -->
    <div style="display: flex; justify-content: center; margin-bottom: 20px;">
        <table style="width: 100%; border: 1px solid #e91e63; border-radius: 10px; padding: 10px; background-color: #fce4ec;">
            <thead>
                <tr style="background-color: #f8bbd0;">
                    <th style="font-size: 14px; color: #880e4f; padding: 10px; text-align: center;">Total Productos</th>
                    <th style="font-size: 14px; color: #880e4f; padding: 10px; text-align: center;">Total Entradas</th>
                    <th style="font-size: 14px; color: #880e4f; padding: 10px; text-align: center;">Total Salidas</th>
                    <th style="font-size: 14px; color: #880e4f; padding: 10px; text-align: center;">Stock Total</th>
                    <th style="font-size: 14px; color: #880e4f; padding: 10px; text-align: center;">Valor Total Inventario</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-size: 14px; font-weight: normal; text-align: center;">{{ $totalProductos }}</td>
                    <td style="font-size: 14px; font-weight: normal; text-align: center;">{{ $totalEntradas }}</td>
                    <td style="font-size: 14px; font-weight: normal; text-align: center;">{{ $totalSalidas }}</td>
                    <td style="font-size: 14px; font-weight: normal; text-align: center;">{{ $stockTotal }}</td>
                    <td style="font-size: 14px; font-weight: normal; text-align: center;">{{ number_format($valorInventario, 2) }} Bs</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Detalle de Productos -->
    <div style="display: flex; justify-content: center;">
        <table style="width: 100%; border: 1px solid #f06292; border-radius: 10px; padding: 10px; background-color: #fce4ec;">
            <thead>
                <tr style="background-color: #f06292; color: #ffffff;">
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Código</th>
                    <th style="font-size: 12px; padding: 8px; text-align: left;">Producto</th>
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Lote</th>
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Stock Inicial</th>
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Entradas</th>
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Salidas</th>
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Stock Actual</th>
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Vencimiento</th>
                    <th style="font-size: 12px; padding: 8px; text-align: right;">Valor Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr style="background-color: #fce4ec;">
                        <td style="font-size: 12px; text-align: center;">{{ $producto->codigo_producto }}</td>
                        <td style="font-size: 12px; text-align: left;">{{ $producto->nombre_producto }}</td>
                        <td style="font-size: 12px; text-align: center;">{{ $producto->lote }}</td>
                        <td style="font-size: 12px; text-align: center;">{{ $producto->stock_inicial }}</td>
                        <td style="font-size: 12px; text-align: center;">{{ $producto->total_entradas }}</td>
                        <td style="font-size: 12px; text-align: center;">{{ $producto->total_salidas }}</td>
                        <td style="font-size: 12px; text-align: center;">{{ $producto->stock }}</td>
                        <td style="font-size: 12px; text-align: center;">
                            @if($producto->fecha_vencimiento)
                                <span style="color: {{ \Carbon\Carbon::parse($producto->fecha_vencimiento)->isPast() ? '#d32f2f' : '#2e7d32' }}">
                                    {{ \Carbon\Carbon::parse($producto->fecha_vencimiento)->format('d/m/Y') }}
                                </span>
                            @else
                                N/A
                            @endif
                        </td>
                        <td style="font-size: 12px; text-align: right;">{{ number_format($producto->stock * $producto->precio, 2) }} Bs</td>
                    </tr>
                    
                    <!-- Detalle de Movimientos -->
                    @if($producto->movimientos && count($producto->movimientos) > 0)
                        <tr>
                            <td colspan="9" style="padding: 0;">
                                <table style="width: 100%; background-color: #fff5f8; font-size: 11px;">
                                    <thead>
                                        <tr style="background-color: #f8bbd0;">
                                            <th style="padding: 5px;">Fecha</th>
                                            <th>Tipo</th>
                                            <th>Cantidad</th>
                                            <th>Referencia</th>
                                            <th>Observación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($producto->movimientos as $movimiento)
                                            <tr>
                                                <td style="padding: 3px;">{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <span style="color: {{ $movimiento->tipo_movimiento === 'entrada' ? '#2e7d32' : '#d32f2f' }}">
                                                        {{ ucfirst($movimiento->tipo_movimiento) }}
                                                    </span>
                                                </td>
                                                <td>{{ $movimiento->cantidad }}</td>
                                                <td>{{ $movimiento->referencia }}</td>
                                                <td>{{ $movimiento->observacion }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <br>
    <div style="text-align: center; font-size: 12px; color: #880e4f; margin-top: 20px;">
        <p>Reporte generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
@endsection 