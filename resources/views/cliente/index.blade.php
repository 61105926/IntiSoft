@extends('layouts.theme.app')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="display-4 fw-bold text-dark">Clientes</h1>
                <p class="text-muted">Gestión de la base de datos de clientes.</p>
            </div>
            <button class="btn btn-warning text-dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                    <path d="M5 12h14"></path>
                    <path d="M12 5v14"></path>
                </svg>
                Nuevo Cliente
            </button>
        </div>
        @livewire('cliente.cliente-controller')
   

    </div>
@endsection
