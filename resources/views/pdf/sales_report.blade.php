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
            Reporte de Venta
        </div>
        <div class="texto-derecha-abajo" style="font-size: 14px; color: #d81b60; margin-top: 10px;">
            Fecha:
            @if ($fechaInicio && $fechaFin)
                {{ $fechaInicio }} hasta {{ $fechaFin }}
            @else
                Actualidad
            @endif
        </div>
    </div>

    <br><br>




    @php
        $totalSubtotal = 0;
        $totalDescuento = 0;
        $totalMontoCompra;
        $gananciaTotal = 0;

    @endphp

    @foreach ($ventas as $venta)
        @foreach ($venta->detalles as $detail)
            @php
                $totalSubtotal += $detail->quantity * $detail->price;
                $totalMontoCompra += $detail->quantity * ($detail->producto->monto_comprado ?? 0);
            @endphp
        @endforeach
    @endforeach
    @php
        $gananciaTotal = $totalSubtotal-$totalMontoCompra;
    @endphp
    <div style="display: flex; justify-content: center; margin-bottom: 20px;">
        <table
            style="width: 100%; border: 1px solid #e91e63; border-radius: 10px; padding: 10px; background-color: #fce4ec;">
            <thead>
                <tr style="background-color: #f8bbd0;">
                    <th style="font-size: 14px; color: #880e4f; padding: 10px; text-align: center;">Total de Ventas</th>
                    <th style="font-size: 14px; color: #880e4f; padding: 10px; text-align: center;">Total de Productos
                        Vendidos</th>
                    <th style="font-size: 14px; color: #880e4f; padding: 10px; text-align: center;">Monto Total Vendido
                    </th>
                    <th style="font-size: 14px; color: #880e4f; padding: 10px; text-align: center;">Monto Total de Compra
                    </th>
                    <th style="font-size: 14px; color: #880e4f; padding: 10px; text-align: center;">Ganancia Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-size: 14px; font-weight: normal; text-align: center;">{{ $totalVentas }}</td>
                    <td style="font-size: 14px; font-weight: normal; text-align: center;">{{ $totalProductosVendidos }}</td>
                    <td style="font-size: 14px; font-weight: normal; text-align: center;">
                        {{ number_format($totalSubtotal, 2) }} Bs</td>
                    <td style="font-size: 14px; font-weight: normal; text-align: center;">
                        {{ number_format($totalMontoCompra, 2) }} Bs</td>

                    <td style="font-size: 14px; font-weight: normal; text-align: center;">
                        {{ number_format($gananciaTotal, 2) }} Bs</td>
                </tr>
            </tbody>
        </table>
    </div>

    <br>

    <div style="display: flex; justify-content: center;">
        <table
            style="width: 90%; border: 1px solid #f06292; border-radius: 10px; padding: 10px; background-color: #fce4ec;">
            <thead>
                <tr style="background-color: #f06292; color: #ffffff;">
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Cod</th>
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Cantidad</th>
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Descripción</th>
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Precio Unitario</th>
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Descuento</th>
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Subtotal</th>
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Lote</th>
                    <th style="font-size: 12px; padding: 8px; text-align: center;">Monto Compra</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ventas as $venta)
                    @foreach ($venta->detalles as $detail)
                        @php
                            $subtotal = $detail->quantity * $detail->price;
                            $descuento = $detail->discount;
                            $montoCompra = $detail->quantity * ($detail->producto->monto_comprado ?? 0);

                            $totalSubtotal += $subtotal;
                            $totalDescuento += $descuento;
                            $totalMontoCompra += $montoCompra;
                        @endphp
                        <tr style="background-color: #fce4ec;">
                            <td style="font-size:12px; text-align: center;">{{ $detail->id }}</td>
                            <td style="font-size:12px; text-align: center;">{{ $detail->quantity }}</td>
                            <td style="font-size:12px; text-align: left;">{{ $detail->producto->nombre_producto ?? '' }}
                            </td>
                            <td style="font-size:12px; text-align: right;">{{ number_format($detail->price, 2) }}</td>
                            <td style="font-size:12px; text-align: right;">{{ number_format($detail->discount, 2) }}</td>
                            <td style="font-size:12px; text-align: right;">
                                {{ number_format($detail->quantity * $detail->price, 2) }}</td>
                            <td style="font-size:12px; text-align: center;">{{ $detail->producto->lote ?? 'N/A' }}</td>
                            <td style="font-size:12px; text-align: right;">
                                {{ number_format($detail->quantity * ($detail->producto->monto_comprado ?? 0), 2) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

 
    <br>
    <p style="text-align: center; font-size: 16px; font-weight: bold; color: #880e4f;">¡Gracias por su preferencia!</p>
@endsection
