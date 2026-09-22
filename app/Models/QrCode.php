<?php

namespace App\Models;

use App\Models\Abstracts\BaseTenantModel;
use App\Support\Enums\QrCodeStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrCode extends BaseTenantModel
{
    protected $fillable = [
        'code',
        'status',
        'printed',
        'is_active',
        'metadata',
        'linked_at',
    ];

    protected $casts = [
        'status' => QrCodeStatus::class,
        'is_active' => 'boolean',
        'printed' => 'boolean',
        'metadata' => 'array',
        'linked_at' => 'datetime',
    ];

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by_user_id');
    }

    public function entityEquipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'entity_id')
            ->where('entity_type', 'equipment');
    }
}
