<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataInduk;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RfidApiController extends Controller
{
    /**
     * Check if RFID is already registered
     */
    public function check(Request $request)
    {
        $rfid = $request->get('rfid', '');

        if (empty($rfid)) {
            return response()->json([
                'registered' => false,
                'message' => 'RFID kosong'
            ]);
        }

        $siswa = DataInduk::where('nomor_rfid', $rfid)
            ->whereNull('deleted_at')
            ->first();

        if ($siswa) {
            return response()->json([
                'registered' => true,
                'siswa_id' => $siswa->id,
                'siswa_name' => $siswa->nama_lengkap,
                'kelas' => $siswa->kelas,
            ]);
        }

        return response()->json([
            'registered' => false,
            'message' => 'Kartu belum terdaftar'
        ]);
    }

    /**
     * Register RFID to a student
     */
    public function register(Request $request)
    {
        try {
            $siswaId = $request->siswa_id;
            $rfid = $request->rfid;

            if (empty($siswaId) || empty($rfid)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa dan nomor RFID wajib diisi'
                ], 400);
            }

            // Check if RFID already used
            $existingRfid = DataInduk::where('nomor_rfid', $rfid)
                ->whereNull('deleted_at')
                ->first();

            if ($existingRfid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kartu ini sudah terdaftar untuk: ' . $existingRfid->nama_lengkap
                ]);
            }

            // Find siswa
            $siswa = DataInduk::find($siswaId);
            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan'
                ], 404);
            }

            // Check if siswa already has RFID
            if (!empty($siswa->nomor_rfid)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa sudah memiliki kartu RFID: ' . $siswa->nomor_rfid
                ]);
            }

            // Register RFID
            $siswa->nomor_rfid = $rfid;
            $siswa->save();

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name ?? 'System',
                'action' => 'daftar_rfid',
                'module' => 'rfid',
                'description' => "Mendaftarkan RFID {$rfid} ke {$siswa->nama_lengkap}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kartu RFID berhasil didaftarkan ke ' . $siswa->nama_lengkap
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unregister RFID from a student
     */
    public function unregister(Request $request)
    {
        try {
            $siswaId = $request->siswa_id;

            $siswa = DataInduk::find($siswaId);
            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan'
                ], 404);
            }

            $oldRfid = $siswa->nomor_rfid;
            $siswa->nomor_rfid = null;
            $siswa->save();

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name ?? 'System',
                'action' => 'hapus_rfid',
                'module' => 'rfid',
                'description' => "Menghapus RFID {$oldRfid} dari {$siswa->nama_lengkap}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kartu RFID berhasil dihapus dari ' . $siswa->nama_lengkap
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
