<?php

namespace App\Actions\V1\Technicians;

use App\Models\Technician;
use Illuminate\Support\Facades\DB;

class UpdateTechnicianAction
{
    public function execute(Technician $technician, array $input): Technician
    {
        return DB::transaction(function () use ($technician, $input): Technician {
            $technician->fill($input);
            $technician->save();

            return $technician;
        });
    }
}
