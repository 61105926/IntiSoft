<div class="modal fade" id="confirm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('cajaEntrada.update', $caja->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title " style="text-align: center" id="exampleModalLabel">Cerrar Caja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <lottie-player src="https://assets4.lottiefiles.com/packages/lf20_p7ki6kij.json"
                        background="transparent" speed="1" style="width: 100%; height: 50%;" loop autoplay>
                    </lottie-player>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Cerrar Caja</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>

            </form>

        </div>
    </div>
</div>
