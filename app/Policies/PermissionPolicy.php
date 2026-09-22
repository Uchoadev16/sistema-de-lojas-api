<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy
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
        return $user->hasPermission('permissions.view')
            || $user->hasPermission('roles.view')
            || $user->hasPermission('roles.create')
            || $user->hasPermission('roles.update');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $this->viewAny($user);
    }
}
