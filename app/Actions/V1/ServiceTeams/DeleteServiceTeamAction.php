<?php

namespace App\Actions\V1\ServiceTeams;

use App\Models\ServiceTeam;
use Illuminate\Support\Facades\DB;

class DeleteServiceTeamAction
{
    public function execute(ServiceTeam $team): void
    {
        DB::transaction(function () use ($team): void {
            $team->delete();
        });
    }
}
