<?php

namespace App\Policies;

use App\Models\EquipmentBrand;
use App\Models\User;

class EquipmentBrandPolicy
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
        return $user->hasPermission('equipment_brands.view');
    }

    public function view(User $user, EquipmentBrand $brand): bool
    {
        return $user->hasPermission('equipment_brands.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('equipment_brands.create');
    }

    public function update(User $user, EquipmentBrand $brand): bool
    {
        return $user->hasPermission('equipment_brands.update');
    }

    public function delete(User $user, EquipmentBrand $brand): bool
    {
        return $user->hasPermission('equipment_brands.delete');
    }
}
