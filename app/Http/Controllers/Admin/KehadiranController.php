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

    public function export(Request $request)
    {
        $filterDate = $request->get('date', date('Y-m-d'));
        $filterJadwal = $request->get('jadwal', '');

        $query = DB::table('attendances as a')
            ->join('data_induk as di', function ($j) {
                $j->on('a.user_id', '=', 'di.id')->whereNull('di.deleted_at');
            })
            ->leftJoin('jadwal_absens as j', 'a.jadwal_id', '=', 'j.id')
            ->whereNull('a.deleted_at')
            ->where('a.attendance_date', $filterDate)
            ->select('a.*', 'di.nama_lengkap', 'di.nisn as nomor_induk', 'di.kelas', 'j.name as jadwal_name');

        if ($filterJadwal) {
            $query->where('a.jadwal_id', $filterJadwal);
        }

        $attendances = $query->orderBy('a.attendance_time', 'asc')->get();

        $jadwalName = $filterJadwal
            ? (JadwalAbsen::find($filterJadwal)->name ?? 'Semua')
            : 'Semua';

        $filename = "Kehadiran_{$filterDate}_{$jadwalName}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($attendances, $filterDate, $jadwalName) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fwrite($file, "\xEF\xBB\xBF");

            // Title rows
            fputcsv($file, ["Laporan Kehadiran"]);
            fputcsv($file, ["Tanggal: {$filterDate}"]);
            fputcsv($file, ["Jadwal: {$jadwalName}"]);
            fputcsv($file, []);

            // Header
            fputcsv($file, ['No', 'Waktu', 'Nama Siswa', 'Kelas', 'Nomor Induk', 'Jadwal', 'Status', 'Terlambat (menit)', 'Catatan']);

            // Data
            foreach ($attendances as $i => $a) {
                fputcsv($file, [
                    $i + 1,
                    $a->attendance_time ? substr($a->attendance_time, 0, 5) : '-',
                    $a->nama_lengkap,
                    $a->kelas ?? '-',
                    $a->nomor_induk ?? '-',
                    $a->jadwal_name ?? '-',
                    ucfirst($a->status),
                    $a->minutes_late ?? 0,
                    $a->notes ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
