<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecordAlphaAttendance extends Command
{
    protected $signature = 'attendance:record-alpha';
    protected $description = 'Record alpha (absent) status for students who did not attend after jadwal closes';

    public function handle()
    {
        $today = date('Y-m-d');
        $now = date('H:i:s');

        // Get active jadwal whose end_time has passed today
        $jadwalList = DB::table('jadwal_absens')
            ->whereNull('deleted_at')
            ->where('is_active', true)
            ->where('end_time', '<=', $now)
            ->get();

        if ($jadwalList->isEmpty()) {
            $this->info('No jadwal has closed yet.');
            return 0;
        }

        $totalInserted = 0;

        foreach ($jadwalList as $jadwal) {
            // Get all active student IDs
            $allStudentIds = DB::table('data_induk')
                ->whereNull('deleted_at')
                ->where('status', 'AKTIF')
                ->pluck('id');

            // Get student IDs who already have attendance today for this jadwal
            $attendedIds = DB::table('attendances')
                ->where('jadwal_id', $jadwal->id)
                ->where('attendance_date', $today)
                ->pluck('user_id');

            // Students who didn't attend
            $absentIds = $allStudentIds->diff($attendedIds);

            if ($absentIds->isEmpty()) {
                $this->info("Jadwal '{$jadwal->name}': all students accounted for.");
                continue;
            }

            // Insert alpha records
            $records = $absentIds->map(function ($studentId) use ($jadwal, $today) {
                return [
                    'user_id' => $studentId,
                    'jadwal_id' => $jadwal->id,
                    'status' => 'alpha',
                    'attendance_date' => $today,
                    'attendance_time' => $jadwal->end_time,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            DB::table('attendances')->insert($records);

            $count = count($records);
            $totalInserted += $count;
            $this->info("Jadwal '{$jadwal->name}': {$count} alpha records inserted.");
        }

        $this->info("Done. Total alpha records: {$totalInserted}");
        return 0;
    }
}
