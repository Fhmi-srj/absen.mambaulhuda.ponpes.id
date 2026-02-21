<?php

namespace App\Http\Controllers;

use App\Models\JadwalAbsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KiosController extends Controller
{
    public function index()
    {
        $jadwalList = JadwalAbsen::whereNull('deleted_at')
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        $today = date('Y-m-d');

        $recentAttendances = DB::table('attendances as a')
            ->join('data_induk as di', 'a.user_id', '=', 'di.id')
            ->leftJoin('jadwal_absens as j', 'a.jadwal_id', '=', 'j.id')
            ->whereNull('a.deleted_at')
            ->where('a.attendance_date', $today)
            ->select(
                'a.*',
                'di.nisn as nomor_induk',
                'di.kelas',
                'di.nama_lengkap',
                'di.jenis_kelamin',
                'j.name as jadwal_name',
                'j.type as jadwal_type'
            )
            ->orderBy('a.created_at', 'desc')
            ->limit(20)
            ->get();

        $todayTotal = DB::table('attendances')
            ->whereNull('deleted_at')
            ->where('attendance_date', $today)
            ->count();

        $totalSiswa = DB::table('data_induk')
            ->whereNull('deleted_at')
            ->where('status', 'AKTIF')
            ->count();

        $kelasList = DB::table('data_induk')
            ->whereNull('deleted_at')
            ->where('status', 'AKTIF')
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        $kioskPassword = config('app.kiosk_password', '1234');

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'jadwalList' => $jadwalList,
                'recentAttendances' => $recentAttendances,
                'todayTotal' => $todayTotal,
                'totalSiswa' => $totalSiswa,
                'kioskPassword' => $kioskPassword,
                'kelasList' => $kelasList,
            ]);
        }

        return view('spa');
    }

    /**
     * Get roster of all students with attendance status for a given jadwal
     */
    public function roster(Request $request)
    {
        $jadwalId = $request->get('jadwal_id');
        $kelas = $request->get('kelas', '');
        $today = date('Y-m-d');

        $query = DB::table('data_induk')
            ->whereNull('deleted_at')
            ->where('status', 'AKTIF')
            ->select('id', 'nama_lengkap', 'kelas', 'nisn', 'jenis_kelamin');

        if ($kelas) {
            $query->where('kelas', $kelas);
        }

        $students = $query->orderBy('nama_lengkap')->get();

        // Get attendance for this jadwal
        $attendedIds = [];
        $attendanceMap = [];
        if ($jadwalId) {
            $jadwal = DB::table('jadwal_absens')->find($jadwalId);

            $attQuery = DB::table('attendances')
                ->whereNull('deleted_at')
                ->where('jadwal_id', $jadwalId);

            if (!$jadwal || !$jadwal->disable_daily_reset) {
                $attQuery->where('attendance_date', $today);
            }

            $attendances = $attQuery->get();

            foreach ($attendances as $att) {
                $attendedIds[] = $att->user_id;
                $attendanceMap[$att->user_id] = [
                    'status' => $att->status,
                    'time' => $att->attendance_time,
                ];
            }
        }

        // Merge attendance status into students
        $roster = $students->map(function ($s) use ($attendedIds, $attendanceMap) {
            $attended = in_array($s->id, $attendedIds);
            return [
                'id' => $s->id,
                'nama_lengkap' => $s->nama_lengkap,
                'kelas' => $s->kelas,
                'nisn' => $s->nisn,
                'jenis_kelamin' => $s->jenis_kelamin,
                'status' => $attended ? $attendanceMap[$s->id]['status'] : 'alpha',
                'attendance_time' => $attended ? $attendanceMap[$s->id]['time'] : null,
            ];
        });

        return response()->json([
            'roster' => $roster,
            'summary' => [
                'total' => $roster->count(),
                'hadir' => $roster->where('status', 'hadir')->count(),
                'terlambat' => $roster->where('status', 'terlambat')->count(),
                'alpha' => $roster->where('status', 'alpha')->count(),
            ],
        ]);
    }
}
