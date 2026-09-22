<?php

namespace App\Actions\V1\Units;

use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class UpdateUnitAction
{
    public function execute(Unit $unit, array $input): Unit
    {
        return DB::transaction(function () use ($unit, $input): Unit {
            $unit->update($input);

            return $unit->load(['customer', 'address', 'environments', 'responsibleContact']);
        });
    }
}
