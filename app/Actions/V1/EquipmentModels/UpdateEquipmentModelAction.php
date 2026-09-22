<?php

namespace App\Actions\V1\EquipmentModels;

use App\Models\EquipmentModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateEquipmentModelAction
{
    public function execute(EquipmentModel $model, array $input): EquipmentModel
    {
        return DB::transaction(function () use ($model, $input): EquipmentModel {
            if (isset($input['name']) && ! isset($input['slug'])) {
                $input['slug'] = Str::slug($input['name']);
            }
            $model->fill($input);
            $model->save();

            return $model;
        });
    }
}
