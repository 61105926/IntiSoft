<?php

namespace App\Http\Livewire\EntradaFolklorica;

use Livewire\Component;

class EntradaFolkloricaController extends Component
{
    public function render()
    {
        return view('livewire.entrada-folklorica.entrada-folklorica')->extends('layouts.theme.modern-app')->section('content');
    }
}
