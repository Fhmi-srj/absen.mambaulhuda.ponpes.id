<?php

namespace App\Http\Controllers;

use App\Models\CatatanAktivitas;
use App\Models\DataInduk;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AktivitasController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;

        // For kesehatan role, default to 'sakit' category
        $defaultKategori = $role === 'kesehatan' ? 'sakit' : null;

        return Inertia::render('Aktivitas', [
            'pageTitle' => $role === 'kesehatan' ? 'Laporan Kesehatan' : 'Aktivitas Santri',
            'defaultKategori' => $defaultKategori,
        ]);
    }
}
