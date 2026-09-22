<?php

namespace App\Actions\V1\Technicians;

use App\Models\Technician;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateTechnicianAction
{
    public function execute(array $input, ?User $currentUser = null): Technician
    {
        $currentUser ??= request()->user();

        return DB::transaction(function () use ($input, $currentUser): Technician {
            $input['created_by_user_id'] = $currentUser?->id;

            return Technician::create($input);
        });
    }
}
