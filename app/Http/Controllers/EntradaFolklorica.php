<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EntradaFolklorica extends Controller
{
    //
     public function index()
    {

        return view('EntradaFolklorica.index')->extends('layouts.theme.app')->section('content');
    }
}
