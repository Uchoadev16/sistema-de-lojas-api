<?php

namespace App\Actions\V1\ServiceTeams;

use App\Models\ServiceTeam;
use Illuminate\Support\Facades\DB;

class DetachTeamMemberAction
{
    public function execute(ServiceTeam $team, array $input): ServiceTeam
    {
        return DB::transaction(function () use ($team, $input): ServiceTeam {
            $technicianIds = $input['technician_ids'] ?? [];
            $leftAt = $input['left_at'] ?? now()->toDateString();

            if (! empty($technicianIds)) {
                DB::table('service_team_members')
                    ->where('service_team_id', $team->id)
                    ->whereIn('technician_id', $technicianIds)
                    ->update(['left_at' => $leftAt]);
            }

            $team->technicians()->detach($technicianIds);

            return $team->loadMissing(['technicians', 'leader']);
        });
    }
}
