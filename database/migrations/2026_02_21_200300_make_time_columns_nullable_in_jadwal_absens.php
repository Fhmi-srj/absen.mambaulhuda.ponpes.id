<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_absens', function (Blueprint $table) {
            $table->time('start_time')->nullable()->change();
            $table->time('scheduled_time')->nullable()->change();
            $table->time('end_time')->nullable()->change();
            $table->integer('late_tolerance_minutes')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_absens', function (Blueprint $table) {
            $table->time('start_time')->nullable(false)->change();
            $table->time('scheduled_time')->nullable(false)->change();
            $table->time('end_time')->nullable(false)->change();
            $table->integer('late_tolerance_minutes')->nullable(false)->change();
        });
    }
};
