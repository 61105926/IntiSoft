<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index()
    {

        return view('reserva.index')->extends('layouts.theme.app')->section('content');
    }
}
