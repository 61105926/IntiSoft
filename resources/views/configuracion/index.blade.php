@extends('layouts.theme.app')

@section('content')
    <div class="container py-5">
        <div class="mb-5">
            <h1 class="display-4 fw-bold text-dark">Configuración</h1>
            <p class="text-muted">Administra los ajustes generales del sistema.</p>
        </div>
        @livewire('empresa.empresa-controller')
        @livewire('sucursal.sucursal-controller')
        @livewire('usuario.usuario-controller')
        @livewire('sistema.sistema-controller')

    </div>
@endsection
