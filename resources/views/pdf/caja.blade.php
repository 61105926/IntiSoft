@extends('layouts.print')
@section('content')
    <div class="header" style="padding: 20px; border-bottom: 2px solid #d81b60;; margin-bottom: 20px;">
        <div class="logo" style="display: flex; align-items: center;">
            <div class="logo-image">
                <img src="img/logo.jpg" style="position: absolute; top: 0; left: 0; z-index: -1000; margin-top: -20px"
                    alt="" width="110" height="110">
            </div>

        </div>
        <div class="texto-centro"
            style="margin-right:80px; font-size: 20px; color: #e91e63; font-weight: bold; text-align: center;">
            REPORTE DE CAJA
        </div>

        <div class="texto-derecha-abajo" style="font-size: 12px; color: #d81b60; text-align: right;">
            <p style="margin: 0;">FECHA: {{ date('d/m/Y') }}</p>
            <p style="margin: 0;">Nº Caja: {{ $caja->id }}</p>
            <p style="margin: 0;">USUARIO: {{ $caja->user->names }}</p>
        </div>
    </div>

    <div class="texto-centro" style="font-size: 15px; color: #e91e63; font-weight: bold; text-align: center;">
        ENTRADAS EN EFECTIVO
    </div>

    <table style="width: 100%; margin-top: 20px; font-family: Arial, sans-serif; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f8bbd0; text-align: center;">
                <td style="font-size: 12px; color: #880e4f; padding: 8px;">ID</td>
                <td style="font-size: 12px; color: #880e4f; padding: 8px;">Tipo</td>
                <td style="font-size: 12px; color: #880e4f; padding: 8px;">Pago</td>
                <td style="font-size: 12px; color: #880e4f; padding: 8px;">Descripción</td>
                <td style="font-size: 12px; color: #880e4f; padding: 8px;">Fecha</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($cajaEntrada as $cajaEntradas)
                <tr style="background-color: #FAFAFA; text-align: center;">
                    <td style="font-size: 12px; padding: 8px;">{{ $cajaEntradas->id }}</td>
                    <td style="font-size: 12px; padding: 8px;">{{ $cajaEntradas->type }}</td>
                    <td style="font-size: 12px; padding: 8px;">{{ $cajaEntradas->monto }}</td>
                    <td style="font-size: 12px; padding: 8px;">{{ $cajaEntradas->description }}</td>
                    <td style="font-size: 12px; padding: 8px;">
                        {{ \Carbon\Carbon::parse($cajaEntradas->created_at)->format('d/m/Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8bbd0; font-weight: bold; text-align: right;">
                <td colspan="2" style="padding: 8px; border-top: 1px solid #f8bbd0; ">Total:</td>
                <td style="padding: 8px; border-top: 1px solid #f8bbd0; ">{{ $caTotal }}</td>
                <td colspan="2" style="border-top: 1px solid #f8bbd0; "></td>
            </tr>
        </tfoot>
    </table>

    <h4
        style="text-align: center; font-weight: normal; margin-right: 50px; font-size: 15px; color: #e91e63; font-weight: bold;">
        ENTRADAS POR DEBITO</h4>

    <table style="width: 100%; margin-top: 20px; font-family: Arial, sans-serif; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f8bbd0; text-align: center;">
                <td style="font-size: 12px; color: #880e4f; padding: 8px;">ID</td>
                <td style="font-size: 12px; color: #880e4f; padding: 8px;">Tipo</td>
                <td style="font-size: 12px; color: #880e4f; padding: 8px;">Pago</td>
                <td style="font-size: 12px; color: #880e4f; padding: 8px;">Descripción</td>
                <td style="font-size: 12px; color: #880e4f; padding: 8px;">Fecha</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($cajaOperacion->where('type', 'Deposito') as $cajaDeposito)
                <tr style="background-color: #FAFAFA; text-align: center;">
                    <td style="font-size: 12px;  padding: 8px;">{{ $cajaDeposito->id }}</td>
                    <td style="font-size: 12px;  padding: 8px;">{{ $cajaDeposito->type }}</td>
                    <td style="font-size: 12px;  padding: 8px;">{{ $cajaDeposito->monto }}</td>
                    <td style="font-size: 12px;  padding: 8px;">{{ $cajaDeposito->description }}</td>
                    <td style="font-size: 12px;  padding: 8px;">
                        {{ \Carbon\Carbon::parse($cajaDeposito->created_at)->format('d/m/Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8bbd0; font-weight: bold; text-align: right;">
                <td colspan="2" style="padding: 8px; border-top: 1px solid #f8bbd0;">Total:</td>
                <td style="padding: 8px; border-top: 1px solid #f8bbd0;">
                    {{ $cajaOperacion->where('type', 'Deposito')->sum('monto') }}</td>
                <td colspan="2" style="border-top: 1px solid #f8bbd0;"></td>
            </tr>
        </tfoot>
    </table>

    <h4
        style="text-align: center; font-weight: normal; margin-right: 50px; margin-top: 30px; font-size: 15px; color: #e91e63; font-weight: bold;">
        ENTRADAS POR TRANSFERENCIA
    </h4>
    <table style="width: 100%; margin-top: -10px; font-family: Arial, sans-serif; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f8bbd0; text-align: center;">
                <th style="font-size: 12px; padding: 8px; color: #880e4f; font-weight: normal;">ID</th>
                <th style="font-size: 12px; padding: 8px; color: #880e4f; font-weight: normal;">Tipo</th>
                <th style="font-size: 12px; padding: 8px; color: #880e4f; font-weight: normal;">Pago</th>
                <th style="font-size: 12px; padding: 8px; color: #880e4f; font-weight: normal; background-color: #f8bbd0;">
                    Descripción</th>
                <th style="font-size: 12px; padding: 8px; color: #880e4f; font-weight: normal; background-color: #f8bbd0;">
                    Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cajaOperacion->where('type', 'Transferencia') as $cajaTransferencia)
                <tr style="background-color: #FAFAFA; text-align: left;">
                    <td style="font-size: 12px; padding: 8px; font-weight: normal;">{{ $cajaTransferencia->id }}</td>
                    <td style="font-size: 12px; padding: 8px; font-weight: normal;">{{ $cajaTransferencia->type }}</td>
                    <td style="font-size: 12px; padding: 8px; font-weight: normal;">{{ $cajaTransferencia->monto }}</td>
                    <td style="font-size: 12px; padding: 8px; font-weight: normal;">{{ $cajaTransferencia->description }}
                    </td>
                    <td style="font-size: 12px; padding: 8px; font-weight: normal;">
                        {{ \Carbon\Carbon::parse($cajaTransferencia->created_at)->format('d/m/Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8bbd0; font-weight: bold; text-align: right;">
                <td colspan="2" style="padding: 8px; border-top: 1px solid #f8bbd0;">Total:</td>
                <td style="padding: 8px; border-top: 1px solid #f8bbd0;">
                    {{ $cajaOperacion->where('type', 'Transferencia')->sum('monto') }}</td>
                <td colspan="2" style="border-top: 1px solid #f8bbd0;"></td>
            </tr>
        </tfoot>
    </table>


    <h4
        style="text-align: center; font-weight: normal; margin-right: 50px; margin-top: 30px; font-size: 15px; color: #e91e63; font-weight: bold;">
        SALIDAS EN EFECTIVO</h4>

    <table style="width: 100%; margin-top: -10px; font-family: Arial, sans-serif; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f8bbd0; text-align: center;">
                <th style="font-size: 12px; padding: 8px; color: #880e4f; text-align: center; font-weight: normal;">ID</th>
                <th style="font-size: 12px; padding: 8px; color: #880e4f; text-align: center; font-weight: normal;">Tipo</th>
                <th style="font-size: 12px; padding: 8px; color: #880e4f; text-align: center; font-weight: normal;">Pago</th>
                <th style="font-size: 12px; padding: 8px; color: #880e4f; text-align: center; font-weight: normal; background-color: #f8bbd0;">
                    Descripción</th>
                <th style="font-size: 12px; padding: 8px; color: #880e4f; text-align: center; font-weight: normal; background-color: #f8bbd0;">
                    Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cajaSalida as $cajaSalidas)
                <tr style="background-color: #FAFAFA; text-align: left;">
                    <td style="font-size: 12px; padding: 8px;text-align: center; font-weight: normal;">{{ $cajaSalidas->id }}</td>
                    <td style="font-size: 12px; padding: 8px;text-align: center; font-weight: normal;">{{ $cajaSalidas->type }}</td>
                    <td style="font-size: 12px; padding: 8px;text-align: center; font-weight: normal;">{{ $cajaSalidas->monto }}</td>
                    <td style="font-size: 12px; padding: 8px;text-align: center; font-weight: normal;">{{ $cajaSalidas->description }}</td>
                    <td style="font-size: 12px; padding: 8px;text-align: center; font-weight: normal;">
                        {{ \Carbon\Carbon::parse($cajaSalidas->created_at)->format('d/m/Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8bbd0; font-weight: bold; text-align: right;">
                <td colspan="2" style="padding: 8px; border-top: 1px solid #f8bbd0;">Total:</td>
                <td style="padding: 8px; border-top: 1px solid #f8bbd0;">{{ number_format($csTotal) }}</td>
                <td colspan="2" style="border-top: 1px solid #f8bbd0;"></td>
            </tr>
        </tfoot>
    </table>

    <h4
        style="text-align: center; font-weight: normal; margin-right: 50px; margin-top: 30px; font-size: 15px; color: #e91e63; font-weight: bold;">
        SALIDAS POR OPERACION</h4>
    <table style="width: 100%; margin-top: -10px; font-family: Arial, sans-serif; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f8bbd0; text-align: center;">
                <th style="font-size: 12px; padding: 8px; color: #880e4f; text-align: center;  font-weight: normal;">ID
                </th>
                <th style="font-size: 12px; padding: 8px; color: #880e4f; text-align: center;  font-weight: normal;">Tipo
                </th>
                <th style="font-size: 12px; padding: 8px; color: #880e4f; text-align: center; font-weight: normal;">Pago
                </th>
                <th
                    style="font-size: 12px; padding: 8px; color: #880e4f; text-align: center; font-weight: normal; background-color: #f8bbd0;">
                    Descripción</th>
                <th
                    style="font-size: 12px; padding: 8px; color: #880e4f;  text-align: center; font-weight: normal; background-color: #f8bbd0;">
                    Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cajaOperacionSalida as $cajaOperacions)
                <tr style="background-color: #fafafa; text-align: left;">
                    <td style="font-size: 12px; padding: 8px;text-align: center; font-weight: normal;">{{ $cajaOperacions->id }}</td>
                    <td style="font-size: 12px; padding: 8px;text-align: center; font-weight: normal;">{{ $cajaOperacions->type }}</td>
                    <td style="font-size: 12px; padding: 8px;text-align: center; font-weight: normal;">{{ $cajaOperacions->monto }}</td>
                    <td style="font-size: 12px; padding: 8px;text-align: center; font-weight: normal;">{{ $cajaOperacions->description }}
                    </td>
                    <td style="font-size: 12px; padding: 8px; font-weight: normal;">
                        {{ \Carbon\Carbon::parse($cajaOperacions->created_at)->format('d/m/Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8bbd0; font-weight: bold; text-align: right;">
                <td colspan="2" style="padding: 8px; border-top: 1px solid #f8bbd0;">Total:</td>
                <td style="padding: 8px; border-top: 1px solid #f8bbd0;">{{ $cosTotal }}</td>
                <td colspan="2" style="border-top: 1px solid #f8bbd0;"></td>
            </tr>
        </tfoot>
    </table>


    <h4
        style="text-align: center; font-weight: normal; margin-right: 50px; margin-top: 30px; font-size: 15px; color: #e91e63; font-weight: bold;">
        RESUMEN GENERAL</h4>

    <table style="width: 100%; font-family: Arial, sans-serif; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f06292; text-align: center;">
                <th style="font-size: 14px; padding: 6px;text-align: center; font-weight: normal; color: #fff;">Descripción</th>
                <th style="font-size: 14px; padding: 6px;text-align: center; font-weight: normal; color: #fff;">Efectivo</th>
                <th style="font-size: 14px; padding: 6px;text-align: center; font-weight: normal; color: #fff;">Operación</th>
                <th style="font-size: 14px; padding: 6px;text-align: center; font-weight: normal; background-color: #9e9e9e; color: #fff;">
                    Total</th>
            </tr>
        </thead>
        <tbody>
            <tr style="background-color: #fbafc9; text-align: center;">
                <td style="font-size: 14px; font-weight: normal;"><b>Entradas</b></td>
                <td style="font-size: 14px; font-weight: normal;">{{ $caTotal }}</td>
                <td style="font-size: 14px; font-weight: normal;">{{ $coTotal }}</td>
                <td style="font-size: 14px; font-weight: normal;">{{ $caTotal + $coTotal }}</td>
            </tr>
            <tr style="background-color: #f8bbd0; text-align: center;">
                <td style="font-size: 14px; font-weight: normal;"><b>Salidas</b></td>
                <td style="font-size: 14px; font-weight: normal;">{{ $csTotal }}</td>
                <td style="font-size: 14px; font-weight: normal;">{{ $cosTotal }}</td>
                <td style="font-size: 14px; font-weight: normal;">{{ $csTotal + $cosTotal }}</td>
            </tr>
            <tr style="background-color: #ff75a3; text-align: center;">
                <td style="font-size: 14px; font-weight: normal;"><b>Total</b></td>
                <td style="font-size: 14px; font-weight: normal;">{{ $caTotal - $csTotal }}</td>
                <td style="font-size: 14px; font-weight: normal;">{{ $coTotal - $cosTotal }}</td>
                <td style="font-size: 14px; font-weight: normal;">
                    {{ $caTotal + $coTotal - ($csTotal + $cosTotal) }}
                </td>
            </tr>
        </tbody>
    </table>
@endsection
