<?php

namespace App\Actions\V1\Technicians;

use App\Models\Technician;
use Illuminate\Support\Facades\DB;

class RestoreTechnicianAction
{
    public function execute(Technician $technician): Technician
    {
        return DB::transaction(function () use ($technician): Technician {
            $technician->restore();

            return $technician;
        });
    }
}
