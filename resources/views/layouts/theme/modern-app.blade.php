<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" />
    <title>@yield('page-title', 'IntiSoft - Sistema Folklórico')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        :root {
            /* Colores principales del sistema */
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #64748b;

            /* Colores por módulo */
            --inventario-color: #3b82f6;
            --inventario-light: #dbeafe;
            --operaciones-color: #10b981;
            --operaciones-light: #d1fae5;
            --finanzas-color: #f59e0b;
            --finanzas-light: #fef3c7;
            --gestion-color: #8b5cf6;
            --gestion-light: #ede9fe;
            --sistema-color: #ef4444;
            --sistema-light: #fee2e2;

            /* Layout */
            --sidebar-width: 280px;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            background-color: #f8fafc;
            color: #334155;
        }

        /* Sidebar moderno */
        .modern-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modern-sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .modern-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .modern-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }

        /* Logo section */
        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo h3 {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 20px;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-logo i {
            font-size: 24px;
            color: #3b82f6;
        }

        /* Navigation */
        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-section {
            margin-bottom: 32px;
        }

        .nav-section-title {
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 20px 12px;
            margin-bottom: 8px;
        }

        .nav-link-modern {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
            margin: 2px 8px;
            border-radius: 8px;
            font-weight: 500;
        }

        .nav-link-modern:hover {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-primary);
            transform: translateX(4px);
        }

        .nav-link-modern.active {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
        }

        .nav-link-modern.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: #3b82f6;
            border-radius: 0 2px 2px 0;
        }

        .nav-link-modern i {
            width: 20px;
            margin-right: 12px;
            font-size: 16px;
        }

        /* Main content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f8fafc;
        }

        /* Header moderno */
        .modern-header {
            background: white;
            height: var(--header-height);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .header-title {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .user-menu:hover {
            background: #f1f5f9;
            color: #334155;
        }

        /* Content area */
        .content-area {
            padding: 32px;
            min-height: calc(100vh - var(--header-height));
        }

        /* Cards modernas */
        .modern-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .modern-card:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            transform: translateY(-1px);
        }

        /* Colores por módulo */
        .inventario-theme .nav-link-modern.active {
            background: var(--inventario-light);
            color: var(--inventario-color);
        }

        .inventario-theme .nav-link-modern.active::before {
            background: var(--inventario-color);
        }

        .operaciones-theme .nav-link-modern.active {
            background: var(--operaciones-light);
            color: var(--operaciones-color);
        }

        .operaciones-theme .nav-link-modern.active::before {
            background: var(--operaciones-color);
        }

        .finanzas-theme .nav-link-modern.active {
            background: var(--finanzas-light);
            color: var(--finanzas-color);
        }

        .finanzas-theme .nav-link-modern.active::before {
            background: var(--finanzas-color);
        }

        .gestion-theme .nav-link-modern.active {
            background: var(--gestion-light);
            color: var(--gestion-color);
        }

        .gestion-theme .nav-link-modern.active::before {
            background: var(--gestion-color);
        }

        .sistema-theme .nav-link-modern.active {
            background: var(--sistema-light);
            color: var(--sistema-color);
        }

        .sistema-theme .nav-link-modern.active::before {
            background: var(--sistema-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modern-sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-open .modern-sidebar {
                transform: translateX(0);
            }
        }

        /* Utilities */
        .text-gradient {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.ts'])
    @stack('styles')
</head>

<body>
    <!-- Sidebar moderna -->
    @include('layouts.theme.modern-sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header moderno -->
        <header class="modern-header">
            <h1 class="header-title">@yield('page-title', 'Dashboard')</h1>
            <div class="header-actions">
                <div class="user-menu">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::user()->name ?? 'Usuario' }}</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="content-area fade-in-up">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Livewire Scripts -->
    @livewireScripts

    @stack('scripts')
</body>
</html>