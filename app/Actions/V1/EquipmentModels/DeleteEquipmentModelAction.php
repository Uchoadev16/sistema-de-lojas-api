<?php

namespace App\Actions\V1\EquipmentModels;

use App\Models\EquipmentModel;
use Illuminate\Support\Facades\DB;

class DeleteEquipmentModelAction
{
    public function execute(EquipmentModel $model): void
    {
        DB::transaction(function () use ($model): void {
            $model->delete();
        });
    }
}
