<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'IntiSoft' }}</title>
    
    <!-- Sistema de Diseño Shadcn/UI -->
    <link href="{{ asset('css/shadcn-system.css') }}" rel="stylesheet" />
    
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <!-- Scripts adicionales -->
    @stack('styles')
</head>
<body class="antialiased">
    <div class="min-h-screen bg-background">
        {{ $slot }}
    </div>
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Scripts adicionales -->
    @stack('scripts')
</body>
</html>