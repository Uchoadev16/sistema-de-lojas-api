<?php

namespace App\Actions\V1\EquipmentBrands;

use App\Models\EquipmentBrand;
use Illuminate\Support\Facades\DB;

class DeleteEquipmentBrandAction
{
    public function execute(EquipmentBrand $brand): void
    {
        DB::transaction(function () use ($brand): void {
            $brand->delete();
        });
    }
}
