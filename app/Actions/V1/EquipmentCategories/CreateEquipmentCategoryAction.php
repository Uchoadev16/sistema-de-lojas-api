<?php

namespace App\Actions\V1\EquipmentCategories;

use App\Models\EquipmentCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateEquipmentCategoryAction
{
    public function execute(array $input): EquipmentCategory
    {
        return DB::transaction(function () use ($input): EquipmentCategory {
            $input['slug'] = $input['slug'] ?? Str::slug($input['name']);

            return EquipmentCategory::create($input);
        });
    }
}
