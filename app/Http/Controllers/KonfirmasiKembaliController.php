<?php

namespace App\Http\Controllers;

use App\Models\CatatanAktivitas;
use Illuminate\Http\Request;

class KonfirmasiKembaliController extends Controller
{
    /**
     * Display the public confirmation page
     */
    public function index()
    {
        return view('konfirmasi-kembali');
    }

    /**
     * Get list of santri with active izin (belum kembali)
     */
    public function getSantriAktif()
    {
        $data = CatatanAktivitas::with('santri')
            ->whereIn('kategori', ['izin_keluar', 'izin_pulang'])
            ->whereNull('tanggal_selesai')
            ->whereNull('deleted_at')
            ->whereNotNull('kode_konfirmasi')
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_lengkap' => $item->santri->nama_lengkap ?? '-',
                    'kelas' => $item->santri->kelas ?? '-',
                    'kategori' => $item->kategori,
                    'kategori_label' => $item->kategori === 'izin_keluar' ? 'Izin Keluar' : 'Izin Pulang',
                    'judul' => $item->judul,
                    'tanggal' => $item->tanggal?->format('d/m/Y H:i'),
                    'batas_waktu' => $item->batas_waktu?->format('d/m/Y H:i'),
                    'batas_waktu_raw' => $item->batas_waktu?->toIso8601String(),
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Get detail of specific izin
     */
    public function getDetail($id)
    {
        $item = CatatanAktivitas::with('santri')->find($id);

        if (!$item || !in_array($item->kategori, ['izin_keluar', 'izin_pulang'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $item->id,
                'nama_lengkap' => $item->santri->nama_lengkap ?? '-',
                'kelas' => $item->santri->kelas ?? '-',
                'kategori' => $item->kategori,
                'kategori_label' => $item->kategori === 'izin_keluar' ? 'Izin Keluar' : 'Izin Pulang',
                'judul' => $item->judul,
                'keterangan' => $item->keterangan,
                'tanggal' => $item->tanggal?->format('d/m/Y H:i'),
                'batas_waktu' => $item->batas_waktu?->format('d/m/Y H:i'),
                'batas_waktu_raw' => $item->batas_waktu?->toIso8601String(),
                'sudah_kembali' => !is_null($item->tanggal_selesai),
            ]
        ]);
    }

    /**
     * Validate kode and confirm return
     */
    public function konfirmasi(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'kode' => 'required|string|min:6|max:10',
        ]);

        $item = CatatanAktivitas::find($request->id);

        if (!$item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data izin tidak ditemukan'
            ], 404);
        }

        if (!in_array($item->kategori, ['izin_keluar', 'izin_pulang'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data bukan izin keluar/pulang'
            ], 400);
        }

        if ($item->tanggal_selesai) {
            return response()->json([
                'status' => 'error',
                'message' => 'Santri sudah dikonfirmasi kembali sebelumnya'
            ], 400);
        }

        // Validate kode konfirmasi (case insensitive)
        if (strtoupper($request->kode) !== strtoupper($item->kode_konfirmasi)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode konfirmasi salah'
            ], 400);
        }

        // Update tanggal_selesai
        $item->update([
            'tanggal_selesai' => now(),
        ]);

        // Check if late
        $terlambat = false;
        if ($item->batas_waktu && now()->gt($item->batas_waktu)) {
            $terlambat = true;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Konfirmasi berhasil! Waktu kembali tercatat.',
            'data' => [
                'nama' => $item->santri->nama_lengkap ?? '-',
                'waktu_kembali' => now()->format('d/m/Y H:i'),
                'terlambat' => $terlambat,
            ]
        ]);
    }
}
