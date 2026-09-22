<?php

namespace App\Policies;

use App\Models\Environment;
use App\Models\User;

class EnvironmentPolicy
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
        return $user->hasPermission('environments.view');
    }

    public function view(User $user, Environment $environment): bool
    {
        return $user->hasPermission('environments.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('environments.create');
    }

    public function update(User $user, Environment $environment): bool
    {
        return $user->hasPermission('environments.update');
    }

    public function delete(User $user, Environment $environment): bool
    {
        return $user->hasPermission('environments.delete');
    }
}
