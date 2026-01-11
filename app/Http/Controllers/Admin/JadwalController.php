<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalAbsen;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwalList = JadwalAbsen::whereNull('deleted_at')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('admin.jadwal', compact('jadwalList'));
    }
}
