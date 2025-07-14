<div class="container mt-4">
    <h2 class="mb-4 text-center text-primary">Lista Vacunas</h2>
    <p class="text-muted text-center mb-4">Distribución de vacunas por especie:</p>

    <!-- Recorrer todas las especies -->
    @foreach ($especies as $especie)
        <div class="card mb-3 shadow-lg" style="border-radius: 15px; background-color: {{ $colores[$especie->id] ?? '#ffffff' }};">
            <div class="card-header d-flex justify-content-between align-items-center" 
                 style="cursor: pointer;" 
                 wire:click="toggleEspecie({{ $especie->id }})">
                <h4 class="mb-0">
                    <i class="fas fa-syringe text-primary"></i> {{ $especie->nombre }}
                </h4>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="fas {{ $especieSeleccionada === $especie->id ? 'fa-minus' : 'fa-plus' }}"></i>
                </button>
            </div>

            @if ($especieSeleccionada === $especie->id)
                @if ($especie->vacunas->isNotEmpty())
                    <div class="card-body">
                        <div class="d-flex flex-wrap">
                            <!-- Recorrer las vacunas de la especie actual -->
                            @foreach ($especie->vacunas as $vacuna)
                                <div class="badge badge-light m-1 p-2" style="background-color: #200404; border-radius: 5px; transition: background-color 0.3s;">
                                    {{ $vacuna->nombre }}
                                    <a href="#" class="ml-2 text-danger" wire:click="eliminarVacuna({{ $vacuna->id }})">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Formulario para agregar nueva vacuna -->
                <div class="card-footer">
                    <form wire:submit.prevent="agregarVacuna({{ $especie->id }})">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Nombre de la vacuna" wire:model="nombreVacuna" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-success" type="submit">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    @endforeach
</div>

<style>
    /* Estilos adicionales para mejorar la apariencia */
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .badge {
        transition: background-color 0.2s, transform 0.2s;
    }
    .badge:hover {
        background-color: #f0e7e0;
        transform: scale(1.05);
    }

    .card-header:hover {
        background-color: #e7f0ff;
        transition: background-color 0.3s;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('show-modal', msg => {
            $('#exampleModal').modal('show');

        });
        window.livewire.on('person-added', msg => {
            $('#exampleModal').modal('hide');

        });
        window.livewire.on('person-updated', msg => {
            $('#exampleModal').modal('hide');

        });
    });
    document.addEventListener('livewire:load', function() {
        Livewire.on('mostrarAlertaSuccess', function(accion, codigo) {
            var mensaje = accion;

            Swal.fire({
                position: 'top-center',
                icon: 'success',
                title: mensaje,
                showConfirmButton: false,
                timer: 2000
            });
        });
    });

    function Confirm(id) {
        Swal.fire({
            icon: 'warning',
            title: '¡Desea cambiar el estado del registro!',
            text: '¡No podrás revertir esto!',
        }).then(function(result) {
            if (result.value) {
                window.livewire.emit('deleteRow', id)
                Swal.close()
            }
        })
    }
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
