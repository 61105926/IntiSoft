<div class="text-center">
    @if ($caja->state == 0)
        <button class="btn btn-success btn-rounded-1 d-inline-block" type="button" data-bs-toggle="modal"
            data-bs-target="#nuevaEntrada" data-placement="top" title="Entrada Manual"><i class="far fa-money-bill-alt"></i>
            Entrada Manual</button>

        <button class="btn btn-secondary btn-rounded-1 d-inline-block" type="button" data-bs-toggle="modal"
            data-bs-target="#salidaEntrada" data-placement="top" title="Salida Manual"><i
                class="far fa-money-bill-alt"></i>
            Salida Manual</button>
    @endif
</div>


<!-- Modal nueva entrada-->
<div class="modal fade" id="nuevaEntrada" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div x-data="{ submitted: false }">

                <form action="{{ route('cajaEntrada.store') }}" method="POST" enctype="multipart/form-data"
                    onsubmit="document.getElementById('guardarBtn').disabled = true;">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Entrada a Caja :
                            {{ $caja->id }} </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" value="{{ $caja->id }}" id="caja_id" name="caja_id">
                            <label for="price" class="form-label">Monto de apertura :</label>
                            <input id="monto" type="number" name="monto" class="form-control" tabindex="2"
                                placeholder="Bs,BOB" required step="any">

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
                    </div>
                    <div class="modal-footer">
                        <button id="guardarBtn" type="submit" class="btn btn-primary">Agregar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
<!-- Modal salida entrada-->
<div class="modal fade" id="salidaEntrada" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div x-data="{ submitted: false }">
                <form action="{{ route('cajaSalida.store') }}" method="POST" enctype="multipart/form-data"
                    onsubmit="document.getElementById('guardarBtnS').disabled = true;">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Salida de Caja :
                            {{ $caja->id }} </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="price" class="form-label">Monto de salida :</label>
                            <input id="monto" type="number" name="monto" class="form-control" tabindex="2"
                                placeholder="Bs,BOB" required step="any">

                            <input type="hidden" value="{{ $caja->id }}" name="caja_id">
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
                    </div>
                    <div class="modal-footer">
                        <button id="guardarBtnS" type="submit" class="btn btn-primary">Agregar</button>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
