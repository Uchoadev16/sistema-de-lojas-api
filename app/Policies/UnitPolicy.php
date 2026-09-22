<?php

namespace App\Policies;

use App\Models\Unit;
use App\Models\User;

class UnitPolicy
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
        return $user->hasPermission('units.view');
    }

    public function view(User $user, Unit $unit): bool
    {
        return $user->hasPermission('units.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('units.create');
    }

    public function update(User $user, Unit $unit): bool
    {
        return $user->hasPermission('units.update');
    }

    public function delete(User $user, Unit $unit): bool
    {
        return $user->hasPermission('units.delete');
    }

    public function restore(User $user, Unit $unit): bool
    {
        return $user->hasPermission('units.delete');
    }
}
