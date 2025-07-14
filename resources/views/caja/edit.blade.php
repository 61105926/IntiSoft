@extends('layouts.theme.app')
@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-sm-6">
                <h4>Detalles de Caja</h4>
            </div>
            @include('caja.form.header')
            <div class="container-fluid">
                <div class="row">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="h6">Datos técnicos</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="small mb-1">Usuario</label>
                                    <input class="form-control" type="text" name="dni"
                                        value="{{ $caja->user->names }}" readonly="">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="small mb-1">Fecha de apertura </label>
                                    <input class="form-control" style="text-transform:uppercase" type="text"
                                        name="first_name" value="{{ $caja->created_at }}" readonly="">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="small mb-1">Fecha de cierre</label>
                                    <input class="form-control" style="text-transform:uppercase" type="text"
                                        name="last_name" value="{{ $caja->updated_at }}" readonly="">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="small mb-1">Identificador</label>
                                    <input class="form-control" id="id" style="text-transform:uppercase"
                                        type="text" name="last_name" value="{{ $caja->id }}" readonly="">
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="small mb-1">Tipo de moneda</label>
                                    <input class="form-control" style="text-transform:uppercase" type="text"
                                        name="last_name" value="Bolivianos" readonly="">
                                </div>
                                @if ($caja->state == '0')
                                    <div class="form-group col-md-4">
                                        <label class="small mb-1">Estado</label>
                                        <button class="form-control btn btn-success" type="button" data-bs-toggle="modal"
                                            data-bs-target="#confirm" data-placement="top" title="Delete">ABIERTO
                                        </button>
                                        @include('caja.alert.confirm')
                                    </div>
                                @else
                                    <div class="form-group col-md-4">
                                        <label class="small mb-1">Estado</label>
                                        <a href="#" class="form-control btn btn-secondary">
                                            CERRADO
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Entradas de Efectivo</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">

                                    <table class="table table-bordered responsive ">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tipo</th>
                                                <th>Monto</th>
                                                <th>Descripcion</th>
                                                <th>Fecha </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cajaEntrada as $cajaEntradas)
                                                <tr>
                                                    <td>{{ $cajaEntradas->id }}</td>
                                                    <td>{{ $cajaEntradas->type }}</td>
                                                    <td>{{ $cajaEntradas->monto }}</td>
                                                    <td>{{ $cajaEntradas->description }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($cajaEntradas->created_at)->format('d/m/Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #f2f2f2; border-top: 2px solid #000;">
                                                <td colspan="2"
                                                    style="font-size:14px; text-align: right; font-weight: bold; padding: 6px; border: 1px solid #ddd;">
                                                    Total:</td>
                                                <td
                                                    style="font-size:14px; text-align: left; font-weight: bold; padding: 6px; border: 1px solid #ddd;">
                                                    {{ $caTotal }}
                                                </td>
                                                <td colspan="2" style="border: 1px solid #ddd;"></td>
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

                                    <table class="table table-bordered responsive ">
                                        <thead class="">
                                            <tr>
                                                <th>ID</th>
                                                <th>Tipo</th>
                                                <th>Monto</th>
                                                <th>Descripcion</th>
                                                <th>Fecha </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cajaSalida as $cajaSalidas)
                                                <tr>
                                                    <td>{{ $cajaSalidas->id }}</td>
                                                    <td>{{ $cajaSalidas->type }}</td>
                                                    <td>{{ $cajaSalidas->monto }}</td>
                                                    <td>{{ $cajaSalidas->description }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($cajaSalidas->created_at)->format('d/m/Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #f2f2f2; border-top: 2px solid #000;">
                                                <td colspan="2"
                                                    style="font-size:14px; text-align: right; font-weight: bold; padding: 6px; border: 1px solid #ddd;">
                                                    Total:</td>
                                                <td
                                                    style="font-size:14px; text-align: left; font-weight: bold; padding: 6px; border: 1px solid #ddd;">
                                                    {{ $csTotal }}
                                                </td>
                                                <td colspan="2" style="border: 1px solid #ddd;"></td>
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
                                <h3 class="card-title">Entradas por Debito</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered responsive">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tipo</th>
                                                <th>Monto</th>
                                                <th>Descripción</th>
                                                <th>Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cajaOperacion->where('type', 'Deposito') as $cajaDeposito)
                                                <tr>
                                                    <td>{{ $cajaDeposito->id }}</td>
                                                    <td>{{ $cajaDeposito->type }}</td>
                                                    <td>{{ $cajaDeposito->monto }}</td>
                                                    <td>{{ $cajaDeposito->description }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($cajaDeposito->created_at)->format('d/m/Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #f2f2f2; border-top: 2px solid #000;">
                                                <td colspan="2" style="font-size:14px; text-align: right; font-weight: bold; padding: 6px; border: 1px solid #ddd;">
                                                    Total:
                                                </td>
                                                <td style="font-size:14px; text-align: left; font-weight: bold; padding: 6px; border: 1px solid #ddd;">
                                                    {{ $cajaOperacion->where('type', 'Deposito')->sum('monto') }}
                                                </td>
                                                <td colspan="2" style="border: 1px solid #ddd;"></td>
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
                                <h3 class="card-title">Entradas por Transferencia</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered responsive">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Tipo</th>
                                                <th>Monto</th>
                                                <th>Descripción</th>
                                                <th>Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cajaOperacion->where('type', 'Transferencia') as $cajaTransferencia)
                                                <tr>
                                                    <td>{{ $cajaTransferencia->id }}</td>
                                                    <td>{{ $cajaTransferencia->type }}</td>
                                                    <td>{{ $cajaTransferencia->monto }}</td>
                                                    <td>{{ $cajaTransferencia->description }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($cajaTransferencia->created_at)->format('d/m/Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #f2f2f2; border-top: 2px solid #000;">
                                                <td colspan="2" style="font-size:14px; text-align: right; font-weight: bold; padding: 6px; border: 1px solid #ddd;">
                                                    Total:
                                                </td>
                                                <td style="font-size:14px; text-align: left; font-weight: bold; padding: 6px; border: 1px solid #ddd;">
                                                    {{ $cajaOperacion->where('type', 'Transferencia')->sum('monto') }}
                                                </td>
                                                <td colspan="2" style="border: 1px solid #ddd;"></td>
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
                                    <table class="table table-bordered responsive ">
                                        <thead class="">
                                            <tr>
                                                <th>ID</th>
                                                <th>Tipo</th>
                                                <th>Monto</th>
                                                <th>Descripcion</th>
                                                <th>Fecha </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cajaOperacionSalida as $cajaOperacionsalidas)
                                                <tr>
                                                    <td>{{ $cajaOperacionsalidas->id }}</td>
                                                    <td>{{ $cajaOperacionsalidas->type }}</td>
                                                    <td>{{ $cajaOperacionsalidas->monto }}</td>
                                                    <td>{{ $cajaOperacionsalidas->description }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($cajaOperacionsalidas->created_at)->format('d/m/Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #f2f2f2; border-top: 2px solid #000;">
                                                <td colspan="2"
                                                    style="font-size:14px; text-align: right; font-weight: bold; padding: 6px; border: 1px solid #ddd;">
                                                    Total:</td>
                                                <td
                                                    style="font-size:14px; text-align: left; font-weight: bold; padding: 6px; border: 1px solid #ddd;">
                                                    {{ $cosTotal }}
                                                </td>
                                                <td colspan="2" style="border: 1px solid #ddd;"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Resumen General</h3>
                            </div>
                            <div class="card-body">
                                <div class="table responsive">
                                    <table id="" class="table table-bordered responsive ">
                                        <thead class=" ">
                                            <tr>
                                                <th>Descripcion</th>
                                                <th>Efectivo</th>
                                                <th>Operacion</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><b>Entradas</b></td>
                                                <td><b>{{ $caTotal }}</b></td>
                                                <td><b>{{ $coTotal }}</b></td>
                                                <td><b>{{ $caTotal + $coTotal }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Salidas</b></td>
                                                <td><b>{{ $csTotal }}</b></td>
                                                <td><b>{{ $cosTotal }}</b></td>
                                                <td><b>{{ $csTotal + $cosTotal }}</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Total</b></td>
                                                <td><b>{{ $caTotal - $csTotal }}</b></td>
                                                <td><b>{{ $coTotal - $cosTotal }}</b></td>
                                                <td><b>{{ $caTotal + $coTotal - ($csTotal + $cosTotal) }}</b></td>
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
    </div>
@stop
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
@stop
@section('js')
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('venta') == 'ok')
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'La venta se realizo correctamente.',
                showConfirmButton: false,
                timer: 1500
            })
        </script>
    @endif

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#EM').DataTable({
                "order": [
                    [0, 'desc']
                ],
                "lengthMenu": [
                    [5, 10, 50, -1],
                    [5, 10, 50, "Todo"]
                ],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "Ningun registro encontrado",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    'search': 'Buscar:',
                    'paginate': {
                        'next': 'Siguiente',
                        'previous': 'Anterior'
                    }
                },
                "responsive": 'true',
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#SM').DataTable({
                "order": [
                    [0, 'desc']
                ],
                "lengthMenu": [
                    [5, 10, 50, -1],
                    [5, 10, 50, "Todo"]
                ],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "Ningun registro encontrado",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    'search': 'Buscar:',
                    'paginate': {
                        'next': 'Siguiente',
                        'previous': 'Anterior'
                    }
                },
                "responsive": 'true',
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#RG').DataTable({
                "order": [
                    [0, 'desc']
                ],
                "lengthMenu": [
                    [5, 10, 50, -1],
                    [5, 10, 50, "Todo"]
                ],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "Ningun registro encontrado",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    'search': 'Buscar:',
                    'paginate': {
                        'next': 'Siguiente',
                        'previous': 'Anterior'
                    }
                },
                "responsive": 'true',
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            $('#CO').DataTable({
                "order": [
                    [0, 'desc']
                ],
                "lengthMenu": [
                    [5, 10, 50, -1],
                    [5, 10, 50, "Todo"]
                ],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "Ningun registro encontrado",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    'search': 'Buscar:',
                    'paginate': {
                        'next': 'Siguiente',
                        'previous': 'Anterior'
                    }
                },
                "responsive": 'true',
            });
        });
    </script>
@stop
