<?php

namespace App\Policies;

use App\Models\EquipmentCategory;
use App\Models\User;

class EquipmentCategoryPolicy
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
        return $user->hasPermission('equipment_categories.view');
    }

    public function view(User $user, EquipmentCategory $category): bool
    {
        return $user->hasPermission('equipment_categories.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('equipment_categories.create');
    }

    public function update(User $user, EquipmentCategory $category): bool
    {
        return $user->hasPermission('equipment_categories.update');
    }

    public function delete(User $user, EquipmentCategory $category): bool
    {
        return $user->hasPermission('equipment_categories.delete');
    }
}
