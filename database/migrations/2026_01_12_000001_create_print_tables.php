<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('print_queue')) {
            Schema::create('print_queue', function (Blueprint $table) {
                $table->id();
                $table->string('job_type', 50);
                $table->json('job_data');
                $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
                $table->text('error')->nullable();
                $table->timestamps();
                $table->index('status');
                $table->index('created_at');
            });
        }

        if (!Schema::hasTable('surat_izin')) {
            Schema::create('surat_izin', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_surat', 100)->unique();
                $table->string('kategori', 50);
                $table->json('santri_ids');
                $table->json('santri_names');
                $table->string('tujuan_guru')->nullable();
                $table->string('kelas', 50)->nullable();
                $table->date('tanggal')->nullable();
                $table->timestamps();
                $table->index('tanggal');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('print_queue');
        Schema::dropIfExists('surat_izin');
    }
};
