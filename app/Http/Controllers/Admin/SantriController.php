<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataInduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        // Sorting
        $sortCol = $request->get('sort', 'nama_lengkap');
        $sortDir = strtoupper($request->get('dir', 'ASC')) === 'DESC' ? 'DESC' : 'ASC';
        $allowedCols = ['nama_lengkap', 'jenis_kelamin', 'kelas', 'quran', 'kategori', 'nisn', 'nik', 'nomor_kk', 'tempat_lahir', 'tanggal_lahir', 'lembaga_sekolah', 'asal_sekolah', 'status_mukim', 'nama_ayah', 'nama_ibu', 'no_wa_wali', 'nomor_rfid', 'status', 'alamat', 'kecamatan', 'kabupaten'];
        if (!in_array($sortCol, $allowedCols))
            $sortCol = 'nama_lengkap';

        // Filters
        $search = $request->get('search', '');
        $filterStatus = $request->get('status', '');
        $filterKelas = $request->get('kelas', '');

        // Pagination
        $perPage = 20;

        $query = DataInduk::whereNull('deleted_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_wa_wali', 'like', "%{$search}%");
            });
        }

        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        if ($filterKelas) {
            $query->where('kelas', $filterKelas);
        }

        $total = $query->count();
        $santriList = $query->orderBy($sortCol, $sortDir)->paginate($perPage);

        // Get kelas list for filter
        $kelasList = DataInduk::whereNull('deleted_at')
            ->whereNotNull('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'santriList' => $santriList,
                'total' => $total,
                'kelasList' => $kelasList,
                'sortCol' => $sortCol,
                'sortDir' => $sortDir,
                'search' => $search,
                'filterStatus' => $filterStatus,
                'filterKelas' => $filterKelas,
            ]);
        }

        return view('spa');
    }
}
