<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatatanAktivitas;
use App\Models\DataInduk;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AktivitasApiController extends Controller
{
    /**
     * Get aktivitas data for DataTables
     */
    public function data(Request $request)
    {
        $user = auth()->user();
        $query = CatatanAktivitas::with(['santri', 'dibuatOleh'])
            ->whereNull('deleted_at');

        // Role-based filtering: kesehatan only sees 'sakit'
        if ($user->role === 'kesehatan') {
            $query->where('kategori', 'sakit');
        }

        // Kategori filter
        if ($request->kategori && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        // Date filters
        if ($request->tanggal_dari) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->tanggal_sampai) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // Search
        if ($request->search_keyword) {
            $keyword = $request->search_keyword;
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('santri', function ($sq) use ($keyword) {
                    $sq->where('nama_lengkap', 'like', "%{$keyword}%");
                })
                    ->orWhere('judul', 'like', "%{$keyword}%")
                    ->orWhere('keterangan', 'like', "%{$keyword}%");
            });
        }

        // Count total
        $totalRecords = $query->count();

        // Ordering
        $orderColumn = $request->input('order.0.column', 1);
        $orderDir = $request->input('order.0.dir', 'desc');

        $columnMap = [
            0 => 'id',
            1 => 'tanggal',
            2 => 'tanggal_selesai',
            3 => 'siswa_id', // Will need to join for actual sorting
            4 => 'kategori',
            5 => 'judul',
            6 => 'keterangan'
        ];

        $sortColumn = $columnMap[$orderColumn] ?? 'tanggal';
        $query->orderBy($sortColumn, $orderDir);

        // Pagination
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $data = $query->skip($start)->take($length)->get();

        // Transform data for DataTables
        $transformedData = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'siswa_id' => $item->siswa_id,
                'nama_lengkap' => $item->santri->nama_lengkap ?? '-',
                'kelas' => $item->santri->kelas ?? '-',
                'nomor_induk' => $item->santri->nisn ?? '-',
                'no_wa_wali' => $item->santri->no_wa_wali ?? null,
                'kategori' => $item->kategori,
                'judul' => $item->judul,
                'keterangan' => $item->keterangan,
                'status_sambangan' => $item->status_sambangan,
                'status_kegiatan' => $item->status_kegiatan,
                'tanggal' => $item->tanggal?->format('Y-m-d H:i:s'),
                'batas_waktu' => $item->batas_waktu?->format('Y-m-d H:i:s'),
                'tanggal_selesai' => $item->tanggal_selesai?->format('Y-m-d H:i:s'),
                'foto_dokumen_1' => $item->foto_dokumen_1,
                'foto_dokumen_2' => $item->foto_dokumen_2,
                'dibuat_oleh' => $item->dibuat_oleh,
                'pembuat_nama' => $item->dibuatOleh->name ?? '-',
                'created_at' => $item->created_at?->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $transformedData
        ]);
    }

    /**
     * Store new aktivitas
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Validate required fields
        $missingFields = [];

        if (!$request->siswa_id) {
            $missingFields[] = 'Siswa belum dipilih';
        } else {
            // Check santri exists
            $santri = DataInduk::where('id', $request->siswa_id)
                ->whereNull('deleted_at')
                ->first();
            if (!$santri) {
                $missingFields[] = 'Siswa tidak ditemukan di database';
            }
        }

        if (!$request->kategori) {
            $missingFields[] = 'Kategori tidak valid';
        }

        if (!$request->tanggal) {
            $missingFields[] = 'Tanggal Mulai/Pergi';
        }

        // Category-specific validation
        switch ($request->kategori) {
            case 'sakit':
                if (empty($request->judul))
                    $missingFields[] = 'Diagnosa (Judul)';
                break;
            case 'izin_keluar':
                if (empty($request->judul))
                    $missingFields[] = 'Keperluan';
                if (empty($request->batas_waktu))
                    $missingFields[] = 'Batas Waktu';
                break;
            case 'izin_pulang':
                if (empty($request->judul))
                    $missingFields[] = 'Alasan';
                if (empty($request->batas_waktu))
                    $missingFields[] = 'Batas Waktu';
                break;
            case 'sambangan':
                if (empty($request->judul))
                    $missingFields[] = 'Nama Penjenguk';
                if (empty($request->status_sambangan))
                    $missingFields[] = 'Hubungan Penjenguk';
                break;
            case 'pelanggaran':
                if (empty($request->judul))
                    $missingFields[] = 'Jenis Pelanggaran';
                break;
            case 'paket':
                if (empty($request->judul))
                    $missingFields[] = 'Isi Paket';
                if (!$request->hasFile('foto_dokumen_1'))
                    $missingFields[] = 'Foto Paket';
                break;
            case 'hafalan':
                if (empty($request->judul))
                    $missingFields[] = 'Nama Kitab/Surat';
                break;
        }

        if (!empty($missingFields)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data belum lengkap:\n• ' . implode('\n• ', $missingFields)
            ], 422);
        }

        try {
            // Handle file uploads
            $foto1 = null;
            $foto2 = null;

            // Debug: Log incoming files
            \Log::info('File upload check', [
                'has_foto_1' => $request->hasFile('foto_dokumen_1'),
                'has_foto_2' => $request->hasFile('foto_dokumen_2'),
                'all_files' => $request->allFiles(),
            ]);

            if ($request->hasFile('foto_dokumen_1') && $request->file('foto_dokumen_1')->isValid()) {
                $foto1 = $this->uploadFile($request->file('foto_dokumen_1'));
                \Log::info('Foto 1 uploaded', ['path' => $foto1]);
            }
            if ($request->hasFile('foto_dokumen_2') && $request->file('foto_dokumen_2')->isValid()) {
                $foto2 = $this->uploadFile($request->file('foto_dokumen_2'));
                \Log::info('Foto 2 uploaded', ['path' => $foto2]);
            }

            // Generate kode konfirmasi for izin_keluar and izin_pulang
            $kodeKonfirmasi = null;
            if (in_array($request->kategori, ['izin_keluar', 'izin_pulang'])) {
                $kodeKonfirmasi = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            }

            // Create aktivitas
            $aktivitas = CatatanAktivitas::create([
                'siswa_id' => $request->siswa_id,
                'kategori' => $request->kategori,
                'judul' => $request->judul,
                'keterangan' => $request->keterangan,
                'status_sambangan' => $request->status_sambangan,
                'status_kegiatan' => $request->kategori === 'paket' ? 'Belum Diterima' : $request->status_kegiatan,
                'tanggal' => $request->tanggal,
                'batas_waktu' => $request->batas_waktu ?: null,
                'tanggal_selesai' => $request->tanggal_selesai ?: null,
                'kode_konfirmasi' => $kodeKonfirmasi,
                'foto_dokumen_1' => $foto1,
                'foto_dokumen_2' => $foto2,
                'dibuat_oleh' => $user->id,
            ]);

            // Log activity
            ActivityLog::log(
                'CREATE',
                'catatan_aktivitas',
                $aktivitas->id,
                $santri->nama_lengkap ?? 'Unknown',
                null,
                [
                    'kategori' => $request->kategori,
                    'judul' => $request->judul,
                ],
                "Tambah aktivitas {$request->kategori}"
            );

            // Return with kode_konfirmasi for print slip
            $response = [
                'status' => 'success',
                'message' => 'Data aktivitas berhasil disimpan!'
            ];

            if ($kodeKonfirmasi) {
                $response['data'] = [
                    'id' => $aktivitas->id,
                    'nama_santri' => $santri->nama_lengkap ?? '-',
                    'kelas' => $santri->kelas ?? '-',
                    'kategori' => $request->kategori,
                    'judul' => $request->judul,
                    'batas_waktu' => $request->batas_waktu,
                    'kode_konfirmasi' => $kodeKonfirmasi,
                ];
            }

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single aktivitas for edit
     */
    public function edit($id)
    {
        $aktivitas = CatatanAktivitas::with('santri')->find($id);

        if (!$aktivitas) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $aktivitas->id,
                'siswa_id' => $aktivitas->siswa_id,
                'nama_lengkap' => $aktivitas->santri->nama_lengkap ?? '-',
                'kelas' => $aktivitas->santri->kelas ?? '-',
                'kategori' => $aktivitas->kategori,
                'judul' => $aktivitas->judul,
                'keterangan' => $aktivitas->keterangan,
                'status_sambangan' => $aktivitas->status_sambangan,
                'status_kegiatan' => $aktivitas->status_kegiatan,
                'tanggal' => $aktivitas->tanggal?->format('Y-m-d\TH:i'),
                'batas_waktu' => $aktivitas->batas_waktu?->format('Y-m-d\TH:i'),
                'tanggal_selesai' => $aktivitas->tanggal_selesai?->format('Y-m-d\TH:i'),
                'foto_dokumen_1' => $aktivitas->foto_dokumen_1,
                'foto_dokumen_2' => $aktivitas->foto_dokumen_2,
            ]
        ]);
    }

    /**
     * Update aktivitas
     */
    public function update(Request $request, $id)
    {
        $aktivitas = CatatanAktivitas::find($id);

        if (!$aktivitas) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        try {
            // Handle file uploads
            if ($request->hasFile('foto_dokumen_1')) {
                // Delete old file if exists
                if ($aktivitas->foto_dokumen_1) {
                    Storage::disk('public')->delete($aktivitas->foto_dokumen_1);
                }
                $aktivitas->foto_dokumen_1 = $this->uploadFile($request->file('foto_dokumen_1'));
            }
            if ($request->hasFile('foto_dokumen_2')) {
                if ($aktivitas->foto_dokumen_2) {
                    Storage::disk('public')->delete($aktivitas->foto_dokumen_2);
                }
                $aktivitas->foto_dokumen_2 = $this->uploadFile($request->file('foto_dokumen_2'));
            }

            // Update data
            $aktivitas->update([
                'judul' => $request->judul ?? $aktivitas->judul,
                'keterangan' => $request->keterangan ?? $aktivitas->keterangan,
                'status_sambangan' => $request->status_sambangan ?? $aktivitas->status_sambangan,
                'status_kegiatan' => $request->status_kegiatan ?? $aktivitas->status_kegiatan,
                'tanggal' => $request->tanggal ?? $aktivitas->tanggal,
                'batas_waktu' => $request->has('batas_waktu') ? ($request->batas_waktu ?: null) : $aktivitas->batas_waktu,
                'tanggal_selesai' => $request->has('tanggal_selesai') ? ($request->tanggal_selesai ?: null) : $aktivitas->tanggal_selesai,
            ]);

            ActivityLog::log('UPDATE', 'catatan_aktivitas', $aktivitas->id, $aktivitas->santri->nama_lengkap ?? 'Unknown');

            return response()->json([
                'status' => 'success',
                'message' => 'Data aktivitas berhasil diupdate!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete aktivitas (soft delete)
     */
    public function destroy($id)
    {
        $aktivitas = CatatanAktivitas::find($id);

        if (!$aktivitas) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        try {
            $aktivitas->update([
                'deleted_at' => now(),
                'deleted_by' => auth()->id(),
            ]);

            ActivityLog::log('DELETE', 'catatan_aktivitas', $aktivitas->id, $aktivitas->santri->nama_lengkap ?? 'Unknown');

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete aktivitas (soft delete)
     */
    public function bulkDestroy(Request $request)
    {
        $user = auth()->user();

        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data yang dipilih'
            ], 400);
        }

        try {
            CatatanAktivitas::whereIn('id', $ids)->update([
                'deleted_at' => now(),
                'deleted_by' => $user->id,
            ]);

            ActivityLog::log(
                'DELETE',
                'catatan_aktivitas',
                null,
                null,
                null,
                null,
                "Hapus " . count($ids) . " data aktivitas ke trash"
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data dipindahkan ke trash'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload file with compression
     */
    protected function uploadFile($file): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        // Validate file size (max 5MB)
        if ($file->getSize() > 5 * 1024 * 1024) {
            throw new \Exception('Ukuran file terlalu besar (max 5MB)');
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            throw new \Exception('Tipe file tidak didukung');
        }

        // Generate unique filename
        $ext = $file->getClientOriginalExtension();
        $filename = date('Ymd_His') . '_' . uniqid() . '.' . $ext;

        // Store file
        $path = $file->storeAs('bukti_aktivitas', $filename, 'public');

        return $path;
    }
}
