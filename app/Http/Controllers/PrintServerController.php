<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrintServerController extends Controller
{
    public function index()
    {
        return view('print-server');
    }
}
