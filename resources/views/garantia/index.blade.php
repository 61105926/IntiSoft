@extends('layouts.theme.app')

@section('content')
    <div class="container py-5">
        {{-- Encabezado principal --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold mb-1 text-body-secondary">
                <i class="fas fa-users me-2 text-warning"></i>
                Gestión de Garantias
            </h1>
            <p class="mb-0 text-body-secondary">Administra Garantias.</p>
        </div>

        {{-- Contenido del componente Livewire --}}
        <div class="bg-body rounded-4 shadow-sm p-4">
            @livewire('garantia.garantia-controller')
        </div>
    </div>
@endsection
