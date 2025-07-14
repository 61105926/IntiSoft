<div>
    <h2 class="text-center text-primary">Perfil Mascota</h2>

    <div class="row mb-4">
        <!-- Tarjeta de Perfil de la Mascota -->
       <div class="col-md-8">
    <div class="card shadow-lg rounded-4">
        <div class="row g-0">
            <!-- Imagen de la mascota -->
            <div class="col-md-4 text-center bg-light rounded-start">
                <img src="{{ $pet->image ? asset('storage/pet/' . $pet->image) : 'https://images.unsplash.com/photo-1507146426996-ef05306b995a?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDF8fHxlbnwwfHx8fHw%3D' }}"
                    alt="Mascota" class="img-fluid rounded-start shadow-sm"
                    style="height: 250px; width: 100%; object-fit: cover; border-radius: 15px 0 0 15px;">
            </div>

            <!-- Detalle de la mascota -->
            <div class="col-md-8">
                <div class="card-body p-4">
                    <h4 class="text-dark fw-bold">{{ $pet->nombre }}</h4>
                    @php
                        use Carbon\Carbon;

                        if ($pet->fecha_nacimiento) {
                            $fechaNacimiento = Carbon::parse($pet->fecha_nacimiento);
                            $años = $fechaNacimiento->diffInYears(Carbon::now());
                            $meses = $fechaNacimiento->copy()->addYears($años)->diffInMonths(Carbon::now());
                        } else {
                            $años = $meses = null;
                        }
                    @endphp
                    @if ($años !== null && $meses !== null)
                        <p class="text-muted">Edad: {{ $años }} años y {{ $meses }} meses</p>
                    @else
                        <p class="text-muted">Edad: Desconocida</p>
                    @endif
                </div>
                
                <!-- Información Adicional -->
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-3 text-center">
                        <div class="icon-container bg-info text-white rounded-circle mb-4 d-flex align-items-center justify-content-center mx-auto"
                             style="width: 60px; height: 60px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                            <i class="fas fa-paw fa-2x"></i>
                        </div>
                        <h6 class="card-title text-info mb-4">Información Adicional</h6>
                        
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <strong><i class="fas fa-dog text-primary"></i> Especie:</strong>
                                <span class="text-dark">{{ $pet->especies->nombre }}</span>
                            </li>
                            <li class="mb-2">
                                <strong><i class="fas fa-paw text-primary"></i> Raza:</strong>
                                <span class="text-dark">{{ $pet->razas->nombre ?? 'N/A' }}</span>
                            </li>
                            <li class="mb-2">
                                <strong><i class="fas fa-microchip text-primary"></i> Chip:</strong>
                                <span class="text-dark">{{ $pet->chip }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Información General -->
            <div class="col-md-12">
                <div class="card-body p-4">
                    <h5 class="card-title text-primary border-bottom pb-2 mb-4">Información General</h5>
                    <div class="row">
                        <!-- Tarjeta: Información Básica -->
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-lg border-0 rounded-4 h-100">
                                <div class="card-body text-center p-4">
                                    <div class="icon-container bg-primary text-white rounded-circle mb-3 d-flex align-items-center justify-content-center mx-auto" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-id-badge fa-2x"></i>
                                    </div>
                                    <h6 class="card-title text-primary">Información Básica</h6>
                                    <ul class="list-unstyled mb-3">
                                        <li class="mb-2">
                                            <strong><i class="fas fa-venus-mars text-primary"></i> Sexo:</strong>
                                            <span class="text-dark">{{ $pet->sexo }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <strong><i class="fas fa-palette text-primary"></i> Color:</strong>
                                            <span class="text-dark">{{ $pet->color }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <strong><i class="fas fa-calendar-alt text-primary"></i> Fecha Nacimiento:</strong>
                                            <span class="text-dark">{{ $pet->fecha_nacimiento }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <strong><i class="fas fa-weight text-primary"></i> Peso:</strong>
                                            <span class="text-dark">{{ $pet->peso }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta: Datos del Dueño -->
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-lg border-0 rounded-4 h-100">
                                <div class="card-body text-center p-4">
                                    <div class="icon-container bg-success text-white rounded-circle mb-3 d-flex align-items-center justify-content-center mx-auto" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                    <h6 class="card-title text-success">Datos del Dueño</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <strong><i class="fas fa-user text-primary"></i> <b>Dueño:</b></strong>
                                            <span class="text-dark">{{ $pet->client->nombre_completo }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <strong><i class="fas fa-id-card text-primary"></i> CI:</strong>
                                            <span class="text-dark">{{ $pet->client->ci }}</span>
                                        </li>
                                        <li class="mb-2">
                                            <strong><i class="fas fa-phone-alt text-primary"></i> Teléfono:</strong>
                                            <span class="text-dark">{{ $pet->client->numero_telefono }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS adicional -->
<style>
    .card-body {
        padding: 20px;
    }

    .icon-container {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-weight: 600;
    }
</style>



        <!-- Tarjeta de Notas -->
        <div class="col-md-4" wire:ignore>
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="text-primary">Notas</h5>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#nuevaNotaModal">+
                        Nueva Nota</button>
                    <div class="modal fade" id="nuevaNotaModal" tabindex="-1" aria-labelledby="nuevaNotaModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="nuevaNotaModalLabel">Agregar Nota</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <textarea class="form-control" wire:model='note' placeholder="Escribe tu nota aquí..." maxlength="140"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary"
                                        wire:click='storeNote'>Guardar</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h5 class="text-primary">Notas</h5>
                        @if (session()->has('message'))
                            <div class="alert alert-success">{{ session('message') }}</div>
                        @endif
                        <ul class="list-unstyled">
                            @foreach ($notas as $nota)
                                <li class="note-item mb-3 p-3 bg-light rounded shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $nota->nota }}</strong> <br>
                                            <small class="text-muted">Creado el:
                                                {{ $nota->created_at->format('d-m-Y ') }}</small>
                                        </div>
                                        <div>
                                            <!-- Icono de editar -->
                                            <button wire:click="editNote({{ $nota->id }})"
                                                class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                                data-bs-target="#editNoteModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <div class="modal fade" id="editNoteModal" tabindex="-1"
                                                aria-labelledby="editNoteModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editNoteModalLabel">Editar
                                                                Nota</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="text" class="form-control"
                                                                wire:model="note" placeholder="Editar la nota...">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary"
                                                                wire:click="storeNote" data-bs-dismiss="modal">Guardar
                                                                Cambios</button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cerrar</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Formulario para eliminar -->
                                            <button wire:click="deleteNote({{ $nota->id }})"
                                                class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <livewire:pet.pet-historial-controller :petId="$petId" />

    <!-- Tarjeta de Historial y Vacunas -->


</div>
