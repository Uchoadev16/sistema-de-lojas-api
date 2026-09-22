<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
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

    public function view(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customers.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('customers.create');
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customers.update');
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customers.delete');
    }

    public function restore(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customers.delete');
    }

    public function createContact(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customers.update');
    }

    public function attachTag(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customers.update');
    }

    public function detachTag(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customers.update');
    }
}
