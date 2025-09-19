<nav class="navbar-custom d-flex justify-content-between align-items-center">
    <div class="navbar-brand">
        <h5 class="mb-0 fw-semibold text-dark">
            @if(View::hasSection('page-title'))
                @yield('page-title')
            @else
                Dashboard
            @endif
        </h5>
    </div>

    <div class="navbar-nav d-flex flex-row align-items-center">
        <!-- Notifications -->
        <div class="nav-item dropdown me-3">
            <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-bell text-muted"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                    3
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                <li><h6 class="dropdown-header">Notificaciones</h6></li>
                <li><a class="dropdown-item" href="#"><small>Stock bajo en Pollera Paceña</small></a></li>
                <li><a class="dropdown-item" href="#"><small>Alquiler vence hoy</small></a></li>
                <li><a class="dropdown-item" href="#"><small>Nueva reserva registrada</small></a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-center" href="#">Ver todas</a></li>
            </ul>
        </div>

        <!-- User Profile -->
        <div class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                <div class="avatar me-2">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; font-size: 0.8rem;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                </div>
                <span class="text-dark fw-medium">{{ Auth::user()->name }}</span>
                <i class="fas fa-chevron-down ms-2 text-muted" style="font-size: 0.7rem;"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><h6 class="dropdown-header">{{ Auth::user()->email }}</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user me-2"></i>
                        Mi Perfil
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-cog me-2"></i>
                        Configuración
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Cerrar Sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
