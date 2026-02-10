<?php

namespace App\Http\Controllers;

use App\Models\CatatanAktivitas;
use App\Models\DataInduk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Inertia\Inertia;

class BerandaController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');

        // Statistics
        $siswaCount = DataInduk::whereNull('deleted_at')->count();
        $userCount = User::whereNull('deleted_at')->count();

        // Aktivitas hari ini
        $aktivitasToday = CatatanAktivitas::whereDate('tanggal', $today)
            ->whereNull('deleted_at')
            ->count();

        // Attendance hari ini - check if attendances table exists
        $presentToday = 0;
        $lateSiswa = [];

        try {
            if (DB::getSchemaBuilder()->hasTable('attendances')) {
                $presentToday = DB::table('attendances')
                    ->where('attendance_date', $today)
                    ->distinct('user_id')
                    ->count();

                // Siswa terlambat hari ini
                $lateSiswa = DB::table('attendances as a')
                    ->join('data_induk as di', 'a.user_id', '=', 'di.id')
                    ->where('a.attendance_date', $today)
                    ->where('a.status', 'terlambat')
                    ->orderByDesc('a.attendance_time')
                    ->select('a.*', 'di.nama_lengkap', 'di.kelas')
                    ->get();
            }
        } catch (\Exception $e) {
            // Table doesn't exist, use defaults
        }

        $absentToday = max(0, $siswaCount - $presentToday);

        // Chart data - 7 hari terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');

            $hadir = 0;
            $terlambat = 0;

            try {
                if (DB::getSchemaBuilder()->hasTable('attendances')) {
                    $stats = DB::table('attendances')
                        ->where('attendance_date', $date)
                        ->selectRaw("
                            COUNT(CASE WHEN status != 'terlambat' THEN 1 END) as hadir,
                            COUNT(CASE WHEN status = 'terlambat' THEN 1 END) as terlambat
                        ")
                        ->first();

                    $hadir = $stats->hadir ?? 0;
                    $terlambat = $stats->terlambat ?? 0;
                }
            } catch (\Exception $e) {
                // Use defaults
            }

            $chartData[] = [
                'date' => Carbon::parse($date)->format('d M'),
                'hadir' => (int) $hadir,
                'terlambat' => (int) $terlambat
            ];
        }

        // Aktivitas terbaru with formatted dates
        $recentAktivitas = CatatanAktivitas::with('santri')
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($akt) {
                return [
                    'id' => $akt->id,
                    'kategori' => $akt->kategori,
                    'judul' => $akt->judul,
                    'tanggal_formatted' => $akt->tanggal ? $akt->tanggal->format('d/m H:i') : '-',
                    'santri' => $akt->santri ? [
                        'nama_lengkap' => $akt->santri->nama_lengkap,
                        'kelas' => $akt->santri->kelas,
                    ] : null,
                ];
            });

        return Inertia::render('Beranda', [
            'siswaCount' => $siswaCount,
            'userCount' => $userCount,
            'aktivitasToday' => $aktivitasToday,
            'presentToday' => $presentToday,
            'absentToday' => $absentToday,
            'chartData' => $chartData,
            'lateSiswa' => $lateSiswa,
            'recentAktivitas' => $recentAktivitas,
        ]);
    }
}
