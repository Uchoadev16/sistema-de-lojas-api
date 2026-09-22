<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;

class AddressPolicy
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
        return $user->hasPermission('addresses.view');
    }

    public function view(User $user, Address $address): bool
    {
        return $user->hasPermission('addresses.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('addresses.create');
    }

    public function update(User $user, Address $address): bool
    {
        return $user->hasPermission('addresses.update');
    }

    public function delete(User $user, Address $address): bool
    {
        return $user->hasPermission('addresses.delete');
    }
}
