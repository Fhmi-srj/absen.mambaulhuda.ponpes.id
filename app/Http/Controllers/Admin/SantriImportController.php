<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SantriImportController extends Controller
{
    private $columnDefinitions = [
        ['name' => 'nama_lengkap', 'label' => 'Nama Lengkap', 'required' => true],
        ['name' => 'kelas', 'label' => 'Kelas', 'required' => true],
        ['name' => 'quran', 'label' => 'Quran', 'required' => false],
        ['name' => 'kategori', 'label' => 'Kategori', 'required' => false],
        ['name' => 'nisn', 'label' => 'NISN', 'required' => false],
        ['name' => 'lembaga_sekolah', 'label' => 'Lembaga Sekolah', 'required' => false],
        ['name' => 'status', 'label' => 'Status', 'required' => false, 'default' => 'AKTIF'],
        ['name' => 'tempat_lahir', 'label' => 'Tempat Lahir', 'required' => false],
        ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'required' => false],
        ['name' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'required' => false],
        ['name' => 'jumlah_saudara', 'label' => 'Jumlah Saudara', 'required' => false],
        ['name' => 'nomor_kk', 'label' => 'Nomor KK', 'required' => false],
        ['name' => 'nik', 'label' => 'NIK', 'required' => false],
        ['name' => 'kecamatan', 'label' => 'Kecamatan', 'required' => false],
        ['name' => 'kabupaten', 'label' => 'Kabupaten', 'required' => false],
        ['name' => 'alamat', 'label' => 'Alamat', 'required' => false],
        ['name' => 'asal_sekolah', 'label' => 'Asal Sekolah', 'required' => false],
        ['name' => 'status_mukim', 'label' => 'Status Mukim', 'required' => false],
        ['name' => 'nama_ayah', 'label' => 'Nama Ayah', 'required' => false],
        ['name' => 'tempat_lahir_ayah', 'label' => 'Tempat Lahir Ayah', 'required' => false],
        ['name' => 'tanggal_lahir_ayah', 'label' => 'Tanggal Lahir Ayah', 'required' => false],
        ['name' => 'nik_ayah', 'label' => 'NIK Ayah', 'required' => false],
        ['name' => 'pekerjaan_ayah', 'label' => 'Pekerjaan Ayah', 'required' => false],
        ['name' => 'penghasilan_ayah', 'label' => 'Penghasilan Ayah', 'required' => false],
        ['name' => 'nama_ibu', 'label' => 'Nama Ibu', 'required' => false],
        ['name' => 'tempat_lahir_ibu', 'label' => 'Tempat Lahir Ibu', 'required' => false],
        ['name' => 'tanggal_lahir_ibu', 'label' => 'Tanggal Lahir Ibu', 'required' => false],
        ['name' => 'nik_ibu', 'label' => 'NIK Ibu', 'required' => false],
        ['name' => 'pekerjaan_ibu', 'label' => 'Pekerjaan Ibu', 'required' => false],
        ['name' => 'penghasilan_ibu', 'label' => 'Penghasilan Ibu', 'required' => false],
        ['name' => 'no_wa_wali', 'label' => 'No. WA Wali', 'required' => false],
        ['name' => 'nomor_rfid', 'label' => 'Nomor RFID', 'required' => false],
        ['name' => 'nomor_pip', 'label' => 'Nomor PIP', 'required' => false],
        ['name' => 'sumber_info', 'label' => 'Sumber Info', 'required' => false],
        ['name' => 'prestasi', 'label' => 'Prestasi', 'required' => false],
        ['name' => 'tingkat_prestasi', 'label' => 'Tingkat Prestasi', 'required' => false],
        ['name' => 'juara_prestasi', 'label' => 'Juara Prestasi', 'required' => false],
    ];

    public function index()
    {
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'columnDefinitions' => $this->columnDefinitions
            ]);
        }

        return view('spa');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_santri' => 'required|mimes:xlsx,xls|max:10240'
        ]);

        $errors = [];
        $successCount = 0;

        try {
            $spreadsheet = IOFactory::load($request->file('file_santri')->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            array_shift($rows); // Skip header

            DB::beginTransaction();
            $rowNum = 1;

            foreach ($rows as $data) {
                $rowNum++;
                if (empty(array_filter($data)))
                    continue;

                $rowData = [];
                foreach ($this->columnDefinitions as $index => $col) {
                    $value = isset($data[$index]) ? trim($data[$index]) : '';
                    if ($value === '' && isset($col['default']))
                        $value = $col['default'];
                    if ($col['name'] === 'jenis_kelamin' && $value !== '' && !in_array(strtoupper($value), ['L', 'P'])) {
                        $value = '';
                    } elseif ($col['name'] === 'jenis_kelamin' && $value !== '') {
                        $value = strtoupper($value);
                    }
                    $rowData[$col['name']] = $value !== '' ? $value : null;
                }

                $requiredMissing = [];
                foreach ($this->columnDefinitions as $col) {
                    if ($col['required'] && empty($rowData[$col['name']])) {
                        $requiredMissing[] = $col['label'];
                    }
                }

                if (!empty($requiredMissing)) {
                    $errors[] = "Baris $rowNum: " . implode(', ', $requiredMissing) . " wajib diisi";
                    continue;
                }

                $existing = null;
                if (!empty($rowData['nisn'])) {
                    $existing = DB::table('data_induk')->where('nisn', $rowData['nisn'])->first();
                } else {
                    $existing = DB::table('data_induk')
                        ->where('nama_lengkap', $rowData['nama_lengkap'])
                        ->where('kelas', $rowData['kelas'])
                        ->first();
                }

                if ($existing) {
                    DB::table('data_induk')->where('id', $existing->id)->update(array_merge($rowData, ['updated_at' => now()]));
                } else {
                    DB::table('data_induk')->insert(array_merge($rowData, ['created_at' => now(), 'updated_at' => now()]));
                }
                $successCount++;
            }

            DB::commit();

            if ($successCount > 0) {
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json(['status' => 'success', 'message' => "$successCount data santri berhasil diimport!"]); // Modified message to include count
                }
                return redirect()->route('admin.santri')->with('success', "$successCount data santri berhasil diimport!"); // Modified message to include count
            } elseif (empty($errors)) { // This block was part of the original try block, moved here to handle cases where no data was processed but no exception occurred
                $errors[] = "Tidak ada data yang valid untuk diimport";
            }
        } catch (ValidationException $e) { // Added new catch block
            DB::rollBack(); // Added rollback for consistency
            $failures = $e->failures();
            $errorMsgs = [];
            foreach ($failures as $failure) {
                $errorMsgs[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Validasi gagal', 'errors' => $errorMsgs], 422);
            }

            $columnDefinitions = $this->getColumnDefinitions();
            return view('admin.santri-import', [
                'columnDefinitions' => $columnDefinitions,
                'errors' => $errorMsgs
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $errors[] = 'Error: ' . $e->getMessage();

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500); // Changed structure to match new format
            }
            return back()->with('error', $e->getMessage()); // Changed return to match new format
        }

        // This return is for cases where there were errors collected but no exception was thrown,
        // or if $successCount was 0 and $errors was not empty.
        return view('admin.santri-import', ['columnDefinitions' => $this->columnDefinitions, 'errors' => $errors]);
    }
}
