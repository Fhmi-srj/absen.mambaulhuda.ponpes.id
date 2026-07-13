<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TahunAjaran;
use App\Models\DataInduk;
use Illuminate\Support\Facades\DB;

echo "=== CLEANUP SCRIPT ===\n\n";

// 1. Show all tahun ajaran
echo "--- Tahun Ajaran ---\n";
$allTahun = TahunAjaran::all();
foreach ($allTahun as $t) {
    $count = DataInduk::withoutGlobalScope('activeTahunAjaran')->where('tahun_ajaran_id', $t->id)->whereNull('deleted_at')->count();
    echo "ID {$t->id} | {$t->nama} | Active: " . ($t->is_active ? 'YES' : 'NO') . " | Students: {$count}\n";
}

// 2. Fix: Activate 2026/2027
$target = TahunAjaran::where('nama', '2026/2027')->first();
if ($target) {
    TahunAjaran::query()->update(['is_active' => false]);
    $target->is_active = true;
    $target->save();
    echo "\nActivated: {$target->nama} (ID {$target->id})\n";
} else {
    echo "\nERROR: 2026/2027 not found!\n";
    exit;
}

// 3. Delete orphan tahun entries
$orphans = TahunAjaran::where('nama', '2026/2027')->where('id', '!=', $target->id)->get();
foreach ($orphans as $o) {
    $cnt = DataInduk::withoutGlobalScope('activeTahunAjaran')->where('tahun_ajaran_id', $o->id)->count();
    echo "Removing orphan tahun ID {$o->id} with {$cnt} records\n";
    DataInduk::withoutGlobalScope('activeTahunAjaran')->where('tahun_ajaran_id', $o->id)->forceDelete();
    $o->delete();
}

// 4. Check duplicates in active tahun
echo "\n--- Checking duplicates in {$target->nama} ---\n";
$dupes = DB::select("
    SELECT nama_lengkap, COUNT(*) as cnt 
    FROM data_induk 
    WHERE tahun_ajaran_id = ? AND deleted_at IS NULL
    GROUP BY nama_lengkap HAVING cnt > 1 
    ORDER BY nama_lengkap
", [$target->id]);

echo count($dupes) . " names duplicated\n";
foreach (array_slice($dupes, 0, 5) as $d) {
    echo "  {$d->nama_lengkap} ({$d->cnt}x)\n";
}

// 5. Remove dupes - keep promoted version (higher kelas)
if (count($dupes) > 0) {
    echo "\nRemoving duplicates...\n";
    $removed = 0;
    foreach ($dupes as $d) {
        $records = DataInduk::withoutGlobalScope('activeTahunAjaran')
            ->where('tahun_ajaran_id', $target->id)
            ->where('nama_lengkap', $d->nama_lengkap)
            ->whereNull('deleted_at')
            ->orderByRaw("LENGTH(kelas) DESC, kelas DESC")
            ->get();
        
        $first = true;
        foreach ($records as $rec) {
            if ($first) { $first = false; continue; }
            $rec->forceDelete();
            $removed++;
        }
    }
    echo "Removed {$removed} duplicates\n";
}

// 6. Final kelas distribution
echo "\n--- Kelas distribution ({$target->nama}) ---\n";
$dist = DB::select("
    SELECT kelas, lembaga_sekolah, COUNT(*) as cnt FROM data_induk 
    WHERE tahun_ajaran_id = ? AND deleted_at IS NULL
    GROUP BY kelas, lembaga_sekolah ORDER BY lembaga_sekolah, kelas
", [$target->id]);
foreach ($dist as $k) {
    echo "  " . ($k->lembaga_sekolah ?: '-') . " | Kelas " . ($k->kelas ?: 'NULL') . ": {$k->cnt}\n";
}

$final = DataInduk::withoutGlobalScope('activeTahunAjaran')->where('tahun_ajaran_id', $target->id)->whereNull('deleted_at')->count();
echo "\nTotal: {$final} santri\n";

\Illuminate\Support\Facades\Cache::forget('active_tahun_ajaran_id');
echo "Done!\n";
