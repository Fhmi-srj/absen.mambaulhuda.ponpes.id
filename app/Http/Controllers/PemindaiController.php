<?php

namespace App\Http\Controllers;

use App\Models\JadwalAbsen;
use Illuminate\Http\Request;

class PemindaiController extends Controller
{
    public function index()
    {
        // Get active jadwal list
        $jadwalList = JadwalAbsen::whereNull('deleted_at')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('user.pemindai', compact('jadwalList'));
    }
}
