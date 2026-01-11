<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataInduk;
use Illuminate\Http\Request;

class SantriApiController extends Controller
{
    /**
     * Search santri by keyword
     */
    public function search(Request $request)
    {
        $keyword = $request->input('q', '');

        if (strlen($keyword) < 2) {
            return response()->json([]);
        }

        $santri = DataInduk::where('status', 'AKTIF')
            ->whereNull('deleted_at')
            ->where(function ($query) use ($keyword) {
                $query->where('nama_lengkap', 'like', "%{$keyword}%")
                    ->orWhere('nisn', 'like', "%{$keyword}%")
                    ->orWhere('nik', 'like', "%{$keyword}%");
            })
            ->select('id', 'nama_lengkap', 'kelas', 'nisn', 'alamat', 'no_wa_wali')
            ->limit(10)
            ->get();

        return response()->json($santri);
    }

    /**
     * Get santri detail by ID
     */
    public function show($id)
    {
        $santri = DataInduk::where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$santri) {
            return response()->json([
                'status' => 'error',
                'message' => 'Santri tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $santri->id,
                'nama_lengkap' => $santri->nama_lengkap,
                'kelas' => $santri->kelas,
                'nisn' => $santri->nisn,
                'nik' => $santri->nik,
                'alamat' => $santri->alamat,
                'no_wa_wali' => $santri->no_wa_wali,
                'status' => $santri->status,
            ]
        ]);
    }
}
