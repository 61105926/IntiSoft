@extends('layouts.theme.app')
@section('content')
    <a href="{{ route('caja.create') }}"class="btn btn-app bg-secondary" style="height: 135px">
        <i class='fas fa-briefcase' style='color:#ffffff; font-size:65px;'></i><br> <b style="font-size: 15px">Nueva Caja</b>
    </a>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de cajas</h3>

            </div>

            <div class="card-body">
                <table id="detalle" class="table table-bordered responsive ">
                    <thead class="">
                        <tr>
                            <th>Caja</th>
                            <th>Usuario</th>
                            <th>Monto</th>
                            <th>Descripcion</th>
                            <th>Fecha apertura</th>
                            <th>Fecha cierre</th>
                            <th>Estado </th>
                            <th>PDF </th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- {{ $caja }} --}}
                        @foreach ($caja as $cajas)
                            <tr>
                                <td>{{ $cajas->id }}</td>
                                <td>{{ $cajas->user->names }}</td>
                                <td>{{ $cajas->total_entrada + $cajas->total_operacion - $cajas->total_salida - $cajas->total_operacion_salida }}
                                </td>
                                <td>{{ $cajas->description }}</td>
                                <td><small class="badge badge-success">
                                        {{ \Carbon\Carbon::parse($cajas->created_at)->format('d/m/Y') }}</small></td>
                                <td><small class="badge badge-success">
                                        {{ \Carbon\Carbon::parse($cajas->updated_at)->format('d/m/Y') }}</small></td>
                                @if ($cajas->state == 0)
                                    <td>
                                        <small class="badge badge-success"> <a class="text-white"
                                                href="cajas/{{ $cajas->id }}"> <i
                                                    class='far fa-clock text-white'></i>-ABIERTO</a></small>

                                    </td>
                                @else
                                    <td>
                                        <small class="badge badge-primary"> <a class="text-white"
                                                href="cajas/{{ $cajas->id }}"> <i
                                                    class='far fa-clock text-white'></i>-CERRADO</a></small>

                                    </td>
                                @endif
                                <td>
                                    <a href="{{ route('reportPdfCaja', $cajas->id) }}" class="btn btn-danger"
                                        target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>



    </div>
@endsection

<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="css/styles.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

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

        $('#detalle').DataTable({

            "order": [
                [0, 'desc']
            ],
            "lengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "Todo"]
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
