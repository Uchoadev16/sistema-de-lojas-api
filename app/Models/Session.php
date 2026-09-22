<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use App\Support\Enums\Platform;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends BaseGlobalModel
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $fillable = [
        'ip_address',
        'user_agent',
        'device_id',
        'payload',
        'last_activity',
        'expires_at',
        'is_active',
        'revoked_at',
    ];

    protected $casts = [
        'device_platform' => Platform::class,
        'last_activity' => 'timestamp',
        'expires_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
