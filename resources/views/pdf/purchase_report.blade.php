@extends('layouts.print')

@section('content')
    <div class="header" style="text-align: center; margin-bottom: 20px;">
        <div class="logo">
            <div class="logo-image">
                <img src="img/logo.jpg" alt="Logo" width="100" height="100"
                    style="border-radius: 50%; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            </div>
        </div>
        <div class="texto-centro"
            style="font-size: 24px; color: #e91e63; font-weight: bold; letter-spacing: 2px; margin-top: 10px;">
            Reporte de Compras
        </div>
        <div class="texto-derecha-abajo" style="font-size: 12px; color: #d81b60; margin-top: 10px;">
            Período: 
            @if ($startDate && $endDate)
                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} hasta {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            @else
                Todas las fechas
            @endif
        </div>
    </div>

    <!-- Resumen de Compras -->
    <div style="margin-bottom: 20px;">
        <table style="width: 100%; border: 1px solid #e91e63; border-radius: 10px; padding: 8px; background-color: #fce4ec;">
            <thead>
                <tr style="background-color: #f8bbd0;">
                    <th style="font-size: 12px; color: #880e4f; padding: 8px; text-align: center;">Total de Compras</th>
                    <th style="font-size: 12px; color: #880e4f; padding: 8px; text-align: center;">Total Pagado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-size: 12px; font-weight: normal; text-align: center;">{{ $transactions->count() }}</td>
                    <td style="font-size: 12px; font-weight: normal; text-align: center;">{{ number_format($totalCompras, 2) }} Bs</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Detalles de Compras -->
    <div style="margin-bottom: 20px;">
        <table style="width: 100%; border: 1px solid #f06292; border-radius: 10px; padding: 8px; background-color: #fce4ec;">
            <thead>
                <tr style="background-color: #f06292; color: #ffffff;">
                    <th style="font-size: 10px; padding: 6px; text-align: center;">ID</th>
                    <th style="font-size: 10px; padding: 6px; text-align: center;">Proveedor</th>
                    <th style="font-size: 10px; padding: 6px; text-align: center;">Fecha</th>
                    <th style="font-size: 10px; padding: 6px; text-align: center;">Total</th>
                    <th style="font-size: 10px; padding: 6px; text-align: center;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr style="background-color: #fce4ec;">
                        <td style="font-size: 10px; text-align: center;">{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td style="font-size: 10px; text-align: left;">{{ $transaction->proveedor->proveedor_nombre }}</td>
                        <td style="font-size: 10px; text-align: center;">{{ \Carbon\Carbon::parse($transaction->fecha_compra)->format('d/m/Y') }}</td>
                        <td style="font-size: 10px; text-align: right;">{{ number_format($transaction->monto_pagado, 2) }} Bs</td>
                        <td style="font-size: 10px; text-align: center; color: {{ $transaction->estado_pago == 'Anulado' ? '#d32f2f' : '#2e7d32' }};">
                            {{ $transaction->estado_pago == 'Pendiente' ? 'Activo' : $transaction->estado_pago }}
                        </td>
                        
                    </tr>
                    <!-- Detalles de productos -->
                    <tr style="background-color: #fff3f7;">
                        <td colspan="5" style="padding: 6px;">
                            <div style="font-size: 10px; color: #880e4f;">
                                <strong>Productos:</strong>
                                <ul style="list-style-type: none; padding-left: 10px; margin: 5px 0;">
                                    @foreach($transaction->details as $detail)
                                        <li style="margin-bottom: 3px;">
                                            {{ $detail->product->nombre_producto }}
                                            ({{ $detail->cantidad }} x {{ number_format($detail->precio_compra, 2) }} Bs)
                                            <br>
                                            <small>Lote: {{ $detail->lote }} | 
                                            Vence: {{ \Carbon\Carbon::parse($detail->fecha_vencimiento)->format('d/m/Y') }}</small>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="text-align: center; font-size: 14px; font-weight: bold; color: #880e4f; margin-top: 20px;">
        <p>Total General: {{ number_format($totalCompras, 2) }} Bs</p>
    </div>

    <p style="text-align: center; font-size: 12px; font-weight: bold; color: #880e4f;">¡Gracias por su preferencia!</p>
@endsection 