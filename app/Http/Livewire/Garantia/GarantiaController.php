<?php

namespace App\Http\Livewire\Garantia;

use Livewire\Component;

class GarantiaController extends Component
{
    public function render()
    {
        return view('livewire.garantia.garantia')->extends('layouts.theme.app')->section('content');
    }
}
