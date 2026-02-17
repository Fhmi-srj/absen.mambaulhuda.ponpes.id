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

        // Get active jadwal whose start_time has passed today and end_time hasn't passed OR end_time has passed but still today
        // Basically any schedule that has started today
        $jadwalList = DB::table('jadwal_absens')
            ->whereNull('deleted_at')
            ->where('is_active', true)
            ->where('start_time', '<=', $now)
            ->get();

        if ($jadwalList->isEmpty()) {
            $this->info('No active jadwal has started yet today.');
            return 0;
        }

        $totalInserted = 0;

        foreach ($jadwalList as $jadwal) {
            // Get all active student IDs, ensure unique and cast to int
            $allStudentIds = DB::table('data_induk')
                ->whereNull('deleted_at')
                ->where('status', 'AKTIF')
                ->pluck('id')
                ->map(fn($id) => (int)$id)
                ->unique();

            // Get student IDs who already have any attendance record today for this jadwal
            $attendedIds = DB::table('attendances')
                ->where('jadwal_id', $jadwal->id)
                ->where('attendance_date', $today)
                ->pluck('user_id')
                ->map(fn($id) => (int)$id)
                ->unique();

            // Students who don't have a record yet (will be marked alpha)
            $absentIds = $allStudentIds->diff($attendedIds)->unique();

            if ($absentIds->isEmpty()) {
                continue;
            }

            // Insert alpha records
            $records = [];
            foreach ($absentIds as $studentId) {
                $records[] = [
                    'user_id' => $studentId,
                    'jadwal_id' => $jadwal->id,
                    'status' => 'alpha',
                    'attendance_date' => $today,
                    'attendance_time' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert in chunks with insertOrIgnore for maximum safety
            foreach (array_chunk($records, 100) as $chunk) {
                DB::table('attendances')->insertOrIgnore($chunk);
            }

            $count = count($records);
            $totalInserted += $count;
            $this->info("Jadwal '{$jadwal->name}': processing {$count} students.");
        }

        $this->info("Done. Total alpha records added: {$totalInserted}");
        return 0;
    }
}
