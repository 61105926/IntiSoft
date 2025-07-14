@include('common.modalhead')
<div>
    <div class="container my-1">
        <!-- Sección Cliente -->
        <div class="d-flex align-items-center mb-4 p-4 rounded shadow-sm bg-white">
            <div class="avatar me-3">
                <!-- Imagen del cliente (opcional) -->
                <img src="https://cdn-icons-png.flaticon.com/512/7127/7127352.png" alt="Cliente" class="rounded-circle">
            </div>
            <div class="col-md-6" wire:ignore>
                <h5 class="mb-0">Seleccione Cliente</h5>
                <select id="sales_client" class="form-select form-select-lg" wire:model="client_id">
                    <option value="">Seleccione un cliente</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->ci }} | {{ $client->nombre_completo }}
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Formulario para agregar productos -->
        <div class="card p-4 shadow-lg border-0 rounded-3">
            <h5 class="mb-4 text-primary fw-bold">Agregar Producto/Servicio</h5>
            <div class="row g-3">
                <!-- Primera fila -->
                <div class="col-md-6" wire:ignore>
                    <label for="product_code" class="form-label">Código del Producto</label>
                    <select id="product_code" class="form-select form-select-lg" wire:model="selected_product_id">
                        <option value="">Seleccione un producto</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->codigo_producto }} |
                                {{ $product->nombre_producto }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="type_product" class="form-label">Categoría</label>
                    <input type="text" class="form-control bg-light" wire:model="type_product" disabled>
                </div>

                <div class="col-md-3">
                    <label for="stockProduct" class="form-label">Stock</label>
                    <input type="text" id="stockProduct" class="form-control bg-light"
                        value="{{ isset($selected_product_id) ? $localStock[$selected_product_id] ?? 0 : 'N/A' }}"
                        disabled>
                </div>
            </div>

            <!-- Segunda fila -->
            <div class="row g-3 mt-3">
                <div class="col-md-3">
                    <label for="quantity" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" wire:model="quantity" min="1">
                </div>

                <div class="col-md-3">
                    <label for="price" class="form-label">Precio Unitario</label>
                    <input type="text" class="form-control bg-light" wire:model="price" disabled>
                </div>

                <div class="col-md-3">
                    <label for="price_total_product" class="form-label">Precio Total</label>
                    <input type="text" class="form-control bg-light" wire:model="price_total_product" disabled>
                </div>
            </div>


            <div class="d-flex justify-content-end mt-4">
                <button class="btn btn-primary px-5 py-2" wire:click="addProduct">
                    <i class="fas fa-plus-circle"></i> Agregar
                </button>
            </div>

            <!-- Tabla de productos agregados -->
            <div class="table-responsive mt-5">
                <table class="table table-hover table-striped align-middle">
                    <thead class="bg-primary text-dark">
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Precio Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $item)
                            <tr>
                                <td>{{ $item['code'] }}</td>
                                <td>{{ $item['description'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ number_format($item['price'], 2) }} Bs</td>
                                <td>{{ number_format($item['price'] * $item['quantity'], 2) }} Bs</td>
                                <td>
                                    <button class="btn btn-danger btn-sm"
                                        wire:click="removeProduct('{{ $item['unique_id'] }}')">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Resumen de la venta -->
            <div class="container mt-5">
                <h6 class="text-primary fw-bold">Detalles de Pago</h6>
                <div class="row">
                    <!-- Fecha de Venta (Columna Superior) -->
                    <div class="col-md-3" wire:ignore>
                        <label for="created_at">Fecha de Venta:</label>
                        <input type="date" id="created_at" class="form-control" wire:model="created_at">
                        @error('created_at')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <!-- Métodos de Pago (Columna Izquierda) -->
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="efectivo" class="form-label">Pago en Efectivo (Bs):</label>
                            <input type="number" class="form-control" id="efectivo" wire:model="cash_amount" placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label for="debito" class="form-label">Pago en Débito (Bs):</label>
                            <input type="number" class="form-control" id="debito" wire:model="deposit_amount" placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label for="transferencia" class="form-label">Pago en Transferencia (Bs):</label>
                            <input type="number" class="form-control" id="transferencia" wire:model="transfer_amount" placeholder="0">
                        </div>
                    </div>
                    <!-- Total a Pagar (Columna Derecha) -->
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="total" class="form-label">Total a Pagar (Bs):</label>
                            <input type="text" class="form-control" id="total" value="{{ number_format($total, 2) }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="cambio" class="form-label">Cambio a Devolver (Bs):</label>
                            <input type="text" class="form-control" id="cambio"
                                value="{{ number_format((float) ($cash_amount ?? 0) + (float) ($deposit_amount ?? 0) + (float) ($transfer_amount ?? 0) - (float) ($total ?? 0), 2) }}"
                                disabled>
                        </div>
                        <div class="mb-3">
                            <label for="client_id">Seleccione su Caja:</label>
                            <select required id="control" wire:model='caja_id' name="caja_id" class="form-control" style="width: 100%">
                                <option value="" disabled selected hidden>Seleccione una Caja</option>
                                @foreach ($atm as $atms)
                                    <option value="{{ $atms->id }}">Caja {{ $atms->id }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Detalles de Venta (Columna Derecha) -->
                    <div class="col-md-6">
                        <div class="text-end p-4 bg-light rounded shadow-sm">
                            <!-- Subtotal -->
                            <p class="mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <strong class="fs-5">{{ number_format($subtotal, 2) }} Bs</strong>
                            </p>
            
                            <!-- Descuento -->
                            <div class="d-flex align-items-center justify-content-end mb-2">
                                <span class="text-muted me-2">Descuento:</span>
                                <div class="input-group w-auto" style="max-width: 150px;">
                                    <input type="number" class="form-control" wire:model="discount" placeholder="0" min="0" max="100" aria-label="Descuento (%)">
                                    <span class="input-group-text">Bs</span>
                                </div>
                            </div>
            
                            <!-- Total -->
                            <h4 class="text-danger fw-bold mt-3">TOTAL: {{ number_format($total, 2) }} Bs</h4>
                        </div>
                    </div>
                </div>
            </div>
            

        </div>
    </div>





    <script>
        document.addEventListener('livewire:load', function() {
            new TomSelect("#sales_client", {
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
            new TomSelect("#product_code", {
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                render: {
                    no_results: function(data, escape) {
                        return '<div class="no-results">No se encontraron resultados</div>';
                    }
                },
                onInitialize: function() {
                    var selectElement = this.$control;
                    selectElement.classList.add('form-control');
                }
            });
        });
    </script>
    <style>
        .description-cell {
            max-height: 100px;
            /* Altura máxima de la celda */
            overflow: hidden;
            /* Ocultar el desbordamiento */
            position: relative;
        }

        .content {
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            overflow-y: auto;
            padding-right: 20px;
            /* Espacio para la barra de desplazamiento */
        }

        .content:hover {
            max-height: none;
            /* Permitir que se muestre todo el contenido cuando se pasa el mouse */
        }

        /* Estilos para el botón de toggle (opcional) */
        .toggle-button {
            display: block;
            margin-top: 10px;
        }
    </style>
</div>

@include('common.modalfooter')
