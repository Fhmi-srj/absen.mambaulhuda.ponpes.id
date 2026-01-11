<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\DataInduk;
use App\Models\JadwalAbsen;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LiveAttendanceApiController extends Controller
{
    /**
     * Get live attendance data for a jadwal
     */
    public function index(Request $request)
    {
        try {
            $jadwalId = $request->get('jadwal_id');
            $today = Carbon::today()->format('Y-m-d');

            if (empty($jadwalId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal ID wajib diisi'
                ], 400);
            }

            // Get jadwal
            $jadwal = JadwalAbsen::find($jadwalId);
            if (!$jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            // Get all students
            $allSiswa = DataInduk::whereNull('deleted_at')
                ->orderBy('kelas', 'asc')
                ->orderBy('nama_lengkap', 'asc')
                ->get(['id', 'nama_lengkap', 'kelas', 'nisn']);

            // Get today's attendance for this jadwal
            $attendances = Attendance::where('jadwal_id', $jadwalId)
                ->where('attendance_date', $today)
                ->get()
                ->keyBy('user_id');

            $hadir = [];
            $terlambat = [];
            $belumHadir = [];

            foreach ($allSiswa as $siswa) {
                if (isset($attendances[$siswa->id])) {
                    $att = $attendances[$siswa->id];
                    $item = [
                        'id' => $siswa->id,
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'kelas' => $siswa->kelas,
                        'waktu_absen' => Carbon::parse($att->attendance_time)->format('H:i'),
                        'minutes_late' => $att->minutes_late,
                    ];

                    if ($att->status === 'terlambat') {
                        $item['waktu_absen'] .= " (+{$att->minutes_late}m)";
                        $terlambat[] = $item;
                    } else {
                        $hadir[] = $item;
                    }
                } else {
                    $belumHadir[] = [
                        'id' => $siswa->id,
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'kelas' => $siswa->kelas,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'jadwal' => [
                    'id' => $jadwal->id,
                    'name' => $jadwal->name,
                    'start_time' => substr($jadwal->start_time, 0, 5),
                ],
                'count' => [
                    'total' => $allSiswa->count(),
                    'hadir' => count($hadir),
                    'terlambat' => count($terlambat),
                    'belum_hadir' => count($belumHadir),
                ],
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'belum_hadir' => $belumHadir,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
