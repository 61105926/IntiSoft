@include('common.modalhead')

<div class="row">
    <!-- Cliente y Mascota en una misma fila -->
    <div class="col-md-6">
        <div class="form-group text-center">
            <!-- Imagen ilustrativa del cliente -->
            <img src="https://cdn-icons-png.flaticon.com/512/7127/7127352.png" class="rounded-circle mb-2" alt="Cliente"
                width="100" height="100">
            <label for="client_id" class="d-block">Cliente</label>
            <div wire:ignore>
                <select id="cita_client" class="form-control" wire:model="client_id">
                    <option value="">Seleccione un Cliente</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->ci }} | {{ $client->nombre_completo }}
                        </option>
                    @endforeach
                </select>
            </div>

            @error('client_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group text-center">
            <!-- Imagen ilustrativa de la mascota -->
            @if ($selectedPetImage)
                <img src="{{ asset('storage/pet/' . $selectedPetImage) }}" class="rounded-circle mb-2"
                    alt="Mascota seleccionada" width="100" height="100">
            @else
                <img src="https://gestion.portalbiesa.com/redaccio/arxius/imatges/202209/770_1662979063blog_post_coccidiosis_1.jpg"
                    class="rounded-circle mb-2" alt="Mascota por defecto" width="100" height="100">
            @endif <label for="pets_id" class="d-block">Mascota</label>
            <div >
                <select id="cita_pet" class="form-control" wire:model="pets_id">
                    <option value="">Seleccione una Mascota</option>
                    @foreach ($pets as $pet)
                        <option value="{{ $pet->id }}">{{ $pet->nombre }}</option>
                    @endforeach
                </select>
            </div>


            @error('pets_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<!-- Fecha y Hora en una misma fila -->
<div class="row mt-3">
    <div class="col-md-6">
        <div class="form-group">
            <label for="fecha">Fecha de la Cita</label>
            <input type="date" id="fecha" class="form-control" wire:model="fecha">
            @error('fecha')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="hora">Hora de la Cita</label>
            <input type="time" id="hora" class="form-control" wire:model="hora">
            @error('hora')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<!-- Motivo con un área de texto más estilizada -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="form-group">
            <label for="motivo">Motivo de la Cita</label>
            <textarea id="motivo" class="form-control" wire:model="motivo" placeholder="Escriba el motivo de la cita"
                rows="3"></textarea>
            @error('motivo')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
<script>
    document.addEventListener('livewire:load', function() {
        new TomSelect("#cita_client", {
            sortField: {
                field: "text",
                direction: "asc"
            },
            render: {
                no_results: function(data, escape) {
                    return '<div class="no-results">No se encontraron resultados</div>';
                }
            }
        });
      
    });
</script>

@include('common.modalfooter')
