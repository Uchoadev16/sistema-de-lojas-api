<?php

namespace App\Actions\V1\Units;

use App\Models\Unit;

class RestoreUnitAction
{
    public function execute(Unit $unit): Unit
    {
        if (! $unit->trashed()) {
            abort(400, 'Unit not soft-deleted');
        }
        $unit->restore();

        return $unit->load(['customer', 'address', 'responsibleContact'])->loadCount(['environments', 'equipments']);
    }
}
