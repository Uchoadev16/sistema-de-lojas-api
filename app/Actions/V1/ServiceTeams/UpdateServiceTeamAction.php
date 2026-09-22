<?php

namespace App\Actions\V1\ServiceTeams;

use App\Models\ServiceTeam;
use Illuminate\Support\Facades\DB;

class UpdateServiceTeamAction
{
    public function execute(ServiceTeam $team, array $input): ServiceTeam
    {
        return DB::transaction(function () use ($team, $input): ServiceTeam {
            $team->fill($input);
            $team->save();

            return $team;
        });
    }
}
