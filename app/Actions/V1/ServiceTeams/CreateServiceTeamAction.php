<?php

namespace App\Actions\V1\ServiceTeams;

use App\Models\ServiceTeam;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateServiceTeamAction
{
    public function execute(array $input, ?User $currentUser = null): ServiceTeam
    {
        $currentUser ??= request()->user();

        return DB::transaction(function () use ($input, $currentUser): ServiceTeam {
            $input['created_by_user_id'] = $currentUser?->id;
            $team = ServiceTeam::create($input);

            if (! empty($input['technician_ids'])) {
                $pivotData = [];
                foreach ($input['technician_ids'] as $techId) {
                    $pivotData[$techId] = [
                        'joined_at' => $input['joined_at'] ?? now()->toDateString(),
                        'is_lead' => false,
                        'notes' => $input['notes'] ?? null,
                    ];
                }
                $team->technicians()->syncWithoutDetaching($pivotData);
            }

            return $team;
        });
    }
}
