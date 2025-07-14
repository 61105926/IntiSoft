@extends('layouts.theme.app')

@section('content')
    <div x-data="{ submitted: false }">

        <form action="/caja" method="POST"onsubmit="document.getElementById('guardarBtn').disabled = true;">
            @csrf

            <div class="container pt-5">
                <div class="abs-center">
                    <div class="card card-primary">
                        <div class="card-header" style="background-color:rgb(0, 55, 100)">
                            <h1 style="font-family: 'Fredoka One', cursive; font-size:38px; text-align:center">REGISTRAR
                                NUEVA
                                CAJA</h1>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">Identificador de caja:</label>
                                <input type="text" class="form-control" tabindex="1" name="caja_id" id="caja_id"
                                    value="{{ $find + 1 }}" readonly>

                            </div>

                            <div class="form-group">
                                <label for="price" class="form-label">Monto de apertura :</label>
                                <input id="monto" type="number" name="monto" class="form-control" tabindex="2"
                                    placeholder="Bs,BOB" required step="any">

                                @if ($errors->has('weight'))
                                    <h6 class="error validators1" for="input-monto"><i class='bx bxs-x-circle'
                                            style='color:#ffffff;'></i>{{ $errors->first('weight') }}</h6>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">Descripción :</label>
                                <textarea id="description" required rows="3" name="description" class="form-control" tabindex="3"
                                    placeholder="Escribe una descripción de la categoria (opcional)."></textarea>
                                @if ($errors->has('description'))
                                    <h6 class="error validators1" for="input-description"><i class='bx bxs-x-circle'
                                            style='color:#ffffff;'></i>{{ $errors->first('description') }}</h6>
                                @endif
                            </div>
                            <div class="card-footer botoncitos">
                                <button id="guardarBtn" type="submit" class="btn btn-primary">Guardar</button>
                                <a href="/caja" class="btn btn-danger mr-4" tabindex="5">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @section('css')
            <link rel="stylesheet" href="/css/styles.css">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Carter+One&family=Fredoka+One&display=swap"
                rel="stylesheet">
        @stop
    </form>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/styles.css">

@stop

@section('js')

@stop
