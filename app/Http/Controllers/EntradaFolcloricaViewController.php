<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EntradaFolcloricaViewController extends Controller
{
    public function index()
    {
        return view('entrada-folclorica.index');
    }
    
    public function participantes($id)
    {
        return view('entrada-folclorica.participantes', compact('id'));
    }
    
    public function devoluciones($id)
    {
        return view('entrada-folclorica.devoluciones', compact('id'));
    }
}