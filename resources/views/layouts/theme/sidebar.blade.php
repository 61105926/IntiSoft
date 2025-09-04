<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">

        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="./index.html">
                        <img src="../src/assets/img/logo.png"  alt="logo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a href="./" style="font-size: 20px !important;" class="nav-link"> Veterinaria </a>
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
                <a href="#" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>

            <li class="menu menu-heading">
                <div class="heading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-minus">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Inventario</span>
                </div>
            </li>

            <li class="menu">
                <a href="#submenu-inventario" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-boxes"></i> <!-- Icono de inventario -->
                        <span>Inventario</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="submenu-inventario" data-bs-parent="#accordionExample">
                    <li class="menu">
                        <a href="{{ route('producto') }}">Productos</a>
                    </li>
                    <li class="menu">
                        <a href="{{ route('sucursal') }}">Stock por Sucursal</a>
                    </li>
                    <li class="menu">
                        <a href="#">Historial de Productos</a>
                    </li>
                </ul>
            </li>

            <li class="menu menu-heading">
                <div class="heading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-minus">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Operaciones</span>
                </div>
            </li>

            <li class="menu">
                <a href="#submenu-operaciones" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-shopping-cart"></i> <!-- Icono de ventas y alquileres -->
                        <span>Operaciones</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="submenu-operaciones" data-bs-parent="#accordionExample">
                    <li class="menu">
                        <a href="#">Ventas</a>
                    </li>
                    <li class="menu">
                        <a href="{{ route('reserva') }}">Reservas</a>
                    </li>
                    <li class="menu">
                        <a href="{{ route('alquiler') }}">Alquileres</a>
                    </li>
                </ul>
            </li>

            <li class="menu menu-heading">
                <div class="heading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-minus">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Finanzas</span>
                </div>
            </li>

            <li class="menu">
                <a href="#submenu-finanzas" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-credit-card"></i> <!-- Icono de finanzas -->
                        <span>Finanzas</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="submenu-finanzas" data-bs-parent="#accordionExample">
                    <li class="menu">
                        <a href="#">Caja</a>
                    </li>
                    <li class="menu">
                        <a href="#">Garantías</a>
                    </li>
                </ul>
            </li>

            <li class="menu">
                <a href="{{ route('cliente') }}" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-users"></i> <!-- Icono clientes -->
                        <span>Clientes</span>
                    </div>
                </a>
            </li>

            <li class="menu">
                <a href="#" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-calendar"></i> <!-- Icono entradas folklóricas -->
                        <span>Entradas Folklóricas</span>
                    </div>
                </a>
            </li>

            <li class="menu">
                <a href="#" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-chart-bar"></i> <!-- Icono reportes -->
                        <span>Reportes</span>
                    </div>
                </a>
            </li>

            <li class="menu">
                <a href="{{ route('configuracion') }}" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-cogs"></i> <!-- Icono configuración -->
                        <span>Configuración</span>
                    </div>
                </a>
            </li>

        </ul>
    </nav>
</div>
