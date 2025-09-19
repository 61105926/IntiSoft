@extends('layouts.theme.app')

@section('page-title', 'Dashboard')

@section('content')
    @livewire('dashboard.dashboard-controller-simple')
@endsection
