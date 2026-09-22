<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use App\Support\Enums\SyncConflictType;
use App\Support\Enums\SyncResolutionStrategy;
use App\Support\Enums\SyncStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncConflict extends BaseGlobalModel
{
    protected $fillable = [
        'sync_queue_id',
        'entity_type',
        'entity_id',
        'external_id',
        'local_record_hash',
        'external_record_hash',
        'conflict_type',
        'resolution_status',
        'resolution_applied_by',
        'resolved_at',
        'local_snapshot',
        'external_snapshot',
        'diff_summary',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'conflict_type' => SyncConflictType::class,
        'resolution_strategy' => SyncResolutionStrategy::class,
        'status' => SyncStatus::class,
        'server_version' => 'json',
        'client_version' => 'json',
        'merged_version' => 'json',
        'resolved_at' => 'datetime',
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

    public function syncQueue(): BelongsTo
    {
        return $this->belongsTo(SyncQueue::class);
    }
}
