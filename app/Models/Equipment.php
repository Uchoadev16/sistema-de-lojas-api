<?php

namespace App\Models;

use App\Models\Abstracts\BaseTenantModel;
use App\Support\Enums\EquipmentCondition;
use App\Support\Enums\EquipmentStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Equipment extends BaseTenantModel
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'model_id',
        'identifier',
        'asset_tag',
        'serial_number',
        'equipment_type',
        'manufacturer',
        'model_name',
        'capacity',
        'capacity_unit',
        'refrigerant',
        'voltage',
        'power_kw',
        'installed_at',
        'warranty_start',
        'warranty_end',
        'purchase_date',
        'purchase_price_cents',
        'condition',
        'last_maintenance_at',
        'next_maintenance_at',
        'qr_code_value',
        'description',
        'notes',
        'external_id',
        'metadata',
        'status',
        'is_active',
    ];

    protected $casts = [
        'status' => EquipmentStatus::class,
        'condition' => EquipmentCondition::class,
        'is_active' => 'boolean',
        'purchase_price_cents' => 'integer',
        'power_kw' => 'decimal:3',
        'capacity' => 'decimal:2',
        'metadata' => 'array',
        'installed_at' => 'datetime',
        'warranty_start' => 'datetime',
        'warranty_end' => 'datetime',
        'purchase_date' => 'date',
        'last_maintenance_at' => 'datetime',
        'next_maintenance_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(EquipmentCategory::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(EquipmentBrand::class, 'brand_id');
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(EquipmentModel::class, 'model_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function qrCode(): HasOne
    {
        return $this->hasOne(QrCode::class, 'entity_id')
            ->where('entity_type', 'equipment');
    }
}
