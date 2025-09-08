<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockSucursal extends Controller
{
    public function index()
    {
        return view('sucursal.index');
    }
}