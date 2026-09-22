<?php

namespace App\Policies;

use App\Models\EquipmentModel;
use App\Models\User;

class EquipmentModelPolicy
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
        return $user->hasPermission('equipment_models.view');
    }

    public function view(User $user, EquipmentModel $model): bool
    {
        return $user->hasPermission('equipment_models.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('equipment_models.create');
    }

    public function update(User $user, EquipmentModel $model): bool
    {
        return $user->hasPermission('equipment_models.update');
    }

    public function delete(User $user, EquipmentModel $model): bool
    {
        return $user->hasPermission('equipment_models.delete');
    }
}
