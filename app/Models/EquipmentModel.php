<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentModel extends BaseGlobalModel
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'image_url',
        'technical_specifications',
        'metadata',
    ];

    protected $casts = [
        'technical_specifications' => 'array',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(EquipmentBrand::class, 'brand_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EquipmentCategory::class, 'category_id');
    }

    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class, 'model_id');
    }
}
