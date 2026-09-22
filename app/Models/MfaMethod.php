<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use App\Support\Enums\MfaType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MfaMethod extends BaseGlobalModel
{
    use SoftDeletes;

    protected $fillable = [
        'method_type',
        'name',
        'identifier',
        'secret_reference',
        'channel',
        'verified_at',
        'enabled',
        'is_default',
        'failures_count',
        'last_used_at',
        'last_attempt_at',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'method_type' => MfaType::class,
        'is_default' => 'boolean',
        'is_enabled' => 'boolean',
        'last_used_at' => 'datetime',
        'verified_at' => 'datetime',
        'recovery_codes' => 'json',
        'metadata' => 'json',
    ];

    protected $hidden = [
        'secret',
        'recovery_codes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
