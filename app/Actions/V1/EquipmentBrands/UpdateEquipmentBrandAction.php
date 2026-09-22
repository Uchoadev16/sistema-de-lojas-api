<?php

namespace App\Actions\V1\EquipmentBrands;

use App\Models\EquipmentBrand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateEquipmentBrandAction
{
    public function execute(EquipmentBrand $brand, array $input): EquipmentBrand
    {
        return DB::transaction(function () use ($brand, $input): EquipmentBrand {
            if (isset($input['name']) && ! isset($input['slug'])) {
                $input['slug'] = Str::slug($input['name']);
            }
            $brand->fill($input);
            $brand->save();

            return $brand;
        });
    }
}
