<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Dashboard extends Controller
{
    //
     public function index()
    {

        return view('dashboard.index')->extends('layouts.theme.app')->section('content');
    }
}
