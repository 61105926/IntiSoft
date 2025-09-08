<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlquileresController extends Controller
{
    public function index()
    {
        return view('alquiler.index');
    }
}
