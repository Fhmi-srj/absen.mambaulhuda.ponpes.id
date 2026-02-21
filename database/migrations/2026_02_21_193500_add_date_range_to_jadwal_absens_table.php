<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_absens', function (Blueprint $table) {
            $table->date('no_reset_start_date')->nullable()->after('disable_daily_reset');
            $table->date('no_reset_end_date')->nullable()->after('no_reset_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_absens', function (Blueprint $table) {
            $table->dropColumn(['no_reset_start_date', 'no_reset_end_date']);
        });
    }
};
