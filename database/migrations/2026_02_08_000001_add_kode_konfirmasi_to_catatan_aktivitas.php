<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('catatan_aktivitas', function (Blueprint $table) {
            $table->string('kode_konfirmasi', 10)->nullable()->after('tanggal_selesai');
            $table->index('kode_konfirmasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catatan_aktivitas', function (Blueprint $table) {
            $table->dropIndex(['kode_konfirmasi']);
            $table->dropColumn('kode_konfirmasi');
        });
    }
};
