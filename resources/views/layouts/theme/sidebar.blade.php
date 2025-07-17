<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">
        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="./index.html">
                        <img src="../src/assets/img/logo.png" alt="logo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a href="./" style="font-size: 20px !important;" class="nav-link"> INTISOFT </a>
                </div>
            </div>
            <div class="nav-item sidebar-toggle">
                <div class="btn-toggle sidebarCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-chevrons-left">
                        <polyline points="11 17 6 12 11 7"></polyline>
                        <polyline points="18 17 13 12 18 7"></polyline>
                    </svg>
                </div>
            </div>
        </div>

        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu">
                <a href="{{ route('cliente') }}" class="dropdown-toggle">
                    <div class="{{ route('cliente') }}">
                        <i class="fas fa-tachometer-alt"></i> <!-- Dashboard -->
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>

            <li class="menu menu-heading">
                <div class="heading">
                    <i class="fas fa-boxes"></i>
                    <span>Inventario y control</span>
                </div>
            </li>
            <li class="menu">
                <a href="{{ route('producto') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="{{ route('producto') }}">
                        <i class="fas fa-cube"></i> <!-- Productos -->
                        <span>Productos</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="{{ route('sucursal') }}" aria-expanded="false" class="dropdown-toggle">
                    <div>
                        <i class="fas fa-building"></i> <!-- Sucursal -->
                        <span>Stock Sucursales</span>
                    </div>
                </a>
            </li>

            <li class="menu menu-heading">
                <div class="heading">
                    <i class="fas fa-handshake"></i>
                    <span>Comercial</span>
                </div>
            </li>
            <li class="menu">
                <a href="{{ route('cliente') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="{{ route('cliente') }}">
                        <i class="fas fa-people-carry"></i> <!-- Alquileres -->
                        <span>Alquileres</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="{{ route('cliente') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="{{ route('cliente') }}">
                        <i class="fas fa-shopping-cart"></i> <!-- Ventas -->
                        <span>Ventas</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="{{ route('cliente') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="{{ route('cliente') }}">
                        <i class="fas fa-cash-register"></i> <!-- Caja -->
                        <span>Caja</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="{{ route('cliente') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="{{ route('cliente') }}">
                        <i class="fas fa-shield-alt"></i> <!-- Garantías -->
                        <span>Garantías</span>
                    </div>
                </a>
            </li>

            <li class="menu menu-heading">
                <div class="heading">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Eventos</span>
                </div>
            </li>
            <li class="menu">
                <a href="{{ route('cliente') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="{{ route('cliente') }}">
                        <i class="fas fa-ticket-alt"></i> <!-- Entradas -->
                        <span>Entradas Folklóricas</span>
                    </div>
                </a>
            </li>

            <li class="menu menu-heading">
                <div class="heading">
                    <i class="fas fa-users"></i>
                    <span>Clientes</span>
                </div>
            </li>
            <li class="menu">
                <a href="{{ route('cliente') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="{{ route('cliente') }}">
                        <i class="fas fa-user-friends"></i> <!-- Gestión de Clientes -->
                        <span>Gestión de Clientes</span>
                    </div>
                </a>
            </li>

            <li class="menu menu-heading">
                <div class="heading">
                    <i class="fas fa-chart-line"></i>
                    <span>Reportes</span>
                </div>
            </li>
            <li class="menu">
                <a href="{{ route('cliente') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="{{ route('cliente') }}">
                        <i class="fas fa-file-alt"></i> <!-- Panel de reportes -->
                        <span>Panel de Reportes</span>
                    </div>
                </a>
            </li>

            <li class="menu">
                <a href="{{ route('configuracion') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="{{ route('configuracion') }}">
                        <i class="fas fa-cogs"></i> <!-- Configuración -->
                        <span>Configuración</span>
                    </div>
                </a>
            </li>
        </ul>

    </nav>
</div>
