<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use App\Support\Enums\SyncOperationType;
use App\Support\Enums\SyncStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SyncQueue extends BaseGlobalModel
{
    protected $fillable = [
        'sync_type',
        'action',
        'entity_type',
        'entity_id',
        'external_id',
        'payload_snapshot',
        'checksum',
        'priority',
        'status',
        'attempts',
        'last_attempt_at',
        'next_attempt_at',
        'locked_at',
        'locked_by',
        'worker_id',
        'errors',
        'metadata',
        'executed_at',
        'completed_at',
        'failed_at',
        'source_system',
        'destination_system',
    ];

    protected $casts = [
        'operation' => SyncOperationType::class,
        'status' => SyncStatus::class,
        'payload' => 'json',
        'changed_fields' => 'json',
        'client_timestamp' => 'datetime',
        'last_attempt_at' => 'datetime',
        'synced_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function conflicts(): HasMany
    {
        return $this->hasMany(SyncConflict::class);
    }
}
