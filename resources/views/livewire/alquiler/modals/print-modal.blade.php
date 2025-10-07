{{-- MODAL VISTA PREVIA PARA IMPRESIÓN --}}
@if($selectedAlquiler)
<div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg">

            {{-- HEADER --}}
            <div class="modal-header bg-dark text-white border-0">
                <div>
                    <h4 class="modal-title mb-1 fw-bold">
                        <i class="fas fa-print me-2"></i>
                        Vista Previa del Contrato
                    </h4>
                    <p class="mb-0 opacity-75">{{ $selectedAlquiler->numero_contrato }}</p>
                </div>
                <button type="button" class="btn-close btn-close-white" wire:click="closePrintModal"></button>
            </div>

            {{-- BODY - CONTENIDO IMPRIMIBLE --}}
            <div class="modal-body p-4">

                <div id="printable-content" class="bg-white">

                    {{-- ENCABEZADO DEL CONTRATO --}}
                    <div class="contract-header text-center mb-4 pb-3 border-bottom border-2">
                        <h2 class="fw-bold text-primary mb-2">CONTRATO DE ALQUILER</h2>
                        <h5 class="text-muted">Ropa Folklórica</h5>
                        <p class="mb-1"><strong>{{ config('app.name', 'Folcklore') }}</strong></p>
                        <p class="small text-muted mb-0">
                            Sucursal: {{ $selectedAlquiler->sucursal->nombre }} |
                            Contrato N°: <strong>{{ $selectedAlquiler->numero_contrato }}</strong>
                        </p>
                    </div>

                    {{-- INFORMACIÓN DE LAS PARTES --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold text-uppercase border-bottom pb-2 mb-3">
                                <i class="fas fa-users me-2"></i>I. INFORMACIÓN DE LAS PARTES
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>ARRENDADOR:</strong></p>
                            <p class="ms-3 mb-1">{{ config('app.name', 'Folcklore') }}</p>
                            <p class="ms-3 mb-1 small">NIT: [Número de NIT]</p>
                            <p class="ms-3 mb-1 small">Dirección: {{ $selectedAlquiler->sucursal->direccion ?? '[Dirección]' }}</p>
                            <p class="ms-3 mb-1 small">Teléfono: {{ $selectedAlquiler->sucursal->telefono ?? '[Teléfono]' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>ARRENDATARIO:</strong></p>
                            <p class="ms-3 mb-1">{{ $selectedAlquiler->cliente->nombres }} {{ $selectedAlquiler->cliente->apellidos }}</p>
                            <p class="ms-3 mb-1 small">CI: {{ $selectedAlquiler->cliente->carnet_identidad ?? 'N/A' }}</p>
                            <p class="ms-3 mb-1 small">Teléfono: {{ $selectedAlquiler->cliente->telefono ?? 'N/A' }}</p>
                            <p class="ms-3 mb-1 small">Email: {{ $selectedAlquiler->cliente->email ?? 'N/A' }}</p>
                            @if($selectedAlquiler->unidadEducativa)
                            <p class="ms-3 mb-1 small">U.E.: {{ $selectedAlquiler->unidadEducativa->nombre }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- DETALLE DEL ALQUILER --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold text-uppercase border-bottom pb-2 mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>II. PERÍODO Y DURACIÓN DEL ALQUILER
                            </h6>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 small text-muted">Fecha de Entrega:</p>
                            <p class="fw-bold">{{ \Carbon\Carbon::parse($selectedAlquiler->fecha_alquiler)->format('d/m/Y') }}</p>
                            <p class="small">Hora: {{ $selectedAlquiler->hora_entrega }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 small text-muted">Fecha de Devolución:</p>
                            <p class="fw-bold">{{ \Carbon\Carbon::parse($selectedAlquiler->fecha_devolucion_programada)->format('d/m/Y') }}</p>
                            <p class="small">Hora: {{ $selectedAlquiler->hora_devolucion_programada }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 small text-muted">Duración:</p>
                            <p class="fw-bold">{{ $selectedAlquiler->dias_alquiler }} día(s)</p>
                        </div>
                    </div>

                    {{-- CONJUNTOS FOLKLÓRICOS ALQUILADOS --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold text-uppercase border-bottom pb-2 mb-3">
                                <i class="fas fa-users me-2"></i>III. DETALLE DE CONJUNTOS FOLKLÓRICOS ALQUILADOS
                            </h6>

                            @php
                                $detallesConConjuntos = $selectedAlquiler->detalles->filter(fn($d) => $d->instanciaConjunto);
                            @endphp

                            @if($detallesConConjuntos->count() > 0)
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="8%">N°</th>
                                        <th width="40%">Conjunto Folklórico</th>
                                        <th width="20%">Variación</th>
                                        <th width="12%">N° Serie</th>
                                        <th width="10%" class="text-end">Precio</th>
                                        <th width="10%" class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detallesConConjuntos as $index => $detalle)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $detalle->instanciaConjunto->variacionConjunto->conjunto->nombre ?? 'Conjunto' }}</strong>
                                            @if($detalle->instanciaConjunto->instanciaComponentes)
                                            <br><small class="text-muted">({{ $detalle->instanciaConjunto->instanciaComponentes->count() }} componentes)</small>
                                            @endif
                                        </td>
                                        <td>{{ $detalle->instanciaConjunto->variacionConjunto->nombre_variacion ?? 'N/A' }}</td>
                                        <td class="text-center"><small>{{ $detalle->instanciaConjunto->numero_serie }}</small></td>
                                        <td class="text-end">Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                                        <td class="text-end">Bs. {{ number_format($detalle->subtotal, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="alert alert-warning">
                                <strong>Nota:</strong> Este alquiler no tiene conjuntos folklóricos registrados.
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- RESUMEN FINANCIERO --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            @if($selectedAlquiler->tieneGarantia())
                            <div class="border rounded p-3">
                                <h6 class="fw-bold mb-3"><i class="fas fa-shield-alt text-warning me-2"></i>GARANTÍA</h6>
                                <p class="mb-1"><strong>Tipo:</strong> {{ $selectedAlquiler->tipo_garantia_display }}</p>
                                @if($selectedAlquiler->documento_garantia)
                                <p class="mb-1"><strong>Documento:</strong> {{ $selectedAlquiler->documento_garantia }}</p>
                                @endif
                                @if($selectedAlquiler->monto_garantia > 0)
                                <p class="mb-1"><strong>Monto:</strong> Bs. {{ number_format($selectedAlquiler->monto_garantia, 2) }}</p>
                                @endif
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end" width="120">Bs. {{ number_format($selectedAlquiler->subtotal, 2) }}</td>
                                </tr>
                                @if($selectedAlquiler->descuento > 0)
                                <tr>
                                    <td class="text-end">Descuento:</td>
                                    <td class="text-end">- Bs. {{ number_format($selectedAlquiler->descuento, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="border-top">
                                    <td class="text-end"><strong>TOTAL:</strong></td>
                                    <td class="text-end"><strong>Bs. {{ number_format($selectedAlquiler->total, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-end">Anticipo/Pagado:</td>
                                    <td class="text-end">Bs. {{ number_format($selectedAlquiler->anticipo, 2) }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="text-end"><strong>SALDO PENDIENTE:</strong></td>
                                    <td class="text-end"><strong class="text-danger">Bs. {{ number_format($selectedAlquiler->saldo_pendiente, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- TÉRMINOS Y CONDICIONES --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold text-uppercase border-bottom pb-2 mb-3">
                                <i class="fas fa-file-contract me-2"></i>IV. TÉRMINOS Y CONDICIONES
                            </h6>
                            <div class="terms-content small">
                                <ol class="mb-2">
                                    <li class="mb-2">El ARRENDATARIO se compromete a devolver los productos alquilados en las mismas condiciones en que fueron entregados, en la fecha y hora acordadas.</li>
                                    <li class="mb-2">En caso de retraso en la devolución, se aplicará un cargo adicional por día de retraso según las tarifas vigentes.</li>
                                    <li class="mb-2">El ARRENDATARIO será responsable de cualquier daño, pérdida o deterioro de los productos alquilados, debiendo asumir el costo de reparación o reposición.</li>
                                    @if($selectedAlquiler->tieneGarantia())
                                    <li class="mb-2">La garantía registrada será devuelta al momento de la devolución de los productos, previa verificación del estado de los mismos.</li>
                                    @endif
                                    <li class="mb-2">Los productos deben ser utilizados únicamente para el fin acordado y no pueden ser subarrendados o transferidos a terceros.</li>
                                    <li class="mb-2">El ARRENDADOR no se hace responsable por accidentes, lesiones o daños causados durante el uso de los productos alquilados.</li>
                                    <li class="mb-2">El presente contrato se rige por las leyes vigentes de Bolivia y cualquier controversia será resuelta en los tribunales competentes.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    {{-- FIRMAS --}}
                    <div class="row mt-5 pt-4">
                        <div class="col-md-6 text-center">
                            <div class="signature-line">
                                <div class="border-top border-dark pt-2 mt-5">
                                    <p class="mb-0 small"><strong>ARRENDADOR</strong></p>
                                    <p class="mb-0 small">{{ config('app.name', 'Folcklore') }}</p>
                                    <p class="mb-0 small text-muted">Nombre y Firma</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="signature-line">
                                <div class="border-top border-dark pt-2 mt-5">
                                    <p class="mb-0 small"><strong>ARRENDATARIO</strong></p>
                                    <p class="mb-0 small">{{ $selectedAlquiler->cliente->nombres }} {{ $selectedAlquiler->cliente->apellidos }}</p>
                                    <p class="mb-0 small text-muted">CI: {{ $selectedAlquiler->cliente->carnet_identidad ?? 'N/A' }}</p>
                                    <p class="mb-0 small text-muted">Nombre y Firma</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PIE DE PÁGINA --}}
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <p class="small text-muted mb-1">Fecha de emisión: {{ now()->format('d/m/Y H:i') }}</p>
                            <p class="small text-muted mb-1">Usuario: {{ auth()->user()->name ?? 'Sistema' }}</p>
                            <p class="small text-muted mb-0">Este documento tiene validez legal</p>
                        </div>
                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" wire:click="closePrintModal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="printContract()">
                    <i class="fas fa-print me-2"></i>Imprimir Contrato
                </button>
                <button type="button" class="btn btn-success" onclick="downloadContractPDF()">
                    <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                </button>
            </div>

        </div>
    </div>
</div>

{{-- BACKDROP --}}
<div class="modal-backdrop fade show"></div>
@endif

<style>
.modal {
    background-color: rgba(0, 0, 0, 0.5);
}

/* Estilos para impresión */
@media print {
    body * {
        visibility: hidden;
    }

    #printable-content,
    #printable-content * {
        visibility: visible;
    }

    #printable-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20mm;
    }

    .modal-header,
    .modal-footer,
    .btn,
    button {
        display: none !important;
    }

    @page {
        size: letter;
        margin: 15mm;
    }
}

/* Estilos del contrato */
#printable-content {
    font-family: 'Times New Roman', serif;
    color: #000;
    line-height: 1.6;
}

.contract-header {
    page-break-after: avoid;
}

.terms-content {
    text-align: justify;
}

.signature-line {
    min-height: 100px;
}
</style>

<script>
function printContract() {
    window.print();
}

function downloadContractPDF() {
    // Implementar lógica para descargar PDF
    // Puedes usar una librería como jsPDF o enviar a backend
    alert('Función de descarga PDF en desarrollo');
}
</script>
