<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    //
    public function index()
    {
        return view('configuracion.index')->extends('layouts.theme.app')->section('content');
    }

    
}
