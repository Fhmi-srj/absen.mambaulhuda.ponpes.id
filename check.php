<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TahunAjaran;
use App\Models\DataInduk;
use Illuminate\Support\Facades\DB;

// Tahun Ajaran status
$allTahun = TahunAjaran::all();
foreach ($allTahun as $t) {
    $count = DataInduk::withoutGlobalScope('activeTahunAjaran')->where('tahun_ajaran_id', $t->id)->whereNull('deleted_at')->count();
    echo "ID={$t->id} | {$t->nama} | " . ($t->is_active ? 'AKTIF' : '-') . " | {$count} santri\n";
}

// Check duplicates
$active = TahunAjaran::where('is_active', true)->first();
$dupes = DB::select("SELECT nama_lengkap, COUNT(*) as cnt FROM data_induk WHERE tahun_ajaran_id = ? AND deleted_at IS NULL GROUP BY nama_lengkap HAVING cnt > 1", [$active->id]);
echo "\nDuplikasi di {$active->nama}: " . count($dupes) . "\n";

// Sample data
echo "\nSample 10 santri di {$active->nama}:\n";
$sample = DataInduk::withoutGlobalScope('activeTahunAjaran')->where('tahun_ajaran_id', $active->id)->whereNull('deleted_at')->orderBy('nama_lengkap')->take(10)->get();
foreach ($sample as $s) {
    echo "  {$s->nama_lengkap} | Kelas: {$s->kelas} | {$s->lembaga_sekolah}\n";
}
