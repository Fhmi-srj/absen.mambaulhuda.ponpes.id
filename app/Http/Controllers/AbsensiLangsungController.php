<?php

namespace App\Http\Controllers;

use App\Models\JadwalAbsen;
use Illuminate\Http\Request;

class AbsensiLangsungController extends Controller
{
    public function index()
    {
        // Get jadwal list
        $jadwalList = JadwalAbsen::whereNull('deleted_at')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('user.absensi-langsung', compact('jadwalList'));
    }
}
