<div class="card shadow-lg">
    <div class="simple-pill">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <!-- Historial Tab -->
            <li class="nav-item" role="presentation">
                <button class="nav-link @if ($activeTab === 'historial') active @endif"
                    wire:click="setActiveTab('historial')" id="pills-historial-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-historial" type="button" role="tab" aria-controls="pills-historial"
                    aria-selected="{{ $activeTab === 'historial' ? 'true' : 'false' }}">
                    <i class="fas fa-history me-2"></i> Historial
                </button>
            </li>

            <!-- Vacunas Tab -->
            <li class="nav-item" role="presentation">
                <button class="nav-link @if ($activeTab === 'vacunas') active @endif"
                    wire:click="setActiveTab('vacunas')" id="pills-vacunas-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-vacunas" type="button" role="tab" aria-controls="pills-vacunas"
                    aria-selected="{{ $activeTab === 'vacunas' ? 'true' : 'false' }}">
                    <i class="fas fa-syringe me-2"></i> Vacunas
                </button>
            </li>

            <!-- Peluquería Tab -->
            <li class="nav-item" role="presentation">
                <button class="nav-link @if ($activeTab === 'peluqueria') active @endif"
                    wire:click="setActiveTab('peluqueria')" id="pills-peluqueria-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-peluqueria" type="button" role="tab" aria-controls="pills-peluqueria"
                    aria-selected="{{ $activeTab === 'peluqueria' ? 'true' : 'false' }}">
                    <i class="fas fa-cut me-2"></i> Peluquería
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="pills-tabContent">
            <!-- Historial Tab Content -->
            <div class="tab-pane fade @if ($activeTab === 'historial') show active @endif" id="pills-historial"
                role="tabpanel" aria-labelledby="pills-historial-tab">
                <div class="container">
                    @if (session()->has('messageHistorial'))
                        <div class="alert alert-success">{{ session('messageHistorial') }}</div>
                    @endif

                    <!-- Botón para abrir el modal -->
                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalHistorial">
                        Registrar Historial
                    </button>
                    <div class="container">
                        @if ($historiales->isEmpty())
                            <p class="text-muted">No hay historiales disponibles para esta mascota.</p>
                        @else
                            @foreach ($historiales as $index => $historial)
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header d-flex justify-content-between align-items-center bg-light">
                                        <div>
                                            <h5 class="mb-0 text-primary">{{ $historial->motivo_consulta }}</h5>
                                            <small class="text-muted">Fecha:
                                                {{ $historial->created_at->format('d/m/Y') }}</small>
                                        </div>
                                        <div>
                                            <button class="btn btn-outline-danger btn-sm me-2"
                                                wire:click="eliminarHistorial({{ $historial->id }})">
                                                Eliminar
                                            </button>
                                            <button class="btn btn-outline-primary btn-sm"
                                                wire:click="toggleHistorial({{ $index }})">
                                                {{ in_array($index, $expandedHistorials) ? 'Ocultar' : 'Expandir' }}
                                            </button>
                                            <button class="btn btn-primary btn-sm" 
        wire:click="editHistorial({{ $historial->id }})"
        data-bs-toggle="modal" 
        data-bs-target="#editHistorialModal">
    <i class="fas fa-edit"></i>
