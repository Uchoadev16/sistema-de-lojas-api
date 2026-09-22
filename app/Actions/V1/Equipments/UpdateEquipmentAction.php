<?php

namespace App\Actions\V1\Equipments;

use App\Models\Equipment;
use Illuminate\Support\Facades\DB;

class UpdateEquipmentAction
{
    public function execute(Equipment $equipment, array $input): Equipment
    {
        return DB::transaction(function () use ($equipment, $input): Equipment {
            $equipment->fill($input);
            $equipment->save();

            return $equipment;
        });
    }
}
