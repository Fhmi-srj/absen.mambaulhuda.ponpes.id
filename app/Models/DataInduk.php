<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataInduk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'data_induk';

    protected $fillable = [
        'no_urut',
        'nama_lengkap',
        'kelas',
        'quran',
        'kategori',
        'nisn',
        'lembaga_sekolah',
        'status',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'jumlah_saudara',
        'nomor_kk',
        'nik',
        'kecamatan',
        'kabupaten',
        'alamat',
        'asal_sekolah',
        'status_mukim',
        'nama_ayah',
        'tempat_lahir_ayah',
        'tanggal_lahir_ayah',
        'nik_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'nama_ibu',
        'tempat_lahir_ibu',
        'tanggal_lahir_ibu',
        'nik_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'no_wa_wali',
        'nomor_rfid',
        'dokumen_kk',
        'dokumen_akte',
        'dokumen_ktp',
        'dokumen_ijazah',
        'dokumen_sertifikat',
        'foto_santri',
        'nomor_pip',
        'sumber_info',
        'prestasi',
        'tingkat_prestasi',
        'juara_prestasi',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function catatanAktivitas()
    {
        return $this->hasMany(CatatanAktivitas::class, 'siswa_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'AKTIF');
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_lengkap', 'like', "%{$keyword}%")
                ->orWhere('nisn', 'like', "%{$keyword}%")
                ->orWhere('nik', 'like', "%{$keyword}%");
        });
    }

    // Accessors
    public function getFormattedPhoneAttribute(): ?string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->no_wa_wali);
        if (empty($phone))
            return null;

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }
        return $phone;
    }
}
