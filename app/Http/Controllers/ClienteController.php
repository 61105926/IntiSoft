<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    //
    public function index()
    {
        // Obtener todas las sucursales activas, por ejemplo
        $sucursales = Sucursal::where('activo', true)->get();

        // Retornar vista con sucursales
        return view('cliente.index', compact('sucursales'))->extends('layouts.theme.app')->section('content');
    }
}


