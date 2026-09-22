<?php

namespace App\Models;

use App\Models\Abstracts\BaseTenantModel;
use App\Support\Enums\EnvironmentStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Environment extends BaseTenantModel
{
    protected $fillable = [
        'name',
        'code',
        'floor',
        'area',
        'purpose',
        'description',
        'status',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'status' => EnvironmentStatus::class,
        'is_active' => 'boolean',
        'area' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
