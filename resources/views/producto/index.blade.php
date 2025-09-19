@extends('layouts.theme.app')

@section('page-title', 'Inventario de Productos')

@section('content')
    @livewire('producto.producto-controller')
@endsection
