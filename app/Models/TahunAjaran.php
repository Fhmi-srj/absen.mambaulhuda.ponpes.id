<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajarans';
    protected $fillable = ['nama', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
