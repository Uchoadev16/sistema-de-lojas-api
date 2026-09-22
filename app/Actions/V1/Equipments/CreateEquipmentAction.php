<?php

namespace App\Actions\V1\Equipments;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateEquipmentAction
{
    public function execute(array $input, ?User $currentUser = null): Equipment
    {
        $currentUser ??= request()->user();

        return DB::transaction(function () use ($input, $currentUser): Equipment {
            $input['created_by_user_id'] = $currentUser?->id;

            return Equipment::create($input);
        });
    }
}
