<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalAbsen;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $jadwalList = JadwalAbsen::whereNull('deleted_at')
            ->orderBy('start_time', 'asc')
            ->get();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['jadwalList' => $jadwalList]);
        }

        return view('spa');
    }
}
