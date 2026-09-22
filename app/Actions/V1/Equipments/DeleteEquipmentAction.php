<?php

namespace App\Actions\V1\Equipments;

use App\Models\Equipment;
use Illuminate\Support\Facades\DB;

class DeleteEquipmentAction
{
    public function execute(Equipment $equipment): void
    {
        DB::transaction(function () use ($equipment): void {
            $equipment->delete();
        });
    }
}
