<?php

namespace App\Actions\V1\EquipmentCategories;

use App\Models\EquipmentCategory;
use Illuminate\Support\Facades\DB;

class DeleteEquipmentCategoryAction
{
    public function execute(EquipmentCategory $category): void
    {
        DB::transaction(function () use ($category): void {
            $category->delete();
        });
    }
}
