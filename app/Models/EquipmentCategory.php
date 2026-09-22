<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentCategory extends BaseGlobalModel
{
    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'color',
        'icon',
        'is_system',
        'metadata',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function equipmentModels(): HasMany
    {
        return $this->hasMany(EquipmentModel::class, 'category_id');
    }

    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class, 'category_id');
    }
}
