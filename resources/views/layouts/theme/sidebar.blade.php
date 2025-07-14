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
                    <a href="./" style="font-size: 20px !important;" class="nav-link"> IntiSoft </a>
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
                <a href="{{ route('dashboard') }}" class="dropdown-toggle">
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
                    <span>Aplicaciones</span>
                </div>
            </li>

            <li class="menu">
                <a href="#gestion-mascota" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-paw"></i> <!-- Icono de calendario -->
                        <span>Mascota</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="gestion-mascota" data-bs-parent="#accordionExample">


                    <li class="menu" class="menu">
                        <a href="{{ route('pet') }}"> Mascota </a>
                    </li>
                    <li class="menu">
                        <a href="{{ route('razas') }}">
                            Razas
                        </a>
                    </li>
                    <li class="menu">
                        <a href="{{ route('especie') }}">
                            Especie
                        </a>
                    </li>
                    <li class="menu">
                        <a href="{{ route('vacuna') }}">
                            Vacuna
                        </a>
                    </li>

                </ul>
            </li>


            {{-- <li class="menu">
                <a href="./personal" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-users"></i> <!-- Icono de calendario -->
                        <span>Personal</span>
                    </div>
                </a>
            </li> --}}

            <li class="menu">
                <a href="{{ route('cliente') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-user"></i> <!-- Icono de cliente -->
                        <span>Cliente</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="{{ route('inventory') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-boxes"></i> <!-- Icono de inventario -->
                        <span>Inventario</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="{{ route('ventas') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-shopping-cart"></i> <!-- Icono de ventas -->
                        <span>Ventas</span>
                    </div>
                </a>
            </li>
     
            <li class="menu">
                <a href="#gestion-proveedor" data-bs-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-box"></i> <!-- Icono de gestión de proveedor -->
                        <span>Proveedor</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled @if (request()->is('proveedor*') || request()->is('compras/*')) show @endif"
                    id="gestion-proveedor" data-bs-parent="#accordionExample">

                    <!-- Submenú Proveedor -->
                    <li class="menu">
                        <a href="{{ route('proveedor') }}">
                            <span>Proveedor</span>
                        </a>
                    </li>

                    <!-- Submenú Compras -->
                    <li class="menu">
                        <a href="{{ route('proveedorcompra') }}">
                            <span>Compras</span>
                        </a>
                    </li>
                </ul>
            </li>
          

            @can('user.view')
                <li class="menu">
                    <a href="#gestion-usuario" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <i class="fas fa-user"></i> <!-- Icono de calendario -->
                            <span>Usuario</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled @if (request()->is('expediente*') || request()->is('factura/*') || request()->is('lista-maestra/*')) show @endif"
                        id="gestion-usuario" data-bs-parent="#accordionExample">


                        <li class="menu" class="menu">
                            <a href="{{ route('roles') }}"> Roles </a>
                        </li>
                        <li class="menu">
                            <a href="{{ route('usuario') }}">
                                Usuario
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            <li class="menu">
                <a href="{{ route('configuracion') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <i class="fas fa-cog"></i> <!-- Icono de configuración -->
                        <span>Configuración</span>
                    </div>
                </a>
            </li>

        </ul>
    </nav>
</div>
