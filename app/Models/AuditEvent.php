<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use App\Support\Enums\AuditResult;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditEvent extends BaseGlobalModel
{
    public $timestamps = false;

    protected $fillable = [
        'event_type',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'metadata',
        'url',
        'user_agent',
        'ip_address',
        'tags',
        'request_id',
    ];

    protected $casts = [
        'previous_values' => 'json',
        'new_values' => 'json',
        'changes' => 'json',
        'context' => 'json',
        'created_at' => 'datetime',
        'result' => AuditResult::class,
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
