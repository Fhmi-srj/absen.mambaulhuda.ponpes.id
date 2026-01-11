<?php

namespace App\Http\Controllers;

use App\Models\DataInduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CetakKartuController extends Controller
{
    public function index(Request $request)
    {
        $ids = $request->get('ids', '');

        if (empty($ids)) {
            return abort(400, 'Tidak ada siswa yang dipilih');
        }

        $idArray = explode(',', $ids);
        $siswaList = DataInduk::whereIn('id', $idArray)
            ->orderBy('nama_lengkap')
            ->get();

        if ($siswaList->isEmpty()) {
            return abort(404, 'Data siswa tidak ditemukan');
        }

        $settings = DB::table('settings')
            ->whereIn('key', ['school_name', 'app_name'])
            ->pluck('value', 'key')
            ->toArray();

        $schoolName = $settings['school_name'] ?? 'Pondok Pesantren Mambaul Huda';

        return view('cetak-kartu', compact('siswaList', 'schoolName'));
    }
}
