<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'device_name',
        'ip_address',
        'action',
        'table_name',
        'record_id',
        'record_name',
        'old_data',
        'new_data',
        'description',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log(
        string $action,
        ?string $tableName = null,
        ?int $recordId = null,
        ?string $recordName = null,
        ?array $oldData = null,
        ?array $newData = null,
        ?string $description = null
    ): self {
        $user = auth()->user();

        return self::create([
            'user_id' => $user?->id ?? 0,
            'user_name' => $user?->name ?? 'System',
            'device_name' => self::getDeviceName(),
            'ip_address' => request()->ip() ?? '0.0.0.0',
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'record_name' => $recordName,
            'old_data' => $oldData,
            'new_data' => $newData,
            'description' => $description,
        ]);
    }

    protected static function getDeviceName(): string
    {
        $userAgent = request()->userAgent() ?? '';

        if (preg_match('/iPhone/', $userAgent))
            return 'iPhone';
        if (preg_match('/iPad/', $userAgent))
            return 'iPad';
        if (preg_match('/SM-[A-Z0-9]+/i', $userAgent, $m))
            return 'Samsung ' . $m[0];
        if (preg_match('/SAMSUNG|Galaxy/i', $userAgent))
            return 'Samsung Galaxy';
        if (preg_match('/Pixel/', $userAgent))
            return 'Google Pixel';
        if (preg_match('/OPPO|CPH/i', $userAgent))
            return 'OPPO';
        if (preg_match('/vivo/i', $userAgent))
            return 'Vivo';
        if (preg_match('/Xiaomi|Redmi|POCO/i', $userAgent))
            return 'Xiaomi';
        if (preg_match('/Huawei/i', $userAgent))
            return 'Huawei';
        if (preg_match('/realme/i', $userAgent))
            return 'Realme';
        if (preg_match('/Android/', $userAgent))
            return 'Android Device';
        if (preg_match('/Macintosh/', $userAgent))
            return 'MacBook/iMac';
        if (preg_match('/Windows NT 10/', $userAgent))
            return 'Windows PC';
        if (preg_match('/Windows NT/', $userAgent))
            return 'Windows PC';
        if (preg_match('/Linux/', $userAgent))
            return 'Linux PC';
        if (preg_match('/CrOS/', $userAgent))
            return 'Chromebook';

        return 'Unknown Device';
    }
}
