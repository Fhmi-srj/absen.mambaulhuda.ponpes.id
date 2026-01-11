<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalAbsen extends Model
{
    use SoftDeletes;

    protected $table = 'jadwal_absens';

    protected $fillable = [
        'name',
        'type',
        'start_time',
        'end_time',
        'tolerance_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
