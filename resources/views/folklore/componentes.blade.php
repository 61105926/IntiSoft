@extends('layouts.theme.modern-app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-puzzle-piece me-2"></i>
                    Componentes Folklóricos
                </h1>
                <p class="page-subtitle">Gestión de piezas individuales de trajes folklóricos</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de Componentes</h3>
                    <div class="card-actions">
                        <button class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Nuevo Componente
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Módulo en Desarrollo:</strong> Este módulo para gestionar componentes individuales estará disponible próximamente.
                        <br><br>
                        <strong>Componentes actuales incluyen:</strong>
                        <ul class="mt-2 mb-0">
                            <li>Polleras (Cholita Paceña, Caporales, Tinku)</li>
                            <li>Blusas y Camisas</li>
                            <li>Mantillas y Chales</li>
                            <li>Sombreros y Bombines</li>
                            <li>Ponchos y Chalecos</li>
                            <li>Calzado y Accesorios</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-female fa-3x text-primary mb-3"></i>
                                    <h5>Componentes Femeninos</h5>
                                    <p class="text-muted">Polleras, blusas, mantillas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-male fa-3x text-success mb-3"></i>
                                    <h5>Componentes Masculinos</h5>
                                    <p class="text-muted">Pantalones, camisas, ponchos</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-gem fa-3x text-warning mb-3"></i>
                                    <h5>Accesorios</h5>
                                    <p class="text-muted">Sombreros, calzado, adornos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection