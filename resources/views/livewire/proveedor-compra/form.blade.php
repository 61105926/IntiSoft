@include('common.modalhead')

<div>
    <h5 class="mt-4 mb-4 text-primary fw-bold">Agregar Productos Comprados</h5>

    <div class="row g-3">
        <!-- Seleccionar Proveedor -->
        <div class="col-md-3" wire:ignore>
            <label for="client_id" class="form-label">Seleccione Proveedor</label>
            <select id="provider_select" class="form-select form-select-lg" wire:model="selectedClientId">
                <option value="">Seleccione un proveedor</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->proveedor_nombre }}</option>
                @endforeach
            </select>
            @error('selectedClientId')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Mostrar información del proveedor seleccionado -->
        @if ($selectedClient)
            <div class="col-md-3">
                <label class="form-label">Número de Cuenta</label>
                <input type="text" class="form-control bg-light" value="{{ $selectedClient->numero_cuenta }}" disabled>
            </div>
            <div class="col-md-3">
                <label class="form-label">Teléfono</label>
                <input type="text" class="form-control bg-light" value="{{ $selectedClient->telefono1 }}" disabled>
            </div>
        @endif
    </div>

    <!-- Código de producto, cantidad y precio -->
    <div class="row g-3 mt-4">
        <div class="col-md-6" wire:ignore>
            <label for="product_id" class="form-label">Seleccione Producto</label>
            <select id="product_select" class="form-select form-select-lg" wire:model="selectedProduct">
                <option value="">Seleccione un producto</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->codigo_producto }} | {{ $product->nombre_producto }}</option>
                @endforeach
            </select>
            @error('selectedProduct') 
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-md-2">
            <label for="quantity" class="form-label">Cantidad</label>
            <input type="number" class="form-control" wire:model="quantity" min="1">
            @error('quantity') 
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-md-2">
            <label for="price" class="form-label">Precio Unitario</label>
            <input type="number" class="form-control" wire:model="price" min="0" step="0.01">
            @error('price') 
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-md-2">
            <label for="total" class="form-label">Precio Total</label>
            <input type="text" class="form-control bg-light"
                value="{{ number_format((float) ($quantity ?? 0) * (float) ($price ?? 0), 2) }}" disabled>
        </div>

        <div class="col-md-3">
            <label for="lote" class="form-label">Lote</label>
            <input type="text" id="lote" class="form-control" wire:model="lote">
            @error('lote') 
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-md-3">
            <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
            <input type="date" id="fecha_vencimiento" class="form-control" wire:model="fechaVencimiento">
            @error('fechaVencimiento') 
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
       

        <div class="col-md-3">
            <div class="d-flex justify-content-end mt-4">
                <button class="btn btn-primary px-5 py-2" wire:click="addProduct">
                    <i class="fas fa-plus-circle"></i> Agregar
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de productos agregados -->
    <div class="table-responsive mt-5">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="table-primary text-center">
                    <th class="bg-primary text-white">Código</th>
                    <th class="bg-primary text-white">Cantidad</th>
                    <th class="bg-primary text-white">Precio Unitario</th>
                    <th class="bg-primary text-white">Precio Total</th>
                    <th class="bg-primary text-white">Lote</th>
                    <th class="bg-primary text-white">Fecha de Vencimiento</th>
                    <th class="bg-primary text-white">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $index => $item)
                    <tr class="text-center">
                        <td>{{ $item['code'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['price'], 2) }} Bs</td>
                        <td class="fw-bold">{{ number_format($item['total'], 2) }} Bs</td>
                        <td>{{ $item['lote'] }}</td>
                        <td>{{ $item['fecha_vencimiento'] }}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" wire:click="removeProduct({{ $index }})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                @if(empty($cart))
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">
                            No hay productos agregados
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <h6 class="mt-4">Total General: {{ number_format($total, 2) }} Bs</h6>
    <div class="col-md-3" wire:ignore>
        <label for="created_at" class="form-label">Fecha de Compra</label>
        <input type="date" id="created_at" class="form-control" wire:model="created_at">
        @error('created_at') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="caja_id">Seleccione su Caja:</label>
        <select required id="caja_id" wire:model="caja_id" class="form-control">
            <option value="" disable selected hidden>Seleccione una Caja</option>
            @foreach ($atm as $atms)
                <option value="{{ $atms->id }}">Caja {{ $atms->id }}</option>
            @endforeach
        </select>
    </div>
</div>

<script>
document.addEventListener('livewire:load', function() {
    new TomSelect("#provider_select", {
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

    new TomSelect("#product_select", {
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

<style>
.ts-wrapper {
    width: 100%;
}

.ts-control {
    border: 1px solid #ced4da;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    border-radius: 0.25rem;
    background-color: #fff;
    min-height: 38px;
}

.ts-dropdown {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.ts-dropdown .active {
    background-color: #650abb;
    color: #fff;
}

.no-results {
    padding: 10px;
    text-align: center;
    color: #6c757d;
}

.ts-control:focus {
    border-color: #650abb;
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}

.table thead th {
    background-color: #650abb !important;
    color: white !important;
    border-color: #3251d4;
    vertical-align: middle;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
    padding: 0.75rem;
}

.btn-danger {
    transition: all 0.3s ease;
}

.btn-danger:hover {
    transform: scale(1.1);
}

/* Estilo para mensajes de error */
.text-danger {
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}
</style>

@include('common.modalfooter')
