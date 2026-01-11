<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintIzinApiController extends Controller
{
    public function getSantri(Request $request)
    {
        $kategori = $request->get('kategori', 'sakit');

        $dateFrom = now()->subDays(7)->toDateString();

        $query = DB::table('catatan_aktivitas as ca')
            ->join('data_induk as di', 'ca.siswa_id', '=', 'di.id')
            ->whereNull('ca.deleted_at')
            ->where('ca.tanggal', '>=', $dateFrom)
            ->select(
                'ca.id as aktivitas_id',
                'ca.siswa_id',
                'di.nama_lengkap',
                'di.kelas',
                'ca.judul',
                'ca.keterangan',
                'ca.tanggal'
            );

        if ($kategori === 'sakit') {
            $query->where('ca.kategori', 'sakit');
        } else {
            $query->where('ca.kategori', 'izin_pulang');
        }

        $data = $query->orderBy('ca.tanggal', 'desc')->get();

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // Generate nomor surat
        $count = DB::table('surat_izin')->whereDate('created_at', today())->count() + 1;
        $month = now()->format('n');
        $romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $nomorSurat = str_pad($count, 3, '0', STR_PAD_LEFT) . '/SKA.001/PPMH/' . $romanMonths[$month - 1] . '/' . now()->year;

        DB::table('surat_izin')->insert([
            'nomor_surat' => $nomorSurat,
            'kategori' => $data['kategori'] ?? 'sakit',
            'santri_ids' => json_encode($data['santri_ids'] ?? []),
            'santri_names' => json_encode($data['santri_names'] ?? []),
            'tujuan_guru' => $data['tujuan_guru'] ?? '',
            'kelas' => $data['kelas'] ?? '',
            'tanggal' => $data['tanggal'] ?? now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'nomor_surat' => $nomorSurat]);
    }
}
