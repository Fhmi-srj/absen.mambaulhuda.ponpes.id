<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\DataInduk;
use App\Models\JadwalAbsen;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceApiController extends Controller
{
    /**
     * Process attendance scan
     */
    public function store(Request $request)
    {
        try {
            $nomorInduk = $request->nomor_induk;
            $jadwalId = $request->jadwal_id;
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            // Validate required fields
            if (empty($nomorInduk) || empty($jadwalId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor induk dan jadwal wajib diisi'
                ], 400);
            }

            // Find siswa by nomor_induk
            $siswa = DataInduk::where('nomor_induk', $nomorInduk)
                ->orWhere('nisn', $nomorInduk)
                ->first();

            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan'
                ], 404);
            }

            // Find jadwal
            $jadwal = JadwalAbsen::find($jadwalId);
            if (!$jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            $today = Carbon::today()->format('Y-m-d');
            $now = Carbon::now();

            // Check if already absen for this jadwal
            $attendanceQuery = Attendance::where('user_id', $siswa->id)
                ->where('jadwal_id', $jadwalId);

            if (!$jadwal->disable_daily_reset) {
                $attendanceQuery->where('attendance_date', $today);
            }

            $attendance = $attendanceQuery->first();

            if ($attendance && !in_array($attendance->status, ['alpha', 'absen'])) {
                return response()->json([
                    'success' => false,
                    'siswa_name' => $siswa->nama_lengkap,
                    'message' => 'Sudah absen hari ini pada ' . Carbon::parse($attendance->attendance_time)->format('H:i')
                ]);
            }

            // Calculate late status
            $startTime = Carbon::createFromFormat('H:i:s', $jadwal->start_time);
            $tolerance = $jadwal->tolerance_minutes ?? 0;
            $lateThreshold = $startTime->copy()->addMinutes($tolerance);

            $status = 'hadir';
            $minutesLate = 0;

            if ($now->gt($lateThreshold)) {
                $status = 'terlambat';
                $minutesLate = $now->diffInMinutes($startTime);
            }

            // Create or Update attendance record
            if ($attendance) {
                $attendance->update([
                    'attendance_time' => $now->format('H:i:s'),
                    'status' => $status,
                    'minutes_late' => $minutesLate,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ]);
            } else {
                $attendance = Attendance::create([
                    'user_id' => $siswa->id,
                    'jadwal_id' => $jadwalId,
                    'attendance_date' => $today,
                    'attendance_time' => $now->format('H:i:s'),
                    'status' => $status,
                    'minutes_late' => $minutesLate,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ]);
            }

            // Log activity
            if (auth()->check()) {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name ?? 'System',
                    'action' => 'absensi',
                    'module' => 'pemindai',
                    'description' => "Absensi {$siswa->nama_lengkap} - {$jadwal->name} - {$status}",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            $message = $status === 'hadir'
                ? 'Berhasil absen tepat waktu'
                : "Terlambat {$minutesLate} menit";

            return response()->json([
                'success' => true,
                'siswa_name' => $siswa->nama_lengkap,
                'status' => $status,
                'minutes_late' => $minutesLate,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get today's attendance for a jadwal
     */
    public function today(Request $request)
    {
        $jadwalId = $request->jadwal_id;
        $today = Carbon::today()->format('Y-m-d');

        $attendances = Attendance::with('santri')
            ->where('attendance_date', $today)
            ->when($jadwalId, function ($q) use ($jadwalId) {
                $q->where('jadwal_id', $jadwalId);
            })
            ->orderByDesc('attendance_time')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $attendances->map(function ($a) {
                return [
                    'id' => $a->id,
                    'nama_lengkap' => $a->santri->nama_lengkap ?? '-',
                    'kelas' => $a->santri->kelas ?? '-',
                    'time' => Carbon::parse($a->attendance_time)->format('H:i'),
                    'status' => $a->status,
                    'minutes_late' => $a->minutes_late,
                ];
            })
        ]);
    }

    /**
     * Process RFID attendance for kiosk
     */
    public function rfid(Request $request)
    {
        $request->validate([
            'rfid' => 'required|string',
            'jadwal_id' => 'required|integer',
        ]);

        $rfid = $request->rfid;
        $jadwalId = $request->jadwal_id;
        $today = date('Y-m-d');
        $now = date('H:i:s');

        // Find santri by RFID
        $santri = DB::table('data_induk')
            ->where('nomor_rfid', $rfid)
            ->whereNull('deleted_at')
            ->first();

        if (!$santri) {
            return response()->json(['success' => false, 'message' => 'Kartu RFID tidak terdaftar']);
        }

        // Check if already attended for this jadwal
        $attendanceQuery = DB::table('attendances')
            ->where('user_id', $santri->id)
            ->where('jadwal_id', $jadwalId);

        if (!$jadwal || !$jadwal->disable_daily_reset) {
            $attendanceQuery->where('attendance_date', $today);
        }

        $attendance = $attendanceQuery->first();

        if ($attendance && !in_array($attendance->status, ['alpha', 'absen'])) {
            return response()->json(['success' => false, 'message' => 'Sudah absen untuk jadwal ini hari ini', 'santri' => $santri]);
        }

        // Get jadwal to determine if late
        $jadwal = DB::table('jadwal_absens')->find($jadwalId);
        $status = 'hadir';

        if ($jadwal && $jadwal->late_time) {
            if (strtotime($now) > strtotime($jadwal->late_time)) {
                $status = 'terlambat';
            }
        }

        if ($attendance) {
            DB::table('attendances')
                ->where('id', $attendance->id)
                ->update([
                    'status' => $status,
                    'attendance_time' => $now,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('attendances')->insert([
                'user_id' => $santri->id,
                'jadwal_id' => $jadwalId,
                'status' => $status,
                'attendance_date' => $today,
                'attendance_time' => $now,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil',
            'santri' => $santri,
            'status' => $status
        ]);
    }

    /**
     * Process manual attendance for kiosk (by siswa_id)
     */
    public function manualKiosk(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|integer',
            'jadwal_id' => 'required|integer',
        ]);

        $siswaId = $request->siswa_id;
        $jadwalId = $request->jadwal_id;
        $today = date('Y-m-d');
        $now = date('H:i:s');

        // Find santri
        $santri = DB::table('data_induk')
            ->where('id', $siswaId)
            ->whereNull('deleted_at')
            ->first();

        if (!$santri) {
            return response()->json(['success' => false, 'message' => 'Santri tidak ditemukan']);
        }

        // Check if already attended for this jadwal
        $attendanceQuery = DB::table('attendances')
            ->where('user_id', $santri->id)
            ->where('jadwal_id', $jadwalId);

        if (!$jadwal || !$jadwal->disable_daily_reset) {
            $attendanceQuery->where('attendance_date', $today);
        }

        $attendance = $attendanceQuery->first();

        if ($attendance && !in_array($attendance->status, ['alpha', 'absen'])) {
            return response()->json([
                'success' => false,
                'message' => 'Sudah absen untuk jadwal ini hari ini',
                'santri' => $santri
            ]);
        }

        // Get jadwal to determine if late
        $jadwal = DB::table('jadwal_absens')->find($jadwalId);
        $status = 'hadir';

        if ($jadwal) {
            $lateTime = null;
            if (!empty($jadwal->late_time)) {
                $lateTime = $jadwal->late_time;
            } elseif (!empty($jadwal->scheduled_time) && isset($jadwal->late_tolerance_minutes)) {
                $lateTime = date('H:i:s', strtotime($jadwal->scheduled_time) + ($jadwal->late_tolerance_minutes * 60));
            }

            if ($lateTime && strtotime($now) > strtotime($lateTime)) {
                $status = 'terlambat';
            }
        }

        if ($attendance) {
            DB::table('attendances')
                ->where('id', $attendance->id)
                ->update([
                    'status' => $status,
                    'attendance_time' => $now,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('attendances')->insert([
                'user_id' => $santri->id,
                'jadwal_id' => $jadwalId,
                'status' => $status,
                'attendance_date' => $today,
                'attendance_time' => $now,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil',
            'santri' => $santri,
            'status' => $status
        ]);
    }
}
