<?php

namespace App\Actions\V1\EquipmentCategories;

use App\Models\EquipmentCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateEquipmentCategoryAction
{
    public function execute(EquipmentCategory $category, array $input): EquipmentCategory
    {
        return DB::transaction(function () use ($category, $input): EquipmentCategory {
            if (isset($input['name']) && ! isset($input['slug'])) {
                $input['slug'] = Str::slug($input['name']);
            }
            $category->fill($input);
            $category->save();

            return $category;
        });
    }
}
