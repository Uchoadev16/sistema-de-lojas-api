<?php

namespace App\Actions\V1\EquipmentModels;

use App\Models\EquipmentModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateEquipmentModelAction
{
    public function execute(array $input): EquipmentModel
    {
        return DB::transaction(function () use ($input): EquipmentModel {
            $input['slug'] = $input['slug'] ?? Str::slug($input['name']);

            return EquipmentModel::create($input);
        });
    }
}
