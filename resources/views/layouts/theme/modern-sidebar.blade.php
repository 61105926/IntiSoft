<div class="modern-sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <h3>
            <i class="fas fa-theater-masks"></i>
            <span class="text-gradient">IntiSoft</span>
        </h3>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <div class="nav-section">
            <a href="{{ route('dashboard') }}" class="nav-link-modern {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- Inventario Section -->
        <div class="nav-section inventario-theme">
            <div class="nav-section-title">Inventario</div>

            <a href="{{ route('producto') }}" class="nav-link-modern {{ request()->routeIs('producto*') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i>
                <span>Productos</span>
            </a>

            <a href="{{ route('sucursal') }}" class="nav-link-modern {{ request()->routeIs('sucursal*') ? 'active' : '' }}">
                <i class="fas fa-store"></i>
                <span>Sucursales</span>
            </a>

            <a href="{{ route('stock-sucursal') }}" class="nav-link-modern {{ request()->routeIs('stock-sucursal*') ? 'active' : '' }}">
                <i class="fas fa-warehouse"></i>
                <span>Stock por Sucursal</span>
            </a>

            <a href="{{ route('historial-producto') }}" class="nav-link-modern {{ request()->routeIs('historial-producto*') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Historial de Productos</span>
            </a>
        </div>

        <!-- Operaciones Section -->
        <div class="nav-section operaciones-theme">
            <div class="nav-section-title">Operaciones</div>

            <a href="{{ route('venta') }}" class="nav-link-modern {{ request()->routeIs('venta*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Ventas</span>
            </a>

            <a href="{{ route('reserva') }}" class="nav-link-modern {{ request()->routeIs('reserva*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i>
                <span>Reservas</span>
            </a>

            <a href="{{ route('alquiler') }}" class="nav-link-modern {{ request()->routeIs('alquiler*') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i>
                <span>Alquileres</span>
            </a>
        </div>

        <!-- Finanzas Section -->
        <div class="nav-section finanzas-theme">
            <div class="nav-section-title">Finanzas</div>

            <a href="{{ route('caja') }}" class="nav-link-modern {{ request()->routeIs('caja*') ? 'active' : '' }}">
                <i class="fas fa-cash-register"></i>
                <span>Caja</span>
            </a>

            <a href="{{ route('garantia') }}" class="nav-link-modern {{ request()->routeIs('garantia*') ? 'active' : '' }}">
                <i class="fas fa-shield-alt"></i>
                <span>Garantías</span>
            </a>

            <a href="#" class="nav-link-modern">
                <i class="fas fa-chart-bar"></i>
                <span>Reportes Financieros</span>
            </a>
        </div>

        <!-- Gestión Section -->
        <div class="nav-section gestion-theme">
            <div class="nav-section-title">Gestión</div>

            <a href="{{ route('cliente') }}" class="nav-link-modern {{ request()->routeIs('cliente*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Clientes</span>
            </a>

            <a href="{{ route('eventos-folkloricos') }}" class="nav-link-modern {{ request()->routeIs('eventos-folkloricos*') ? 'active' : '' }}">
                <i class="fas fa-mask"></i>
                <span>Eventos Folklóricos</span>
            </a>

            <a href="{{ route('entrada-folklorica') }}" class="nav-link-modern {{ request()->routeIs('entrada-folklorica*') ? 'active' : '' }}">
                <i class="fas fa-calendar"></i>
                <span>Entradas Folklóricas</span>
            </a>

            <a href="{{ route('usuario') }}" class="nav-link-modern {{ request()->routeIs('usuario*') ? 'active' : '' }}">
                <i class="fas fa-user-friends"></i>
                <span>Usuarios</span>
            </a>

            <a href="{{ route('rool') }}" class="nav-link-modern {{ request()->routeIs('rool*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i>
                <span>Roles y Permisos</span>
            </a>
        </div>

        <!-- Sistema Section -->
        <div class="nav-section sistema-theme">
            <div class="nav-section-title">Sistema</div>

            <a href="#" class="nav-link-modern">
                <i class="fas fa-chart-pie"></i>
                <span>Reportes Generales</span>
            </a>

            <a href="{{ route('configuracion') }}" class="nav-link-modern {{ request()->routeIs('configuracion*') ? 'active' : '' }}">
                <i class="fas fa-cogs"></i>
                <span>Configuración</span>
            </a>

            <a href="#" class="nav-link-modern">
                <i class="fas fa-download"></i>
                <span>Respaldos</span>
            </a>

            <a href="#" class="nav-link-modern">
                <i class="fas fa-question-circle"></i>
                <span>Ayuda</span>
            </a>
        </div>
    </nav>
</div>

<script>
    // Auto-detect current module and apply theme
    document.addEventListener('DOMContentLoaded', function() {
        const currentRoute = window.location.pathname;
        const sidebar = document.querySelector('.modern-sidebar');

        // Remove existing theme classes
        sidebar.classList.remove('inventario-theme', 'operaciones-theme', 'finanzas-theme', 'gestion-theme', 'sistema-theme');

        // Apply theme based on current route
        if (currentRoute.includes('producto') || currentRoute.includes('sucursal') || currentRoute.includes('historial-producto') || currentRoute.includes('stock-sucursal')) {
            sidebar.classList.add('inventario-theme');
        } else if (currentRoute.includes('venta') || currentRoute.includes('reserva') || currentRoute.includes('alquiler')) {
            sidebar.classList.add('operaciones-theme');
        } else if (currentRoute.includes('caja') || currentRoute.includes('garantia')) {
            sidebar.classList.add('finanzas-theme');
        } else if (currentRoute.includes('cliente') || currentRoute.includes('eventos-folkloricos') || currentRoute.includes('entrada-folklorica') || currentRoute.includes('usuario') || currentRoute.includes('roles')) {
            sidebar.classList.add('gestion-theme');
        } else if (currentRoute.includes('configuracion')) {
            sidebar.classList.add('sistema-theme');
        }
    });
</script>