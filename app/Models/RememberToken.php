<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RememberToken extends Model
{
    use HasFactory;

    protected $table = 'remember_tokens';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'token_hash',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        return $this->expires_at->isFuture();
    }
}
