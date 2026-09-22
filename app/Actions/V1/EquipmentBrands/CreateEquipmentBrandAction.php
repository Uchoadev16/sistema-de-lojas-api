<?php

namespace App\Actions\V1\EquipmentBrands;

use App\Models\EquipmentBrand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateEquipmentBrandAction
{
    public function execute(array $input): EquipmentBrand
    {
        return DB::transaction(function () use ($input): EquipmentBrand {
            $input['slug'] = $input['slug'] ?? Str::slug($input['name']);

            return EquipmentBrand::create($input);
        });
    }
}
