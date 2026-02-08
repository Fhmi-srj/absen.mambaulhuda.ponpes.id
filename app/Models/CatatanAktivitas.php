<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatatanAktivitas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'catatan_aktivitas';

    protected $fillable = [
        'siswa_id',
        'kategori',
        'judul',
        'keterangan',
        'status_sambangan',
        'status_kegiatan',
        'tanggal',
        'batas_waktu',
        'tanggal_selesai',
        'kode_konfirmasi',
        'foto_dokumen_1',
        'foto_dokumen_2',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'batas_waktu' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Kategori constants
    const KATEGORI_SAKIT = 'sakit';
    const KATEGORI_IZIN_KELUAR = 'izin_keluar';
    const KATEGORI_IZIN_PULANG = 'izin_pulang';
    const KATEGORI_SAMBANGAN = 'sambangan';
    const KATEGORI_PELANGGARAN = 'pelanggaran';
    const KATEGORI_PAKET = 'paket';
    const KATEGORI_HAFALAN = 'hafalan';

    public static function getKategoriInfo($kategori): array
    {
        $map = [
            'sakit' => ['label' => 'Sakit', 'color' => '#ef4444', 'bg' => '#fef2f2', 'icon' => 'fas fa-procedures'],
            'izin_keluar' => ['label' => 'Izin Keluar', 'color' => '#f59e0b', 'bg' => '#fffbeb', 'icon' => 'fas fa-sign-out-alt'],
            'izin_pulang' => ['label' => 'Izin Pulang', 'color' => '#f97316', 'bg' => '#fff7ed', 'icon' => 'fas fa-home'],
            'sambangan' => ['label' => 'Sambangan', 'color' => '#10b981', 'bg' => '#ecfdf5', 'icon' => 'fas fa-users'],
            'pelanggaran' => ['label' => 'Pelanggaran', 'color' => '#db2777', 'bg' => '#fdf2f8', 'icon' => 'fas fa-exclamation-triangle'],
            'paket' => ['label' => 'Paket', 'color' => '#3b82f6', 'bg' => '#eff6ff', 'icon' => 'fas fa-box-open'],
            'hafalan' => ['label' => 'Hafalan', 'color' => '#3b82f6', 'bg' => '#dbeafe', 'icon' => 'fas fa-quran'],
        ];
        return $map[$kategori] ?? ['label' => $kategori, 'color' => '#64748b', 'bg' => '#f1f5f9', 'icon' => 'fas fa-info'];
    }

    public function getKategoriInfoAttribute(): array
    {
        return self::getKategoriInfo($this->kategori);
    }

    // Relationships
    public function santri()
    {
        return $this->belongsTo(DataInduk::class, 'siswa_id');
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    // Scopes
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', today());
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }
}
