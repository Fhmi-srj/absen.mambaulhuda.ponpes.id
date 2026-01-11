<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataInduk;
use App\Models\ActivityLog;
use App\Models\CatatanAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SantriApiController extends Controller
{
    /**
     * Get santri for editing
     */
    public function edit($id)
    {
        try {
            $santri = DataInduk::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $santri
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Santri tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Store new santri
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
            ]);

            $data = $request->except(['_token', 'foto_santri', 'dokumen_kk', 'dokumen_akte', 'dokumen_ktp', 'dokumen_ijazah', 'dokumen_sertifikat']);

            // Handle file uploads
            $uploadFields = ['foto_santri', 'dokumen_kk', 'dokumen_akte', 'dokumen_ktp', 'dokumen_ijazah', 'dokumen_sertifikat'];
            foreach ($uploadFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $folder = $field === 'foto_santri' ? 'foto_santri' : 'dokumen';
                    $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

                    $uploadPath = public_path('uploads/' . $folder);
                    if (!is_dir($uploadPath))
                        mkdir($uploadPath, 0755, true);

                    $file->move($uploadPath, $filename);
                    $data[$field] = $folder . '/' . $filename;
                }
            }

            $santri = DataInduk::create($data);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'action' => 'create',
                'module' => 'data_induk',
                'description' => "Menambahkan santri: {$santri->nama_lengkap}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Santri baru berhasil ditambahkan!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan santri: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update santri
     */
    public function update(Request $request, $id)
    {
        try {
            $santri = DataInduk::findOrFail($id);

            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
            ]);

            $data = $request->except(['_token', 'id', 'foto_santri', 'dokumen_kk', 'dokumen_akte', 'dokumen_ktp', 'dokumen_ijazah', 'dokumen_sertifikat']);

            // Handle file uploads
            $uploadFields = ['foto_santri', 'dokumen_kk', 'dokumen_akte', 'dokumen_ktp', 'dokumen_ijazah', 'dokumen_sertifikat'];
            foreach ($uploadFields as $field) {
                if ($request->hasFile($field)) {
                    // Delete old file
                    if ($santri->$field && file_exists(public_path('uploads/' . $santri->$field))) {
                        unlink(public_path('uploads/' . $santri->$field));
                    }

                    $file = $request->file($field);
                    $folder = $field === 'foto_santri' ? 'foto_santri' : 'dokumen';
                    $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

                    $uploadPath = public_path('uploads/' . $folder);
                    if (!is_dir($uploadPath))
                        mkdir($uploadPath, 0755, true);

                    $file->move($uploadPath, $filename);
                    $data[$field] = $folder . '/' . $filename;
                }
            }

            $santri->update($data);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'action' => 'update',
                'module' => 'data_induk',
                'description' => "Mengubah data santri: {$santri->nama_lengkap}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data santri berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui santri: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Soft delete santri
     */
    public function destroy(Request $request, $id)
    {
        try {
            $santri = DataInduk::findOrFail($id);

            // Check if santri has activity records
            $hasActivity = CatatanAktivitas::where('siswa_id', $id)
                ->whereNull('deleted_at')
                ->exists();

            if ($hasActivity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus santri yang memiliki catatan aktivitas!'
                ], 400);
            }

            $santri->deleted_at = now();
            $santri->deleted_by = Auth::id();
            $santri->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'action' => 'delete',
                'module' => 'data_induk',
                'description' => "Menghapus santri: {$santri->nama_lengkap} ke trash",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Santri dipindahkan ke trash!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus santri: ' . $e->getMessage()
            ], 500);
        }
    }
}
