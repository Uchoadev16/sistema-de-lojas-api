<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
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
        return $user->hasPermission('users.view');
    }

    public function view(User $user, User $target): bool
    {
        if ($user->is($target)) {
            return true;
        }

        return $user->hasPermission('users.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('users.create');
    }

    public function update(User $user, User $target): bool
    {
        if ($user->is($target)) {
            return true;
        }

        return $user->hasPermission('users.update');
    }

    public function delete(User $user, User $target): bool
    {
        if ($user->is($target)) {
            return false;
        }

        return $user->hasPermission('users.delete');
    }

    public function invite(User $user): bool
    {
        return $user->hasPermission('users.invite');
    }

    public function resetPassword(User $user, User $target): bool
    {
        if ($user->is($target)) {
            return true;
        }

        return $user->hasPermission('users.reset_password');
    }

    public function changeStatus(User $user, User $target): bool
    {
        if ($user->is($target)) {
            return false;
        }

        return $user->hasPermission('users.update_status');
    }
}
