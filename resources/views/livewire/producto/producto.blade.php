{{-- resources/views/livewire/producto/producto.blade.php --}}
<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Productos</h2>
        <div>
            <button type="button" wire:click="showCreateModal" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Producto
            </button>
            @include('livewire.producto.form')

        </div>
    </div>

    {{-- Mensajes flash --}}
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtros --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label>Buscar</label>
                    <input type="text" class="form-control" wire:model.live="search"
                        placeholder="Nombre o descripción...">
                </div>
                <div class="col-md-2">
                    <label>Sucursal</label>
                    <select class="form-select" wire:model.live="sucursal_id">
                        <option value="">Todas</option>
                        @foreach ($sucursales as $s)
                            <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Categoría</label>
                    <select class="form-select" wire:model.live="categoria_id">
                        <option value="">Todas</option>
                        @foreach ($categorias as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Estado Stock</label>
                    <select class="form-select" wire:model.live="estado_stock">
                        <option value="">Todos</option>
                        <option value="sin_stock">Sin Stock</option>
                        <option value="stock_bajo">Stock Bajo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Disponible Venta</label>
                    <select class="form-select" wire:model.live="disponible_venta">
                        <option value="">Todos</option>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button wire:click="clearFilters" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de productos --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Sucursal</th>
                            <th>Stock</th>
                            <th>Precio Venta</th>
                            <th>Precio Alquiler</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $prod)
                            <tr>
                                <td>{{ $prod->codigo }}</td>
                                <td>{{ $prod->nombre }}</td>
                                <td>{{ $prod->categoria->nombre ?? 'Sin categoría' }}</td>

                                {{-- Mostrar nombre de sucursal desde stock_por_sucursals --}}
                                <td>
                                    @php
                                        $sucursal = $sucursales->firstWhere('id', $prod->sucursal_id);
                                    @endphp
                                    {{ $sucursal ? $sucursal->nombre : 'N/A' }}
                                </td>

                                <td>
                                    <span
                                        class="badge bg-{{ ($prod->stock_actual ?? 0) <= 0 ? 'danger' : (($prod->stock_actual ?? 0) <= ($prod->stock_minimo ?? 0) ? 'warning' : 'success') }}">
                                        {{ $prod->stock_actual ?? 0 }}
                                    </span>
                                </td>

                                <td>${{ number_format($prod->precio_venta_sucursal ?? 0, 2) }}</td>
                                <td>${{ number_format($prod->precio_alquiler_sucursal ?? 0, 2) }}</td>


                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button wire:click="showEditModal({{ $prod->id }})"
                                            class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="showStockModal({{ $prod->id }})"
                                            class="btn btn-outline-info">
                                            <i class="fas fa-plus-square"></i>
                                        </button>
                                        <button wire:click="showDetailModal({{ $prod->id }})"
                                            class="btn btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay productos</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
            <div class="mt-3 d-flex justify-content-end">
                {{ $productos->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Formulario Nuevo/Editar --}}
    @include('livewire.producto.form')
    =
    {{-- Modal Detalle --}}
    @include('livewire.producto.detail')

    <script>
        document.addEventListener('livewire:load', () => {
            Livewire.on('showModal', () => new bootstrap.Modal('#productoModal').show());
            Livewire.on('hideModal', () => bootstrap.Modal.getInstance('#productoModal').hide());
            Livewire.on('showStockModal', () => new bootstrap.Modal('#stockModal').show());
            Livewire.on('hideStockModal', () => bootstrap.Modal.getInstance('#stockModal').hide());
            Livewire.on('showDetailModal', () => new bootstrap.Modal('#detailModal').show());
        });
    </script>
</div>
