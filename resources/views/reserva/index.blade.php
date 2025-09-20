@extends('layouts.theme.modern-app')

@section('page-title', 'Gestión de Reservas')

@section('content')
    @livewire('reservas.reservas-controller')
@endsection
