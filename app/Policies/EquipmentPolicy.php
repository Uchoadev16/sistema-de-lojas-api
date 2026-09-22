<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;

class EquipmentPolicy
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
        return $user->hasPermission('equipments.view');
    }

    public function view(User $user, Equipment $equipment): bool
    {
        return $user->hasPermission('equipments.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('equipments.create');
    }

    public function update(User $user, Equipment $equipment): bool
    {
        return $user->hasPermission('equipments.update');
    }

    public function delete(User $user, Equipment $equipment): bool
    {
        return $user->hasPermission('equipments.delete');
    }

    public function restore(User $user, Equipment $equipment): bool
    {
        return $user->hasPermission('equipments.restore');
    }
}
