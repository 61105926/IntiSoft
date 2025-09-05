<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CajaController extends Controller
{
    //
       public function index()
    {

        return view('caja.index')->extends('layouts.theme.app')->section('content');
    }
}
