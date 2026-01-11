<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintQueueApiController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();

        // Generate nomor surat if needed
        $nomorSurat = null;
        if ($data['job_type'] === 'surat_izin') {
            $count = DB::table('surat_izin')->whereDate('created_at', today())->count() + 1;
            $month = now()->format('n');
            $romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $nomorSurat = str_pad($count, 3, '0', STR_PAD_LEFT) . '/SKA.001/PPMH/' . $romanMonths[$month - 1] . '/' . now()->year;

            DB::table('surat_izin')->insert([
                'nomor_surat' => $nomorSurat,
                'kategori' => $data['kategori'] ?? 'sakit',
                'santri_ids' => json_encode($data['santri_ids'] ?? []),
                'santri_names' => json_encode($data['santri_names'] ?? []),
                'tujuan_guru' => $data['tujuan_guru'] ?? '',
                'kelas' => $data['kelas'] ?? '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $jobData = $data;
        $jobData['nomor_surat'] = $nomorSurat;

        $id = DB::table('print_queue')->insertGetId([
            'job_type' => $data['job_type'],
            'job_data' => json_encode($jobData),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'job_id' => $id, 'nomor_surat' => $nomorSurat]);
    }

    public function stats()
    {
        $stats = [
            'pending' => DB::table('print_queue')->where('status', 'pending')->count(),
            'processing' => DB::table('print_queue')->where('status', 'processing')->count(),
            'completed_today' => DB::table('print_queue')->where('status', 'completed')->whereDate('updated_at', today())->count(),
            'failed_today' => DB::table('print_queue')->where('status', 'failed')->whereDate('updated_at', today())->count(),
        ];
        return response()->json(['success' => true, 'stats' => $stats]);
    }

    public function pending()
    {
        $jobs = DB::table('print_queue')
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->limit(5)
            ->get()
            ->map(function ($job) {
                $job->job_data = json_decode($job->job_data);
                return $job;
            });
        return response()->json(['success' => true, 'jobs' => $jobs]);
    }

    public function processing($id)
    {
        DB::table('print_queue')->where('id', $id)->update(['status' => 'processing', 'updated_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function complete($id)
    {
        DB::table('print_queue')->where('id', $id)->update(['status' => 'completed', 'updated_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function fail(Request $request, $id)
    {
        DB::table('print_queue')->where('id', $id)->update([
            'status' => 'failed',
            'error' => $request->get('error'),
            'updated_at' => now()
        ]);
        return response()->json(['success' => true]);
    }
}
