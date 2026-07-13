<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TahunAjaran;
use App\Models\DataInduk;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

echo "Starting data import script...\n";

DB::beginTransaction();
try {
    // 1. Rename existing default to 2025/2026 or get it
    $currentActive = TahunAjaran::where('nama', '2025/2026')->first();
    if (!$currentActive) {
        $currentActive = TahunAjaran::first();
        if ($currentActive) {
            $currentActive->nama = '2025/2026';
            $currentActive->save();
        } else {
            $currentActive = TahunAjaran::create(['nama' => '2025/2026', 'is_active' => false]);
        }
        echo "Updated base year to 2025/2026 (ID {$currentActive->id})\n";
    } else {
        echo "Found base year 2025/2026 (ID {$currentActive->id})\n";
    }

    // 2. Create 2026/2027
    $newTahun = TahunAjaran::where('nama', '2026/2027')->first();
    if (!$newTahun) {
        $newTahun = TahunAjaran::create(['nama' => '2026/2027', 'is_active' => false]);
    }
    
    // Deactivate old, activate new
    TahunAjaran::query()->update(['is_active' => false]);
    $newTahun->is_active = true;
    $newTahun->save();
    \Illuminate\Support\Facades\Cache::forget('active_tahun_ajaran_id');
    echo "Activated new year 2026/2027 (ID {$newTahun->id})\n";

    // 3. Promote existing students from 2025/2026 to 2026/2027
    $dataIndukLama = DataInduk::withoutGlobalScope('activeTahunAjaran')
                    ->where('tahun_ajaran_id', $currentActive->id)
                    ->get();

    // Map function for roman numerals and numbers
    function getNextClass($c) {
        $map = [
            'I' => 'II', 'II' => 'III', 'III' => 'LULUS', 'IV' => 'V', 'V' => 'VI', 'VI' => 'LULUS', 'VII' => 'LULUS',
            '1' => '2', '2' => '3', '3' => 'LULUS', '4' => '5', '5' => '6', '6' => 'LULUS'
        ];
        
        // Coba split by space untuk kelas seperti 'I A'
        $parts = explode(' ', $c, 2);
        if (isset($parts[0]) && isset($map[$parts[0]])) {
            return $map[$parts[0]] . (isset($parts[1]) ? ' ' . $parts[1] : '');
        }
        
        // Coba split by slash untuk kelas seperti '1/4'
        $partsSlash = explode('/', $c, 2);
        if (count($partsSlash) == 2 && isset($map[$partsSlash[0]]) && isset($map[$partsSlash[1]])) {
            return $map[$partsSlash[0]] . '/' . $map[$partsSlash[1]];
        }
        
        return $c; // Return unchanged if no pattern matches
    }

    $promoted = 0;
    // Hapus data lama yang sudah ada di new tahun agar idempotent (jika script di rerun)
    DataInduk::withoutGlobalScope('activeTahunAjaran')->where('tahun_ajaran_id', $newTahun->id)->delete();

    foreach ($dataIndukLama as $santri) {
        $newKelas = getNextClass($santri->kelas);
        
        if (strpos(strtoupper($newKelas), 'LULUS') !== false) {
            continue; // Lulus doesn't move
        }
        
        $newSantri = $santri->replicate();
        $newSantri->tahun_ajaran_id = $newTahun->id;
        $newSantri->kelas = $newKelas;
        $newSantri->save();
        $promoted++;
    }
    echo "Promoted $promoted students from 2025/2026 to 2026/2027.\n";

    // 4. Import from Excel
    $excelFile = 'c:\\laragon\\www\\absen.mambaulhuda.ponpes.id\\data-pendaftar-1783666748223.xlsx';
    if (!file_exists($excelFile)) {
        throw new \Exception("Excel file not found at " . $excelFile);
    }
    
    $spreadsheet = IOFactory::load($excelFile);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
    $headers = array_shift($rows); // Skip header

    // Mapping header index to our columns based on SantriImportController logic
    // Actually typically the headers match the labels or names. Let's just try to map them dynamically.
    // Or we assume standard order. Since user just provided the file, I will map the columns by finding their names in the header row.
    echo "Headers found: " . implode(", ", $headers) . "\n";
    
    // We need 'nama_lengkap', 'kelas', 'lembaga_sekolah' etc.
    // So let's map header strings to db columns
    $headerMap = [];
    foreach ($headers as $idx => $headerText) {
        if (!$headerText) continue;
        $ht = strtolower(trim($headerText));
        if ($ht === 'nama' || (str_contains($ht, 'nama') && !str_contains($ht, 'ayah') && !str_contains($ht, 'ibu'))) $headerMap['nama_lengkap'] = $idx;
        if (str_contains($ht, 'kelas')) $headerMap['kelas'] = $idx;
        if (str_contains($ht, 'lembaga')) $headerMap['lembaga_sekolah'] = $idx;
        if (str_contains($ht, 'nisn')) $headerMap['nisn'] = $idx;
        if ($ht === 'nik') $headerMap['nik'] = $idx;
        if ($ht === 'jk' || str_contains($ht, 'jenis kelamin')) $headerMap['jenis_kelamin'] = $idx;
        if ($ht === 'ttl') $headerMap['ttl'] = $idx;
        if (str_contains($ht, 'kota') || str_contains($ht, 'kab')) $headerMap['kabupaten'] = $idx;
        if (str_contains($ht, 'kecamatan')) $headerMap['kecamatan'] = $idx;
        if (str_contains($ht, 'kelurahan') || str_contains($ht, 'alamat')) $headerMap['alamat'] = $idx;
        if (str_contains($ht, 'asal sekolah')) $headerMap['asal_sekolah'] = $idx;
        if (str_contains($ht, 'status mukim')) $headerMap['status_mukim'] = $idx;
        if (str_contains($ht, 'pip') || str_contains($ht, 'pkh')) $headerMap['nomor_pip'] = $idx;
        if (str_contains($ht, 'nama ayah')) $headerMap['nama_ayah'] = $idx;
        if (str_contains($ht, 'pekerjaan ayah')) $headerMap['pekerjaan_ayah'] = $idx;
        if (str_contains($ht, 'nama ibu')) $headerMap['nama_ibu'] = $idx;
        if (str_contains($ht, 'pekerjaan ibu')) $headerMap['pekerjaan_ibu'] = $idx;
        if (str_contains($ht, 'hp') || str_contains($ht, 'wa')) $headerMap['no_wa_wali'] = $idx;
    }
    echo "Mapped columns: " . implode(', ', array_keys($headerMap)) . "\n";
    
    $imported = 0;
    foreach ($rows as $data) {
        if (empty(array_filter($data))) continue;
        
        $nama_lengkap = isset($headerMap['nama_lengkap']) ? trim($data[$headerMap['nama_lengkap']]) : '';
        $lembaga = isset($headerMap['lembaga_sekolah']) ? trim($data[$headerMap['lembaga_sekolah']]) : '';
        $jk = isset($headerMap['jenis_kelamin']) ? strtoupper(trim($data[$headerMap['jenis_kelamin']])) : '';
        
        if (!$nama_lengkap) continue;
        
        // Tentukan kelas berdasarkan lembaga
        // SMP NU = kelas 1, MA ALHIKAM = kelas 4
        $isMA = strpos(strtolower($lembaga), 'alhikam') !== false;
        
        if ($isMA) {
            // MA ALHIKAM kelas 4, tidak perlu suffix A/B
            $kelas = '4';
        } else {
            // SMP NU kelas 1, tambah suffix A (putra) / B (putri)
            $suffix = '';
            if ($jk === 'L') $suffix = 'A';
            elseif ($jk === 'P') $suffix = 'B';
            $kelas = '1' . $suffix;
        }

        // Parse TTL jika ada (format: "Tempat, TanggalLahir")
        $tempat_lahir = null;
        $tanggal_lahir = null;
        if (isset($headerMap['ttl']) && trim($data[$headerMap['ttl']]) !== '') {
            $ttl = trim($data[$headerMap['ttl']]);
            if (strpos($ttl, ',') !== false) {
                $parts = explode(',', $ttl, 2);
                $tempat_lahir = trim($parts[0]);
                $tanggal_lahir = trim($parts[1]);
            } else {
                $tempat_lahir = $ttl;
            }
        }

        // Setup base data
        $rowData = [
            'nama_lengkap' => $nama_lengkap,
            'kelas' => $kelas,
            'lembaga_sekolah' => $lembaga,
            'jenis_kelamin' => $jk ?: null,
            'status' => 'AKTIF',
            'tahun_ajaran_id' => $newTahun->id,
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
        ];
        
        // Map semua field yang tersedia dari Excel
        $simpleFields = ['nisn', 'nik', 'kabupaten', 'kecamatan', 'alamat', 'asal_sekolah', 'status_mukim', 'nomor_pip', 'nama_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pekerjaan_ibu', 'no_wa_wali'];
        foreach ($simpleFields as $field) {
            if (isset($headerMap[$field]) && trim($data[$headerMap[$field]]) !== '') {
                $rowData[$field] = trim($data[$headerMap[$field]]);
            }
        }

        // Insert directly
        DataInduk::create($rowData);
        $imported++;
    }

    echo "Imported $imported new students into 2026/2027.\n";

    DB::commit();
    echo "Success!\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
