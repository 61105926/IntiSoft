<div>
    <div class="row row-cols-1 row-cols-md-5 g-4 mb-4 mt-2">
        @php
            $estadisticas = $estadisticas ?? [
                'total_items' => 0,
                'sin_stock' => 0,
                'stock_bajo' => 0,
                'stock_ok' => 0,
                'valor_total' => 0,
            ];
        @endphp

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Ítems</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['total_items'] }}</p>
                    </div>
                    <i class="fas fa-boxes text-primary fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Sin Stock</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['sin_stock'] }}</p>
                    </div>
                    <i class="fas fa-times-circle text-danger fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Stock Bajo</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['stock_bajo'] }}</p>
                    </div>
                    <i class="fas fa-exclamation-triangle text-warning fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Stock OK</p>
                        <p class="fs-2 fw-bold mb-0">{{ $estadisticas['stock_ok'] }}</p>
                    </div>
                    <i class="fas fa-check-circle text-success fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Valor Total (Bs)</p>
                        <p class="fs-2 fw-bold mb-0">{{ number_format($estadisticas['valor_total'], 2, ',', '.') }}</p>
                    </div>
                    <i class="fas fa-money-bill-wave text-success fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.sucursal.transferencia-modal')

    <ul class="nav nav-tabs mb-3" id="stockTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tabActivo === 'stock' ? 'active' : '' }}" type="button" role="tab"
                wire:click="cambiarTab('stock')">
                Stock por Sucursal
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tabActivo === 'transferencias' ? 'active' : '' }}" type="button" role="tab"
                wire:click="cambiarTab('transferencias')">
                Transferencias
            </button>
        </li>
    </ul>


    <div class="tab-content" id="stockTabsContent">
        @include('livewire.sucursal.stock-tab')
        @include('livewire.sucursal.transferencias-tab')
    </div>
</div>
