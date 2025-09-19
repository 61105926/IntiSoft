@extends('layouts.theme.app')

@section('page-title', 'Gestión de Clientes')

@section('content')
    @livewire('cliente.cliente-controller')
@endsection
