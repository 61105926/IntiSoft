@extends('layouts.theme.app')

@section('content')
    <div class="container py-5">
        {{-- Encabezado principal --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-1 text-body-secondary">
                    <i class="fas fa-boxes me-2 text-warning"></i>
                    Gestión de Productos e Inventario
                </h1>
                <p class="mb-0 text-body-secondary">Administra productos y su disponibilidad.</p>
            </div>
        </div>

        {{-- Contenido del componente Livewire --}}
        <div class="rounded-4 shadow-sm p-4 bg-light text-body bg-opacity-100 dark-mode:bg-dark dark-mode:text-white">
            @livewire('producto.producto-controller')
        </div>
    </div>
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
@endsection
