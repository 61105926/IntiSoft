<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistorialProductoController extends Controller
{
    public function index()
    {
        return view('historial-producto.index');
    }
}