<?php

namespace App\Policies;

use App\Models\ServiceTeam;
use App\Models\User;

class ServiceTeamPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('service_teams.view');
    }

    public function view(User $user, ServiceTeam $team): bool
    {
        return $user->hasPermission('service_teams.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('service_teams.create');
    }

    public function update(User $user, ServiceTeam $team): bool
    {
        return $user->hasPermission('service_teams.update');
    }

    public function delete(User $user, ServiceTeam $team): bool
    {
        return $user->hasPermission('service_teams.delete');
    }

    public function restore(User $user, ServiceTeam $team): bool
    {
        return $user->hasPermission('service_teams.restore');
    }

    public function attachMember(User $user, ServiceTeam $team): bool
    {
        return $user->hasPermission('service_teams.update');
    }

    public function detachMember(User $user, ServiceTeam $team): bool
    {
        return $user->hasPermission('service_teams.update');
    }
}
