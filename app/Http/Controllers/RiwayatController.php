<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Inertia\Inertia;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        // Get attendances for the selected month/year
        $attendances = DB::table('attendances as a')
            ->join('data_induk as di', 'a.user_id', '=', 'di.id')
            ->leftJoin('jadwal_absens as j', 'a.jadwal_id', '=', 'j.id')
            ->whereNull('a.deleted_at')
            ->whereMonth('a.attendance_date', $month)
            ->whereYear('a.attendance_date', $year)
            ->orderByDesc('a.attendance_date')
            ->orderByDesc('a.attendance_time')
            ->select('a.*', 'di.nama_lengkap', 'di.kelas', 'j.name as jadwal_name', 'j.type as jadwal_type')
            ->get();

        // Group by date
        $groupedAttendances = [];
        foreach ($attendances as $a) {
            $date = $a->attendance_date;
            if (!isset($groupedAttendances[$date])) {
                $groupedAttendances[$date] = [];
            }
            $groupedAttendances[$date][] = $a;
        }

        // Stats for the month
        $stats = DB::table('attendances')
            ->whereNull('deleted_at')
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = 'terlambat' THEN 1 ELSE 0 END) as terlambat,
                SUM(CASE WHEN status = 'pulang' THEN 1 ELSE 0 END) as pulang
            ")
            ->first();

        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $dayNames = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        return Inertia::render('Riwayat', [
            'groupedAttendances' => $groupedAttendances,
            'stats' => $stats,
            'months' => $months,
            'dayNames' => $dayNames,
            'month' => $month,
            'year' => $year,
        ]);
    }
}
