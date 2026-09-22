<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
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
        return $user->hasPermission('roles.view');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermission('roles.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('roles.create');
    }

    public function update(User $user, Role $role): bool
    {
        if ($role->is_system) {
            return false;
        }

        return $user->hasPermission('roles.update');
    }

    public function delete(User $user, Role $role): bool
    {
        if ($role->is_system) {
            return false;
        }

        if ($role->userTenants()->exists()) {
            return false;
        }

        return $user->hasPermission('roles.delete');
    }

    public function syncPermissions(User $user, Role $role): bool
    {
        return $user->hasPermission('roles.update');
    }
}
