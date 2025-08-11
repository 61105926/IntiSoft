@extends('layouts.theme.app')

@section('content')
    <div class="container py-5">
        {{-- Encabezado principal --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-1 text-body-secondary">
                    <i class="fas fa-boxes me-2 text-warning"></i>
                    Reservas
                </h1>
                <p class="mb-0 text-body-secondary">Gestión de reservas de productos con montos en efectivo.</p>
            </div>
        </div>

        {{-- Contenido del componente Livewire --}}
        <div class="rounded-4 shadow-sm p-4 bg-light text-body bg-opacity-100 dark-mode:bg-dark dark-mode:text-white">
            @livewire('reservas.reservas-controller')
        </div>
    </div>
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
@endsection
