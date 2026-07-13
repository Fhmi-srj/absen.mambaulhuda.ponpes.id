<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Models\DataInduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TahunAjaranApiController extends Controller
{
    public function index()
    {
        return TahunAjaran::orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:tahun_ajarans,nama'
        ]);

        $tahun = TahunAjaran::create([
            'nama' => $request->nama,
            'is_active' => false
        ]);

        return response()->json($tahun);
    }

    public function activate(Request $request, $id)
    {
        $newTahun = TahunAjaran::findOrFail($id);
        $mappings = $request->input('mappings', []);

        if ($newTahun->is_active) {
            return response()->json(['message' => 'Tahun Ajaran ini sudah aktif.'], 400);
        }

        DB::beginTransaction();
        try {
            $currentActive = TahunAjaran::where('is_active', true)->first();
            
            TahunAjaran::query()->update(['is_active' => false]);
            
            $newTahun->is_active = true;
            $newTahun->save();
            
            Cache::forget('active_tahun_ajaran_id');

            if ($currentActive) {
                // Ambil data tanpa menggunakan global scope aktif
                $dataIndukLama = DataInduk::withoutGlobalScope('activeTahunAjaran')
                    ->where('tahun_ajaran_id', $currentActive->id)
                    ->get();
                    
                foreach ($dataIndukLama as $santri) {
                    $oldKelas = $santri->kelas;
                    $newKelas = $mappings[$oldKelas] ?? $oldKelas; // if not mapped, stay same class

                    $lowerNew = strtolower($newKelas);
                    if ($lowerNew === 'lulus' || $lowerNew === 'keluar' || $lowerNew === 'boyong' || $lowerNew === 'hapus') {
                        // Jangan duplikasi ke tahun ajaran baru, artinya santri selesai/lulus
                        continue;
                    }

                    $newSantri = $santri->replicate();
                    $newSantri->tahun_ajaran_id = $newTahun->id;
                    $newSantri->kelas = $newKelas;
                    $newSantri->save();
                }
            }
            
            DB::commit();
            return response()->json(['message' => 'Tahun Ajaran berhasil diaktifkan. Data santri telah di-promote.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal mengaktifkan tahun ajaran: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $tahun = TahunAjaran::findOrFail($id);
        
        if ($tahun->is_active) {
            return response()->json(['message' => 'Tidak dapat menghapus tahun ajaran yang sedang aktif.'], 400);
        }

        // Pastikan tidak ada data induk yang sudah masuk ke tahun ini secara manual sebelum dihapus
        $count = DataInduk::withoutGlobalScope('activeTahunAjaran')->where('tahun_ajaran_id', $id)->count();
        if ($count > 0) {
            return response()->json(['message' => "Tidak dapat menghapus, terdapat $count santri pada tahun ajaran ini."], 400);
        }

        $tahun->delete();
        return response()->json(['message' => 'Berhasil dihapus']);
    }
}
