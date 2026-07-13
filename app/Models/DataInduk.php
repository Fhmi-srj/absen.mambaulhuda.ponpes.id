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
        'tahun_ajaran_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
        'deleted_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::addGlobalScope('activeTahunAjaran', function ($builder) {
            $tahunAktif = \Illuminate\Support\Facades\Cache::remember('active_tahun_ajaran_id', 3600, function () {
                return \App\Models\TahunAjaran::where('is_active', true)->value('id');
            });

            if ($tahunAktif) {
                // Jangan terapkan scope ini jika dipanggil explicitly via API/Admin yang meminta tanpa scope,
                // tapi secara global selalu terapkan.
                $builder->where('tahun_ajaran_id', $tahunAktif);
            }
        });
    }

    // Relationships
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

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
