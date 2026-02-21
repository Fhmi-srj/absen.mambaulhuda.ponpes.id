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
        'scheduled_time',
        'end_time',
        'tolerance_minutes',
        'late_tolerance_minutes',
        'late_time',
        'is_active',
        'disable_daily_reset',
        'no_reset_start_date',
        'no_reset_end_date',
        'deleted_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'disable_daily_reset' => 'boolean',
        'no_reset_start_date' => 'date',
        'no_reset_end_date' => 'date',
    ];
}
