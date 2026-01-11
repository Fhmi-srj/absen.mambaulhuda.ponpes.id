<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;

    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'attendance_date',
        'attendance_time',
        'status',
        'minutes_late',
        'latitude',
        'longitude',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'latitude' => 'float',
        'longitude' => 'float',
        'minutes_late' => 'integer',
    ];

    public function santri()
    {
        return $this->belongsTo(DataInduk::class, 'user_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalAbsen::class, 'jadwal_id');
    }
}
