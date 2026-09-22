<?php

namespace App\Actions\V1\ServiceTeams;

use App\Models\ServiceTeam;
use Illuminate\Support\Facades\DB;

class RestoreServiceTeamAction
{
    public function execute(ServiceTeam $team): ServiceTeam
    {
        return DB::transaction(function () use ($team): ServiceTeam {
            $team->restore();

            return $team;
        });
    }
}
