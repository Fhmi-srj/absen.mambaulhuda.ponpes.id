<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'role',
        'foto',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'deleted_at' => 'datetime',
    ];

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_KARYAWAN = 'karyawan';
    const ROLE_PENGURUS = 'pengurus';
    const ROLE_GURU = 'guru';
    const ROLE_KEAMANAN = 'keamanan';
    const ROLE_KESEHATAN = 'kesehatan';

    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_KARYAWAN => 'Karyawan',
            self::ROLE_PENGURUS => 'Pengurus',
            self::ROLE_GURU => 'Guru',
            self::ROLE_KEAMANAN => 'Keamanan',
            self::ROLE_KESEHATAN => 'Kesehatan',
        ];
    }

    public function getRoleLabelAttribute(): string
    {
        return self::getRoles()[$this->role] ?? ucfirst($this->role);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // Relationships
    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function catatanAktivitas()
    {
        return $this->hasMany(CatatanAktivitas::class, 'dibuat_oleh');
    }
}
