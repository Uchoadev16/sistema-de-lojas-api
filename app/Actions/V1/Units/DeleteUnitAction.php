<?php

namespace App\Actions\V1\Units;

use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class DeleteUnitAction
{
    public function execute(Unit $unit): void
    {
        DB::transaction(function () use ($unit): void {
            $unit->environments()->delete();
            $unit->delete();
        });
    }
}
