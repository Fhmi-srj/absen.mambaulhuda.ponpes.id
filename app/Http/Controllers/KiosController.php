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
            ->orderBy('start_time')
            ->get();

        $today = date('Y-m-d');

        $recentAttendances = DB::table('attendances as a')
            ->join('data_induk as di', 'a.user_id', '=', 'di.id')
            ->leftJoin('jadwal_absens as j', 'a.jadwal_id', '=', 'j.id')
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
            ->where('attendance_date', $today)
            ->count();

        $totalSiswa = DB::table('data_induk')
            ->whereNull('deleted_at')
            ->count();

        $kioskPassword = config('app.kiosk_password', '1234');

        return view('kios', compact('jadwalList', 'recentAttendances', 'todayTotal', 'totalSiswa', 'kioskPassword'));
    }
}
