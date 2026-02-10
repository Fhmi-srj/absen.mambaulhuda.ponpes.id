<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\JadwalAbsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $filterDate = $request->get('date', date('Y-m-d'));
        $filterJadwal = $request->get('jadwal', '');

        $jadwalList = JadwalAbsen::whereNull('deleted_at')
            ->orderBy('start_time')
            ->get();

        $query = DB::table('attendances as a')
            ->join('data_induk as di', function ($j) {
                $j->on('a.user_id', '=', 'di.id')->whereNull('di.deleted_at');
            })
            ->leftJoin('jadwal_absens as j', 'a.jadwal_id', '=', 'j.id')
            ->whereNull('a.deleted_at')
            ->where('a.attendance_date', $filterDate)
            ->select('a.*', 'di.nama_lengkap', 'di.nisn as nomor_induk', 'di.kelas', 'j.name as jadwal_name', 'j.type as jadwal_type');

        if ($filterJadwal) {
            $query->where('a.jadwal_id', $filterJadwal);
        }

        $attendances = $query->orderBy('a.attendance_time', 'desc')->get();

        $totalHadir = $attendances->where('status', 'hadir')->count();
        $totalTerlambat = $attendances->where('status', 'terlambat')->count();
        $totalAbsen = $attendances->where('status', 'absen')->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'jadwalList' => $jadwalList,
                'attendances' => $attendances,
                'filterDate' => $filterDate,
                'filterJadwal' => $filterJadwal,
                'totalHadir' => $totalHadir,
                'totalTerlambat' => $totalTerlambat,
                'totalAbsen' => $totalAbsen,
            ]);
        }

        return view('spa');
    }
}
