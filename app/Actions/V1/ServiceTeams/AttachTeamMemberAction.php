<?php

namespace App\Actions\V1\ServiceTeams;

use App\Models\ServiceTeam;
use Illuminate\Support\Facades\DB;

class AttachTeamMemberAction
{
    public function execute(ServiceTeam $team, array $input): ServiceTeam
    {
        return DB::transaction(function () use ($team, $input): ServiceTeam {
            $technicianIds = $input['technician_ids'] ?? (isset($input['technician_id']) ? [$input['technician_id']] : []);
            $isLead = $input['is_lead'] ?? false;
            $joinedAt = $input['joined_at'] ?? now()->toDateString();
            $notes = $input['notes'] ?? null;

            $pivotData = [];
            foreach ($technicianIds as $techId) {
                $pivotData[$techId] = [
                    'joined_at' => $joinedAt,
                    'is_lead' => (bool) $isLead,
                    'notes' => $notes,
                ];
            }

            $team->technicians()->syncWithoutDetaching($pivotData);

            return $team->load('technicians');
        });
    }
}