</button>
                                        </div>
                                    </div>
                                    @if (in_array($index, $expandedHistorials))
                                        <div class="card-body">
                                            <p class="fw-bold">N. Historial:</p>
                                            <p class="text-muted">{{ $historial->id }}</p>
                                            <p class="fw-bold">Síntomas:</p>
                                            <p class="text-muted">{{ $historial->sintomas }}</p>
                                            <p class="fw-bold">Diagnóstico:</p>
                                            <p class="text-muted">{{ $historial->diagnostico }}</p>
                                            <p class="fw-bold">Tratamiento:</p>
                                            <p class="text-muted">{{ $historial->tratamiento }}</p>

                                            <h6 class="mt-3">Documentos:</h6>
                                            <div class="row">
                                                @foreach ($historial->documentos as $documento)
                                                    <div class="col-md-4 mb-3">
                                                        <div class="card">
                                                            <div class="card-body d-flex align-items-center">
                                                                <img src="{{ Storage::url($documento->archivo) }}"
                                                                    alt="{{ $documento->titulo }}"
                                                                    class="img-thumbnail me-3"
                                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                                                <a href="{{ Storage::url($documento->archivo) }}"
                                                                    target="_blank"
                                                                    class="text-decoration-none text-primary">{{ $documento->titulo }}</a>
                                                            </div>
                                                              <button class="btn btn-danger btn-sm" 
                        wire:click="eliminarDocumento({{ $documento->id }})"
                        onclick="return confirm('¿Estás seguro de eliminar este documento?')">
                    <i class="fas fa-trash"></i>
                </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Vacunas Tab Content -->
            <div class="tab-pane fade @if ($activeTab === 'vacunas') show active @endif" id="pills-vacunas"
                role="tabpanel" aria-labelledby="pills-vacunas-tab">
                <div class="mt-3">
                    @if (session()->has('messageVacuna'))
                        <div class="alert alert-success">{{ session('messageVacuna') }}</div>
                    @endif
                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalVacuna">
                        Registrar Vacuna
                    </button>
                    <div class="mb-4">
                        <h4>Historial de Vacunas</h4>
                        @if ($vacunas->isEmpty())
                            <p>No hay vacunas registradas.</p>
                        @else
                            <div class="row">
                                @foreach ($historialVacunas as $hsitorial_vacuna)
                                    <div class="col-md-4 mb-3">
                                        <div class="card shadow-sm border-primary">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-syringe fa-lg me-2"></i>
                                                    <h5 class="mb-0">{{ $hsitorial_vacuna->vacunas->nombre }}</h5>
                                                </div>
                                                <button class="btn btn-primary btn-sm me-1"
                                                    wire:click="editVacuna({{ $hsitorial_vacuna->id }})"
                                                    data-bs-toggle="modal" data-bs-target="#editVacunaModal"
                                                    title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm"
                                                    wire:click="deleteVacuna({{ $hsitorial_vacuna->id }})"
                                                    title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text"><strong>Fecha de Aplicación:</strong>
                                                    {{ \Carbon\Carbon::parse($hsitorial_vacuna->created_at)->format('d-m-Y') }}
                                                </p>
                                                <p class="card-text"><strong>IntiSoft:</strong>
                                                    <span
                                                        class="text-muted">{{ $hsitorial_vacuna->IntiSoft ?? 'N/A' }}</span>
                                                </p>
                                                <p class="card-text"><strong>Producto:</strong>
                                                    <span
                                                        class="text-muted">{{ $hsitorial_vacuna->producto ?? 'N/A' }}</span>
                                                </p>
                                                <p class="card-text"><strong>Referencia:</strong>
                                                    <span
                                                        class="text-muted">{{ $hsitorial_vacuna->referencia ?? 'N/A' }}</span>
                                                </p>
                                                <p class="card-text"><strong>Observaciones:</strong>
                                                    <span
                                                        class="text-muted">{{ $hsitorial_vacuna->observaciones ?? 'N/A' }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{--  editar vacuna  --}}
            <!-- Modal para Editar Vacuna -->



            <!-- Peluquería Tab Content -->
            <div class="tab-pane fade @if ($activeTab === 'peluqueria') show active @endif" id="pills-peluqueria"
                role="tabpanel" aria-labelledby="pills-peluqueria-tab">
                <div class="container mt-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#peluqueriaModal">
                        Agregar Servicio de Peluquería
                    </button>
                    <h4 class="text-center mb-4" style="font-size: 1.5rem; font-weight: bold; color: #4CAF50;">
                        Historial de Peluquería</h4>

                    @if (session()->has('messagePeluqueria'))
                        <div class="alert alert-success">{{ session('messagePeluqueria') }}</div>
                    @endif
                    <div class="row" wire:ignore.self>
                        @foreach ($historialPeluqueria as $peluqueria)
                            <div class="col-md-4 mb-4">
                                <div class="card shadow border-0"
                                    style="border-radius: 15px; overflow: hidden; background-color: #f8f9fa;">
                                    <!-- Imagen de Peluquería -->
                                    <div class="card-img-top position-relative">
                                        <img src="{{ $peluqueria->imagen ? asset('storage/' . $peluqueria->imagen) : 'https://via.placeholder.com/300x200' }}"
                                            alt="Imagen Peluquería" class="img-fluid"
                                            style="object-fit: cover; height: 220px; width: 100%; border-bottom: 3px solid #007bff;">
                                    </div>

                                    <!-- Detalles del Servicio -->
                                    <div class="card-body">
                                        <h5 class="card-title text-center mb-3"
                                            style="font-size: 1.5rem; font-weight: bold; color: #007bff;">
                                            {{ ucfirst($peluqueria->tipo_corte) }}
                                        </h5>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <p class="mb-0 text-muted">
                                                <i class="fas fa-cut"></i>
                                                <strong>Número de Cuchilla:</strong> {{ $peluqueria->numero_cuchilla }}
                                            </p>
                                            <p class="mb-0 text-muted">
                                                <i class="fas fa-money-bill"></i>
                                                <strong>Precio:</strong> Bs{{ number_format($peluqueria->precio, 2) }}
                                            </p>
                                        </div>

                                        <div class="d-flex justify-content-between mb-3">
                                            <p class="mb-0">
                                                <strong>Tipo de Corte:</strong>
                                                <span class="badge bg-primary text-white" style="font-size: 0.9rem;">
                                                    {{ ucfirst($peluqueria->tipo_corte) }}
                                                </span>
                                            </p>
                                            <p class="mb-0 text-muted">
                                                <i class="fas fa-calendar-alt"></i>
{{ $peluqueria->created_at ? $peluqueria->created_at->format('d-m-Y') : 'Sin fecha' }}
                                            </p>
                                        </div>

                                        <!-- Botones de acción -->
                                        <div class="d-flex justify-content-between mt-4">
                                            <button class="btn btn-outline-primary btn-sm px-4" data-bs-toggle="modal"
                                                data-bs-target="#editPeluqueriaModal"
                                                wire:click="edit({{ $peluqueria->id }})">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm px-4" data-bs-toggle="modal"
                                                data-bs-target="#deletePeluqueriaModal"
                                                wire:click="setDeleteId({{ $peluqueria->id }})">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                    <!-- Modal de edición -->
                    <div wire:ignore.self class="modal fade" id="editPeluqueriaModal" tabindex="-1"
                        aria-labelledby="editPeluqueriaModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header ">
                                    <h5 class="modal-title" id="editPeluqueriaModalLabel">Editar Servicio de
                                        Peluquería</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form wire:submit.prevent="updatePeluqueria">
                                        <div class="row g-3">
                                            <!-- Subir Imagen -->
                                            <div class="col-md-12">
                                                <label for="uploadImage" class="form-label">Imagen del
                                                    Servicio</label>
                                                <input type="file" class="form-control" wire:model="imagen"
                                                    accept="image/*">
                                                @if ($imagen)
                                                    <div class="mt-2 text-center">
                                                        <img src="{{ $imagen->temporaryUrl() }}" alt="Vista Previa"
                                                            class="img-thumbnail" style="max-height: 150px;">
                                                    </div>
                                                @endif
                                                @error('imagen')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <!-- Indicador de carga -->
                                                <div wire:loading wire:target="imagen" class="mt-2 text-info">
                                                    Subiendo imagen, por favor espere...
                                                </div>
                                            </div>

                                            <!-- Número de Cuchilla -->
                                            <div class="col-md-6">
                                                <label for="cuchillaNumber" class="form-label">Número de
                                                    Cuchilla</label>
                                                <input type="number" class="form-control"
                                                    wire:model="numero_cuchilla" placeholder="Ejemplo: 3">
                                                @error('numero_cuchilla')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Tipo de Corte -->
                                            <div class="col-md-6">
                                                <label for="tipoCorte" class="form-label">Tipo de Corte</label>
                                                  <input type="text" class="form-control" wire:model="tipo_corte"
                                                    placeholder="escribe el corte">
                                                @error('tipo_corte')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Precio -->
                                            <div class="col-md-6">
                                                <label for="precio" class="form-label">Precio del Servicio</label>
                                                <input type="number" class="form-control" wire:model="precio"
                                                    placeholder="Ejemplo: 150.00" step="0.01">
                                                @error('precio')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Fecha del Corte -->
                                            <div class="col-md-6">
                                                <label for="fechaCorte" class="form-label">Fecha del Corte</label>
                                                <input type="date" class="form-control" wire:model="fecha_corte">
                                                @error('fecha_corte')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="modal-footer mt-4">
                                            <button type="submit" class="btn btn-primary"
                                                wire:loading.attr="disabled" wire:target="imagen">
                                                Actualizar
                                            </button>

                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal de confirmación de eliminación -->
                    <div wire:ignore class="modal fade" id="deletePeluqueriaModal" tabindex="-1"
                        aria-labelledby="deletePeluqueriaModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deletePeluqueriaModalLabel">Eliminar Servicio de
                                        Peluquería</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Estás seguro de que deseas eliminar este servicio de peluquería? Esta acción no se
                                    puede deshacer.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-danger"
                                        wire:click="deletePeluqueria">Eliminar</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>
    {{-- modal historiakl --}}
    <div wire:ignore.self class="modal fade" id="modalHistorial" tabindex="-1"
        aria-labelledby="modalHistorialLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- Aumentamos el tamaño del modal a 'extra grande' -->
            <div class="modal-content">
                <div class="modal-header  text-white"> <!-- Encabezado con fondo y texto estilizado -->
                    <h5 class="modal-title" id="modalHistorialLabel">Añadir Historial</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light"> <!-- Fondo más claro para mejor visibilidad -->
                    <div class="container">
                        <div class="row">
                            <!-- Motivo de consulta -->
                            <div class="col-md-6 mb-3">
                                <label for="motivo_consulta" class="form-label fw-bold">Motivo de consulta</label>
                                <input type="text" id="motivo_consulta" wire:model="motivoConsulta"
                                    class="form-control shadow-sm border-primary">
                            </div>

                            <!-- Síntomas -->
                            <div class="col-md-6 mb-3">
                                <label for="sintomas" class="form-label fw-bold">Síntomas</label>
                                <textarea id="sintomas" wire:model="sintomas" class="form-control shadow-sm border-primary"></textarea>
                            </div>

                            <!-- Diagnóstico -->
                            <div class="col-md-6 mb-3">
                                <label for="diagnostico" class="form-label fw-bold">Diagnóstico</label>
                                <textarea id="diagnostico" wire:model="diagnostico" class="form-control shadow-sm border-primary"></textarea>
                            </div>

                            <!-- Tratamiento -->
                            <div class="col-md-6 mb-3">
                                <label for="tratamiento" class="form-label fw-bold">Tratamiento</label>
                                <textarea id="tratamiento" wire:model="tratamiento" class="form-control shadow-sm border-primary"></textarea>
                            </div>

                            <!-- Documentos -->
                            <div class="col-12 mb-3" id="documentosContainer">
                                <label class="form-label fw-bold">Documentos</label>
                                @foreach ($documentos as $index => $documento)
                                    <div class="card p-3 mb-3 shadow-sm">
                                        <div class="mb-2">
                                            <input type="text" 
                                                name="titulosDocumentos[{{ $index }}]"
                                                class="form-control mt-2 shadow-sm border-primary"
                                                placeholder="Título del archivo"
                                                wire:model="titulosDocumentos.{{ $index }}">
                                        </div>

                                        <div class="mb-2 position-relative">
                                            <input type="file" 
                                                name="documentos[{{ $index }}]"
                                                class="form-control mt-2 shadow-sm border-primary"
                                                wire:model="documentos.{{ $index }}"
                                                onchange="mostrarIndicadorCarga(this)">
                                            
                                            <!-- Indicador de carga -->
                                            <div class="upload-indicator position-absolute top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center" 
                                                 style="background: rgba(255,255,255,0.9);">
                                                <div class="d-flex align-items-center text-primary">
                                                    <div class="spinner-border spinner-border-sm me-2" role="status">
                                                        <span class="visually-hidden">Cargando...</span>
                                                    </div>
                                                    <span>Subiendo documento...</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Previsualización del archivo -->
                                        @if($documento && method_exists($documento, 'temporaryUrl'))
                                            <div class="mt-2">
                                                @if(in_array(strtolower($documento->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'gif']))
                                                    <img src="{{ $documento->temporaryUrl() }}" 
                                                         class="img-thumbnail" 
                                                         style="max-height: 200px; width: auto;">
                                                @else
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-file me-2"></i>
                                                        Archivo: {{ $documento->getClientOriginalName() }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <button type="button" 
                                            class="btn btn-danger mt-2"
                                            wire:click.prevent="removeDocumento({{ $index }})">
                                            Eliminar
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Botón para añadir documento -->
                            <div class="col-12 mb-3">
                                <button type="button" class="btn btn-success" wire:click.prevent="addDocumento">
                                    <i class="bi bi-plus-lg"></i> Agregar nuevo documento
                                </button>
                            </div>

                            <!-- Botón para guardar el historial -->
                            <div class="col-12 text-center mt-4">
                                <button type="button" class="btn btn-primary btn-lg" wire:click="guardarHistorial">
                                    <i class="bi bi-save"></i> Guardar Historial
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal Vacuna --}}

  <!-- Modal para Añadir Vacuna -->
