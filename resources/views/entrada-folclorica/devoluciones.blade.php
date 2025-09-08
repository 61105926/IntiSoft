@extends('layouts.theme.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header d-print-none mb-3">
                    <div class="container-xl">
                        <div class="row g-2 align-items-center">
                            <div class="col">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('entrada-folklorica') }}">Entradas Folclóricas</a></li>
                                        <li class="breadcrumb-item active">Gestión de Devoluciones</li>
                                    </ol>
                                </nav>
                                <h2 class="page-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M9 14l-4 -4l4 -4"/>
                                        <path d="M5 10h11a4 4 0 1 1 0 8h-1"/>
                                    </svg>
                                    Gestión de Devoluciones
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
                
                @livewire('entrada-folclorica.devolucion-controller', ['id' => $id])
            </div>
        </div>
    </div>
@endsection