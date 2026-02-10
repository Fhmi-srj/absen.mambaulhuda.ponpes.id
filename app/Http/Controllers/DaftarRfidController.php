<?php

namespace App\Http\Controllers;

use App\Models\DataInduk;
use Illuminate\Http\Request;

class DaftarRfidController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));
        $perPage = 10;

        $query = DataInduk::whereNull('deleted_at')
            ->orderBy('kelas', 'asc')
            ->orderBy('nama_lengkap', 'asc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $siswaList = $query->paginate($perPage)->appends(['search' => $search]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['siswaList' => $siswaList, 'search' => $search]);
        }

        return view('spa');
    }
}
