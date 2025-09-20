@extends('layouts.theme.modern-app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @livewire('entrada-folclorica.entrada-folclorica-controller')
            </div>
        </div>
    </div>
@endsection