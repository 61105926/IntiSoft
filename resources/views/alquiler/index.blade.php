@extends('layouts.theme.app')

@section('content')
    @livewire('alquiler.alquiler-controller')
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
@endsection
