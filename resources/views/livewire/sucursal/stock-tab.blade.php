   <!-- STOCK POR SUCURSAL -->
        <div class="tab-pane fade show active" id="stock" role="tabpanel">

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" placeholder="Buscar por nombre o código..."
                                    wire:model="searchTerm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model="filterSucursal">
                                <option value="">Todas las sucursales</option>
                                @foreach ($sucursales as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model="filterEstado">
                                <option value="">Todos los estados</option>
                                @foreach ($estados as $estado)
                                    <option value="{{ $estado }}">{{ Str::title(str_replace('_', ' ', $estado)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla Stock -->
            <div class="card">
                <div class="card-header fw-bold">
                    Stock por Sucursal ({{ count($filteredStock) }})
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Sucursal</th>
                                <th>Stock</th>
                                <th>Estado</th>
                                <th>Precios</th>
                                <th>Valor Stock</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filteredStock as $stock)
                                <tr>
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ $stock->producto->nombre }}</div>
                                            <div class="text-muted small">{{ $stock->producto->codigo }}</div>
                                            <span
                                                class="badge bg-secondary text-uppercase">{{ str_replace('_', ' ', $stock->categoria_nombre) }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $stock->sucursal->nombre }}</td>
                                    <td>
                                        <div class="small">
                                            <div><strong>Actual:</strong> {{ $stock->stock_actual }}</div>
                                            <div class="text-muted">Mín: {{ $stock->stock_minimo }}</div>
                                            <div class="text-muted">Disp: {{ $stock->stock_disponible }}</div>
                                            <div class="text-warning">Res: {{ $stock->stock_reservado }}</div>
                                            <div class="text-info">Alq: {{ $stock->stock_alquilado }}</div>
                                        </div>
                                    </td>
                                    <td>{!! $this->getEstadoBadge($stock->estado_stock) !!}</td>
                                    <td>
                                        <div class="small">
                                            <div>Venta: Bs.
                                                {{ number_format($stock->precio_venta_sucursal, 2, ',', '.') }}</div>
                                            <div>Alquiler: Bs.
                                                {{ number_format($stock->precio_alquiler_sucursal, 2, ',', '.') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fw-bold">Bs.
                                        {{ number_format($stock->stock_actual * $stock->precio_venta_sucursal, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-primary btn-sm"
                                                wire:click="editarStock({{ $stock->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>