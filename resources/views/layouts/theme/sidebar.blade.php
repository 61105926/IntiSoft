<div class="sidebar">
    <!-- Logo -->
    <div class="logo d-flex align-items-center">
        <i class="fas fa-theater-masks text-white me-2" style="font-size: 1.5rem;"></i>
        <h4 class="text-white mb-0">IntiSoft</h4>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>

        <!-- Inventario Section -->
        <div class="nav-section mt-4">
            <small class="text-white px-3 text-uppercase fw-bold">Inventario</small>
        </div>



        <a href="{{ route('sucursal') }}" class="nav-link {{ request()->routeIs('sucursal*') ? 'active' : '' }}">
            <i class="fas fa-store"></i>
            <span>Stock por Sucursal</span>
        </a>

        <a href="{{ route('historial-producto') }}" class="nav-link {{ request()->routeIs('historial-producto*') ? 'active' : '' }}">
            <i class="fas fa-history"></i>
            <span>Historial de Productos</span>
        </a>

        <!-- Operaciones Section -->
        <div class="nav-section mt-4">
            <small class="text-white px-3 text-uppercase fw-bold">Operaciones</small>
        </div>

        <a href="{{ route('venta') }}" class="nav-link {{ request()->routeIs('venta*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i>
            <span>Ventas</span>
        </a>

        <a href="{{ route('reserva') }}" class="nav-link {{ request()->routeIs('reserva*') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i>
            <span>Reservas</span>
        </a>

        <a href="{{ route('alquiler') }}" class="nav-link {{ request()->routeIs('alquiler*') ? 'active' : '' }}">
            <i class="fas fa-handshake"></i>
            <span>Alquileres</span>
        </a>

        <!-- Finanzas Section -->
        <div class="nav-section mt-4">
            <small class="text-white px-3 text-uppercase fw-bold">Finanzas</small>
        </div>

        <a href="{{ route('caja') }}" class="nav-link {{ request()->routeIs('caja*') ? 'active' : '' }}">
            <i class="fas fa-cash-register"></i>
            <span>Caja</span>
        </a>

        <a href="{{ route('garantia') }}" class="nav-link {{ request()->routeIs('garantia*') ? 'active' : '' }}">
            <i class="fas fa-shield-alt"></i>
            <span>Garantías</span>
        </a>

        <!-- Gestión Section -->
        <div class="nav-section mt-4">
            <small class="text-white px-3 text-uppercase fw-bold">Gestión</small>
        </div>

        <a href="{{ route('cliente') }}" class="nav-link {{ request()->routeIs('cliente*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Clientes</span>
        </a>

        <a href="{{ route('entrada-folklorica') }}" class="nav-link {{ request()->routeIs('entrada-folklorica*') ? 'active' : '' }}">
            <i class="fas fa-calendar"></i>
            <span>Entradas Folklóricas</span>
        </a>

        <!-- Sistema Section -->
        <div class="nav-section mt-4">
            <small class="text-white px-3 text-uppercase fw-bold">Sistema</small>
        </div>

        <a href="#" class="nav-link">
            <i class="fas fa-chart-bar"></i>
            <span>Reportes</span>
        </a>

        <a href="{{ route('configuracion') }}" class="nav-link {{ request()->routeIs('configuracion*') ? 'active' : '' }}">
            <i class="fas fa-cogs"></i>
            <span>Configuración</span>
        </a>
    </nav>
</div>

<style>
.sidebar {
    height: 100vh;
    overflow-y: auto;
    overflow-x: hidden;
}

.sidebar-nav {
    height: calc(100vh - 80px);
    overflow-y: auto;
    overflow-x: hidden;
    padding-bottom: 20px;
}

.nav-section {
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
}

.nav-section small {
    font-size: 0.7rem;
    letter-spacing: 0.5px;
    opacity: 0.7;
}

/* Personalizar scrollbar */
.sidebar::-webkit-scrollbar,
.sidebar-nav::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track,
.sidebar-nav::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

.sidebar::-webkit-scrollbar-thumb,
.sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
}

.sidebar::-webkit-scrollbar-thumb:hover,
.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}
</style>

