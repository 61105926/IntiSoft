<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalle Venta</title>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 0.875rem;
            font-weight: normal;
            line-height: 1.5;
            margin-top: 2cm;
        }

        /** Definir las reglas del encabezado **/
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            background-color: #ffffff;
            color: rgb(0, 0, 0);
            text-align: center;
            padding: 10px;
            max-width: 100%;
            margin-top: -35px
        }

        /* Estilos del logo */
        .logo {
            float: left;
            width: 50px;
            height: 50px;
            background-size: contain;
            background-repeat: no-repeat;
            margin-right: 10px;
            margin-top: -20px;
            margin-left: 25px;

        }

        .logo-texto {
            font-size: 14px;
            color: #333;
            text-align: center;
        }

        .texto-centro {
            vertical-align: middle;
            margin-left: 20px;
            margin-right: 20px;
            margin-top: 15px;
            font-size: 15px;
        }

        .texto-derecha {
            float: right;
            margin-right: -10px;

            font-size: 13px;
        }

        .texto-derecha-abajo {
            margin-right: -10px;
            margin-top: -45px;
            float: right;
            clear: right;
            font-size: 13px;
        }

        /** Definir las reglas del pie de página **/
        /* footer {
            background-color: #ffffff;
            position: fixed;
            bottom: -50px;
            left: 0cm;
            right: 0cm;
            height: 2cm;
        } */

        .table {
            display: table;
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
        }

        .table-bordered {
            border: 1px solid #c2cfd6;
        }

        thead {
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
        }

        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }

        .table th,
        .table td {
            padding: 0.3, 5rem;
            vertical-align: top;
            border-top: 1px solid #c2cfd6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #c2cfd6;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #c2cfd6;
        }

        th,
        td {
            display: table-cell;
            vertical-align: inherit;
        }

        th {
            font-weight: bold;
            text-align: -internal-center;
            text-align: left;
        }

        tbody {
            display: table-row-group;
            vertical-align: middle;
            border-color: inherit;
        }

        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .izquierda {
            float: left;
        }

        .derecha {
            float: right;
        }

        .row {
            display: inline-block;
        }

        .col,
        .col-1,
        .col-10,
        .col-11,
        .col-12,
        .col-2,
        .col-3,
        .col-4,
        .col-5,
        .col-6,
        .col-7,
        .col-8,
        .col-9 {
            position: relative;
            width: 100%;
        }

        .col-6 {
            display: inline-block;
            max-width: 49%;
        }

        .col-5 {
            display: inline-block;
            max-width: 41.666667%;
        }

        .col-4 {
            display: inline-block;
            max-width: 33.333333%;
        }
    </style>

</head>

<body>
    @yield('content')
</body>

</html>
