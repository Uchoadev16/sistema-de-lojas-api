<?php

namespace App\Actions\V1\Equipments;

use App\Models\Equipment;
use Illuminate\Support\Facades\DB;

class RestoreEquipmentAction
{
    public function execute(Equipment $equipment): Equipment
    {
        return DB::transaction(function () use ($equipment): Equipment {
            $equipment->restore();

            return $equipment;
        });
    }
}
