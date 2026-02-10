<?php

namespace App\Http\Controllers;

use App\Models\DataInduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KartuQrController extends Controller
{
    public function show($id)
    {
        $siswa = DataInduk::findOrFail($id);

        $settings = DB::table('settings')
            ->whereIn('key', ['school_name', 'app_name'])
            ->pluck('value', 'key')
            ->toArray();

        $schoolName = $settings['school_name'] ?? 'Pondok Pesantren Mambaul Huda';
        $qrData = $siswa->nisn ?? $siswa->id;
        $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrData);

        if (request()->expectsJson()) {
            return response()->json([
                'siswa' => $siswa,
                'schoolName' => $schoolName,
                'qrApiUrl' => $qrApiUrl
            ]);
        }

        return view('spa');
    }
}
