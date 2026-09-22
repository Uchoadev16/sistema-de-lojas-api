<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentBrand extends BaseGlobalModel
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'website',
        'logo_url',
        'is_system',
        'metadata',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function equipmentModels(): HasMany
    {
        return $this->hasMany(EquipmentModel::class, 'brand_id');
    }

    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class, 'brand_id');
    }
}
