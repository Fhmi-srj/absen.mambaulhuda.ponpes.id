<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataInduk;
use App\Models\Attendance;
use App\Models\CatatanAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function stats()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        // Count santri
        $siswaCount = DataInduk::whereNull('deleted_at')->count();
        
        // Count present today
        $presentToday = Attendance::where('attendance_date', $today)
            ->where('status', 'hadir')
            ->whereNull('deleted_at')
            ->count();
        
        // Count aktivitas today
        $aktivitasToday = CatatanAktivitas::whereDate('tanggal', $today)
            ->whereNull('deleted_at')
            ->count();
        
        // Count users
        $userCount = User::whereNull('deleted_at')->count();
        
        // Late students today
        $lateSiswa = DB::table('attendances as a')
            ->join('data_induk as di', 'a.user_id', '=', 'di.id')
            ->where('a.attendance_date', $today)
            ->where('a.status', 'terlambat')
            ->whereNull('a.deleted_at')
            ->select('di.nama_lengkap', 'di.kelas', 'a.minutes_late')
            ->orderByDesc('a.minutes_late')
            ->limit(5)
            ->get();
        
        // Recent aktivitas
        $recentAktivitas = CatatanAktivitas::with('santri:id,nama_lengkap,kelas')
            ->whereNull('deleted_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();
        
        return response()->json([
            'siswaCount' => $siswaCount,
            'presentToday' => $presentToday,
            'aktivitasToday' => $aktivitasToday,
            'userCount' => $userCount,
            'lateSiswa' => $lateSiswa,
            'recentAktivitas' => $recentAktivitas,
        ]);
    }
}
