@extends('layouts.print')

@section('content')
    <div class="header">
        <div class="logo">
            <div class="logo-image"><img src="img/logo.jpg" alt="" width="150" height="150"></div>
        </div>
        <div class="texto-centro" style="font-size: 24px; color: #e91e63; font-weight: bold;">COMPROBANTE</div>
        <div class="texto-derecha-abajo" style="font-size:12px; color: #d81b60;">Nº: VTR-{{ $venta->id }}</div>
        <br>
        <div class="texto-derecha-abajo" style="font-size:12px; color: #d81b60;">USUARIO: {{ $venta->id_user }}</div>
    </div>

    <br><br>

    <table style="width: 70%; margin-left: 30%; ">
        <thead>
            <tr style="background-color: #f8bbd0;">
                <td style="font-size:12px; color: #880e4f;">Nombres y Apellidos:</td>
                <td style="font-size:12px; color: #880e4f;">NºCI:</td>
                <td style="font-size:12px; color: #880e4f;">Celular:</td>
                <td style="font-size:12px; color: #880e4f;">Nacionalidad:</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="font-size:12px; font-weight: normal;">{{ $venta->cliente->nombre_completo }}</th>
                <th style="font-size:12px; font-weight: normal;">{{ $venta->cliente->ci }}</th>
                <th style="font-size:12px; font-weight: normal;">{{ $venta->cliente->numero_telefono }}</th>
                <th style="font-size:12px; font-weight: normal;">{{ $venta->cliente->nacionalidad }}</th>
            </tr>
        </tbody>
    </table>

    <br>

    <table style="width: 90%; margin-left: 5%;">
        <thead>
            <tr style="background-color: #f06292; color: #ffffff;">
                <th style="font-size:12px; text-align: center; padding: 6px;">Cod</th>
                <th style="font-size:12px; text-align: center; padding: 6px;">Cantidad</th>
                <th style="font-size:12px; text-align: center; padding: 6px;">Descripción</th>
                <th style="font-size:12px; text-align: center; padding: 6px;">Precio Unitario</th>
                <th style="font-size:12px; text-align: center; padding: 6px;">Descuento</th>
                <th style="font-size:12px; text-align: center; padding: 6px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($venta->detalles as $detail)
                <tr style="background-color: #fce4ec;">
                    <td style="font-size:12px; text-align: center;">{{ $detail->id }}</td>
                    <td style="font-size:12px; text-align: center;">{{ $detail->quantity }}</td>
                    <td style="font-size:12px; text-align: left;">{{ $detail->producto->nombre_producto ?? '' }}</td>
                    <td style="font-size:12px; text-align: right;">{{ number_format($detail->price, 2) }}</td>
                    <td style="font-size:12px; text-align: right;">{{ number_format($detail->discount, 2) }}</td>
                    <td style="font-size:12px; text-align: right;">
                        {{ number_format($detail->quantity * $detail->price, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4"></td>
                <td style="text-align: right; font-size:12px; font-weight: bold; color: #880e4f;">SUBTOTAL:</td>
                <td style="text-align: right; font-size:12px; color: #880e4f;">Bs: {{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td style="text-align: right; font-size:12px; font-weight: bold; color: #880e4f;">DESCUENTO GENERAL:</td>
                <td style="text-align: right; font-size:12px; color: #880e4f;">Bs: {{ number_format($discount, 2) }}</td>
            </tr>
            <tr style="background-color: #f48fb1;">
                <td colspan="4"></td>
                <td style="text-align: right; font-size:12px; font-weight: bold; color: #ffffff;">TOTAL A PAGAR:</td>
                <td style="text-align: right; font-size:12px; font-weight: bold; color: #ffffff;">Bs:
                    {{ number_format($total_price, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <br>

    <table style="width: 90%; margin-left: 5%;">
        <thead>
            <tr style="background-color: #f06292; color: #ffffff;">
                <th style="font-size:12px; text-align: center; padding: 6px;">Método de Pago</th>
                <th style="font-size:12px; text-align: center; padding: 6px;">Monto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-size:12px;">Efectivo</td>
                <td style="font-size:12px; text-align: right;">Bs: {{ number_format($venta->cash_amount, 2) }}</td>
            </tr>
            <tr>
                <td style="font-size:12px;">Debito</td>
                <td style="font-size:12px; text-align: right;">Bs: {{ number_format($venta->deposit_amount, 2) }}</td>
            </tr>
            <tr>
                <td style="font-size:12px;">Transferencia</td>
                <td style="font-size:12px; text-align: right;">Bs: {{ number_format($venta->transfer_amount, 2) }}</td>
            </tr>
            <tr style="background-color: #f8bbd0;">
                <td style="font-size:12px;">Total Pagado</td>
                <td style="font-size:12px; text-align: right;">Bs: {{ number_format($venta->total_paid, 2) }}</td>
            </tr>
            <tr style="background-color: #f8bbd0;">
                <td style="font-size:12px;">Cambio Devuelto</td>
                <td style="font-size:12px; text-align: right;">Bs: {{ number_format($venta->change, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <br>
    <p style="text-align: center; color: #880e4f; font-weight: bold;">¡Gracias por su preferencia!</p>
@endsection
