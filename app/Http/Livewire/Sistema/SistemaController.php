<?php

namespace App\Http\Livewire\Sistema;

use Livewire\Component;

class SistemaController extends Component
{
    public function render()
    {
        return view('livewire.sistema.sistema')->extends('layouts.theme.modern-app')->section('content');
    }
}
