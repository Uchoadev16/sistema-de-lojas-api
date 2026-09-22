<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use App\Support\Enums\DeviceStatus;
use App\Support\Enums\Platform;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends BaseGlobalModel
{
    use SoftDeletes;

    protected $fillable = [
        'device_type',
        'manufacturer',
        'model',
        'os_version',
        'app_version',
        'device_identifier',
        'push_token',
        'last_seen_at',
        'is_active',
        'status',
        'last_issued_token_hash',
        'metadata',
    ];

    protected $casts = [
        'platform' => Platform::class,
        'status' => DeviceStatus::class,
        'is_trusted' => 'boolean',
        'push_enabled' => 'boolean',
        'notifications_enabled' => 'boolean',
        'location_enabled' => 'boolean',
        'offline_mode_enabled' => 'boolean',
        'last_seen_at' => 'datetime',
        'registered_at' => 'datetime',
        'activated_at' => 'datetime',
        'last_sync_at' => 'datetime',
        'last_sync_successful_at' => 'datetime',
        'last_known_latitude' => 'decimal:8',
        'last_known_longitude' => 'decimal:8',
        'metadata' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
