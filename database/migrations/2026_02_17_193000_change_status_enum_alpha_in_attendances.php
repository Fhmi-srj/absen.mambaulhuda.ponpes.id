<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Replace 'absen' with 'alpha' in the status ENUM
        DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('hadir','terlambat','alpha','izin','sakit','pulang') DEFAULT 'alpha'");

        // Convert any existing 'absen' records to 'alpha'
        // (This is safe even if there are no 'absen' records)
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('hadir','terlambat','absen','izin','sakit','pulang') DEFAULT 'hadir'");
    }
};
