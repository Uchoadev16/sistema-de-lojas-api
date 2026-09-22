<?php

namespace App\Policies;

use App\Models\CustomerContact;
use App\Models\User;

class CustomerContactPolicy
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
        return $user->hasPermission('customers.view');
    }

    public function view(User $user, CustomerContact $contact): bool
    {
        return $user->hasPermission('customers.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('customers.create');
    }

    public function update(User $user, CustomerContact $contact): bool
    {
        return $user->hasPermission('customers.update');
    }

    public function delete(User $user, CustomerContact $contact): bool
    {
        return $user->hasPermission('customers.delete');
    }
}