<div wire:ignore.self class="modal fade" id="modalVacuna" tabindex="-1" aria-labelledby="modalVacunaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Modal de tamaño grande -->
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title" id="modalVacunaLabel">Añadir Vacuna</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="container">
                    <div class="row">
                        <!-- Fecha de la vacuna -->
                        <div class="col-md-6 mb-3">
                            <label for="fecha_vacuna" class="form-label fw-bold">Fecha de Vacuna</label>
                            <input type="date" id="fecha_vacuna" wire:model="fechaVacuna" class="form-control shadow-sm border-primary" value="{{ now()->format('Y-m-d') }}">
                        </div>

                        <!-- Vacuna ID -->
                        <div class="col-md-6 mb-3">
                            <label for="vacuna_id" class="form-label fw-bold">Selecciona la Vacuna</label>
                            <select id="vacuna_id" wire:model="vacunaId" class="form-select shadow-sm border-primary">
                                <option value="" selected>Selecciona una vacuna</option>
                                @foreach ($vacunas as $vacuna)
                                    <option value="{{ $vacuna->id }}">{{ $vacuna->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Producto -->
                        <div class="col-md-6 mb-3">
                            <label for="producto" class="form-label fw-bold">Producto</label>
                            <input type="text" id="producto" wire:model="producto" class="form-control shadow-sm border-primary" placeholder="Nombre del producto">
                        </div>

                        <!-- IntiSoft -->
                        <div class="col-md-6 mb-3">
                            <label for="IntiSoft" class="form-label fw-bold">IntiSoft</label>
                            <input type="text" id="IntiSoft" wire:model="IntiSoft" class="form-control shadow-sm border-primary" placeholder="Nombre de la IntiSoft">
                        </div>

                        <!-- Referencia -->
                        <div class="col-md-6 mb-3">
                            <label for="referencia" class="form-label fw-bold">Referencia</label>
                            <input type="text" id="referencia" wire:model="referencia" class="form-control shadow-sm border-primary" placeholder="Referencia de la vacuna">
                        </div>

                        <!-- Observaciones -->
                        <div class="col-12 mb-3">
                            <label for="observaciones" class="form-label fw-bold">Observaciones</label>
                            <textarea id="observaciones" wire:model="observaciones" class="form-control shadow-sm border-primary" placeholder="Observaciones sobre la vacuna"></textarea>
                        </div>

                        <!-- Botón para guardar la vacuna -->
                        <div class="col-12 text-center mt-4">
                            <button type="button" class="btn btn-primary btn-lg" wire:click="guardarVacuna">
                                <i class="bi bi-save"></i> Guardar Vacuna
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Vacuna -->
<div wire:ignore.self class="modal fade" id="editVacunaModal" tabindex="-1" aria-labelledby="editVacunaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Modal de tamaño grande -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVacunaModalLabel">Editar Vacuna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <!-- Fecha de Aplicación -->
                        <div class="col-md-6 mb-3">
                            <label for="fechaVacuna" class="form-label fw-bold">Fecha de Aplicación</label>
                            <input type="date" id="fechaVacuna" wire:model="fechaVacuna" class="form-control shadow-sm border-primary">
                            @error('fechaVacuna') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Vacuna ID (Este es el campo que faltaba) -->
                        <div class="col-md-6 mb-3">
                            <label for="vacuna_id_edit" class="form-label fw-bold">Selecciona la Vacuna</label>
                            <select id="vacuna_id_edit" wire:model="vacunaId" class="form-select shadow-sm border-primary">
                                <option value="" selected>Selecciona una vacuna</option>
                                @foreach ($vacunas as $vacuna)
                                    <option value="{{ $vacuna->id }}">{{ $vacuna->nombre }}</option>
                                @endforeach
                            </select>
                            @error('vacunaId') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Producto -->
                        <div class="col-md-6 mb-3">
                            <label for="producto" class="form-label fw-bold">Producto</label>
                            <input type="text" id="producto" wire:model="producto" class="form-control shadow-sm border-primary">
                            @error('producto') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- IntiSoft -->
                        <div class="col-md-6 mb-3">
                            <label for="IntiSoft" class="form-label fw-bold">IntiSoft</label>
                            <input type="text" id="IntiSoft" wire:model="IntiSoft" class="form-control shadow-sm border-primary">
                            @error('IntiSoft') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Referencia -->
                        <div class="col-md-6 mb-3">
                            <label for="referencia" class="form-label fw-bold">Referencia</label>
                            <input type="text" id="referencia" wire:model="referencia" class="form-control shadow-sm border-primary">
                            @error('referencia') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Observaciones -->
                        <div class="col-12 mb-3">
                            <label for="observaciones" class="form-label fw-bold">Observaciones</label>
                            <textarea id="observaciones" wire:model="observaciones" class="form-control shadow-sm border-primary"></textarea>
                            @error('observaciones') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Botón para guardar los cambios -->
                        <div class="col-12 text-center mt-4">
                            <button type="button" class="btn btn-primary btn-lg" wire:click="updateVacuna">
                                <i class="bi bi-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    {{-- modal Peluqueria  --}}
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="peluqueriaModal" tabindex="-1"
        aria-labelledby="peluqueriaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg" style="border-radius: 15px;">
                <!-- Encabezado -->
                <div class="modal-header ">
                    <h5 class="modal-title" id="peluqueriaModalLabel" style="font-weight: bold;">
                        Nuevo Servicio de Peluquería
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Cuerpo -->
                <div class="modal-body bg-light">
                    <form wire:submit.prevent="savePeluqueria">
                        <div class="row g-4">
                            <!-- Columna 1 -->
                            <div class="col-md-6">
                                <!-- Subir Imagen -->
                                <div class="mb-4">
                                    <label for="uploadImage" class="form-label fw-semibold">Subir Imagen</label>
                                    <input type="file" class="form-control" wire:model="imagen" accept="image/*">
                                    @error('imagen')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                    <div class="mt-3 text-center" wire:ignore>
                                        @if ($imagen)
                                            <img src="{{ $imagen->temporaryUrl() }}" alt="Previsualización"
                                                class="img-thumbnail rounded shadow-sm"
                                                style="height: 150px; width: auto;">
                                        @endif
                                    </div>
                                    <!-- Indicador de carga -->
                                    <div wire:loading wire:target="imagen" class="mt-2 text-info">
                                        Subiendo imagen, por favor espere...
                                    </div>
                                </div>

                                <!-- Número de Cuchilla -->
                                <div class="mb-4">
                                    <label for="cuchillaNumber" class="form-label fw-semibold">Número de
                                        Cuchilla</label>
                                    <input type="number" id="cuchillaNumber" class="form-control"
                                        wire:model="numero_cuchilla" placeholder="Ejemplo: 3" min="0">
                                    @error('numero_cuchilla')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Columna 2 -->
                            <div class="col-md-6">
                                <!-- Tipo de Corte -->
                                <div class="mb-4">
                                    <label for="tipoCorte" class="form-label fw-semibold">Tipo de Corte</label>
                                    <input type="text" class="form-control" wire:model="tipo_corte"
                                                    placeholder="escribe el corte">
                                    @error('tipo_corte')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Precio -->
                                <div class="mb-4">
                                    <label for="precio" class="form-label fw-semibold">Precio</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" id="precio" class="form-control"
                                            wire:model="precio" placeholder="Ejemplo: 150.00" step="0.01"
                                            min="0">
                                    </div>
                                    @error('precio')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Fecha de Corte -->
                                <div class="mb-4">
                                    <label for="fechaCorte" class="form-label fw-semibold">Fecha de Corte</label>
                                    <input type="date" id="fechaCorte" class="form-control"
                                        wire:model="fecha_corte">
                                    @error('fecha_corte')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="modal-footer bg-opacity-10">
                    <button type="submit" class="btn btn-primary w-100 fw-bold" wire:click="savePeluqueria"
                        wire:loading.attr="disabled" wire:target="imagen">
                        Guardar
                    </button>
                    <button type="button" class="btn btn-light w-100 mt-2" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar historial -->
    <div wire:ignore.self class="modal fade" id="editHistorialModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">
                        <b>Editar Historial</b>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Motivo de Consulta</label>
                                <input type="text" wire:model.defer="motivoConsulta" class="form-control">
                                @error('motivoConsulta') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Síntomas</label>
                                <textarea wire:model.defer="sintomas" class="form-control"></textarea>
                                @error('sintomas') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Diagnóstico</label>
                                <textarea wire:model.defer="diagnostico" class="form-control"></textarea>
                                @error('diagnostico') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Tratamiento</label>
                                <textarea wire:model.defer="tratamiento" class="form-control"></textarea>
                                @error('tratamiento') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Sección de Documentos -->
                        <div class="col-12 mb-3" id="documentosContainer">
                            <label class="form-label fw-bold">Documentos</label>
                            @foreach ($documentos as $index => $documento)
                                <div class="card p-3 mb-3 shadow-sm">
                                    <div class="mb-2">
                                        <input type="text" 
                                            name="titulosDocumentos[{{ $index }}]"
                                            class="form-control mt-2 shadow-sm border-primary"
                                            placeholder="Título del archivo"
                                            wire:model="titulosDocumentos.{{ $index }}">
                                    </div>

                                    <div class="mb-2 position-relative">
                                        <input type="file" 
                                            name="documentos[{{ $index }}]"
                                            class="form-control mt-2 shadow-sm border-primary"
                                            wire:model="documentos.{{ $index }}"
                                            onchange="mostrarIndicadorCarga(this)">
                                        
                                        <!-- Indicador de carga -->
                                        <div class="upload-indicator position-absolute top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center" 
                                             style="background: rgba(255,255,255,0.9);">
                                            <div class="d-flex align-items-center text-primary">
                                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                                    <span class="visually-hidden">Cargando...</span>
                                                </div>
                                                <span>Subiendo documento...</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Previsualización del archivo -->
                                    @if($documento && method_exists($documento, 'temporaryUrl'))
                                        <div class="mt-2">
                                            @if(in_array(strtolower($documento->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'gif']))
                                                <img src="{{ $documento->temporaryUrl() }}" 
                                                     class="img-thumbnail" 
                                                     style="max-height: 200px; width: auto;">
                                            @else
                                                <div class="alert alert-info">
                                                    <i class="fas fa-file me-2"></i>
                                                    Archivo: {{ $documento->getClientOriginalName() }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <button type="button" 
                                        class="btn btn-danger mt-2"
                                        wire:click.prevent="removeDocumento({{ $index }})">
                                        Eliminar
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <!-- Agregar el botón que faltaba -->
                        <div class="col-12 mt-4">
                            <div class="form-group">
                                <label class="fw-bold">Documentos</label>
                                @foreach ($documentos as $index => $documento)
                                    <!-- ... código existente de los documentos ... -->
                                @endforeach
                                
                                <!-- Agregar el botón que faltaba -->
                                <button type="button" class="btn btn-success mt-3" wire:click="addDocumento">
                                    <i class="fas fa-plus"></i> Agregar Documento
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.prevent="updateHistorial()" class="btn btn-primary">
                        Actualizar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* peluqueria */


        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .modal-open .card:hover {
            transform: none;
            box-shadow: none;
        }

        .card-title {
            text-transform: capitalize;
            letter-spacing: 0.5px;
        }

        .badge {
            padding: 5px 10px;
            font-size: 0.85rem;
            border-radius: 12px;
        }

        .btn-outline-primary {
            border-color: #007bff;
            color: #007bff;
        }

        .btn-outline-primary:hover {
            background-color: #007bff;
            color: #fff;
        }

        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: #fff;
        }

        .modal {
            z-index: 1060 !important;
            /* Asegúrate de que sea mayor que otros elementos */
        }

        .modal-backdrop {
            z-index: 1055 !important;
            /* La sombra detrás del modal */
        }

        .upload-indicator {
            transition: all 0.3s ease;
            backdrop-filter: blur(2px);
            z-index: 10;
        }

        .spinner-border {
            width: 1.2rem;
            height: 1.2rem;
        }
    </style>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('imagePreview');
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                preview.src = '#';
            }
        }
    </script>

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('closeVacunaModal', () => {
                var myModalEl = document.getElementById('modalVacuna');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();
            });
        });
        document.addEventListener('livewire:load', function() {
            Livewire.on('closeVacunaModal', () => {
                var myModalEl = document.getElementById('modalVacuna');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();
            });
        });
    </script>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('closePeluqueriaModal', () => {
                var myModalEl = document.getElementById('peluqueriaModal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();
            });
            Livewire.on('updateVacunaModal', () => {
                var myModalEl = document.getElementById('editVacunaModal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();
                console.log('vacuna');
            });
            Livewire.on('deletePeluqueriaModal', () => {
                var myModalEl = document.getElementById('deletePeluqueriaModal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();
            });
        });
        document.addEventListener('livewire:load', function() {
            Livewire.on('show-alert', function(data) {
                const {
                    title,
                    type
                } = data;
                Swal.fire({
                    position: 'top-end',
                    icon: type,
                    title: title,
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        });
    </script>

    <script>
    document.addEventListener('livewire:load', function () {
        // Mostrar indicador de carga cuando se selecciona un archivo
        function mostrarIndicadorCarga(input) {
            const container = input.closest('.position-relative');
            const indicator = container.querySelector('.upload-indicator');
            
            if (input.files && input.files[0]) {
                // Mostrar indicador
                indicator.classList.remove('d-none');
                indicator.classList.add('d-flex');
                
                // Escuchar el evento de Livewire para saber cuándo termina la carga
                Livewire.on('documentoSubido', () => {
                    indicator.classList.remove('d-flex');
                    indicator.classList.add('d-none');
                });
            }
        }

        // Hacer la función disponible globalmente
        window.mostrarIndicadorCarga = mostrarIndicadorCarga;

        // Ocultar todos los indicadores cuando se complete cualquier operación de Livewire
        Livewire.on('documentoSubido', () => {
            document.querySelectorAll('.upload-indicator').forEach(indicator => {
                indicator.classList.remove('d-flex');
                indicator.classList.add('d-none');
            });
        });
    });
    </script>

    <script>
    document.addEventListener('livewire:load', function () {
        // ... otros listeners existentes ...

        Livewire.on('closeEditHistorialModal', () => {
            var myModalEl = document.getElementById('editHistorialModal');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            if (modal) {
                modal.hide();
            }
        });

        // Agregar este listener para cerrar el modal cuando se complete la actualización
        Livewire.on('historialActualizado', () => {
            var myModalEl = document.getElementById('editHistorialModal');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            if (modal) {
                modal.hide();
            }
            // Opcional: mostrar mensaje de éxito
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Historial actualizado correctamente',
                showConfirmButton: false,
                timer: 2000
            });
        });
    });
    </script>
</div>

<!-- Modal para Registrar Historial -->
