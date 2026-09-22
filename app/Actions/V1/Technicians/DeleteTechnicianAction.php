<?php

namespace App\Actions\V1\Technicians;

use App\Models\Technician;
use Illuminate\Support\Facades\DB;

class DeleteTechnicianAction
{
    public function execute(Technician $technician): void
    {
        DB::transaction(function () use ($technician): void {
            $technician->delete();
        });
    }
}
