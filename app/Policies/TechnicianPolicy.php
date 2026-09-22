<?php

namespace App\Policies;

use App\Models\Technician;
use App\Models\User;

class TechnicianPolicy
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
        return $user->hasPermission('technicians.view');
    }

    public function view(User $user, Technician $technician): bool
    {
        return $user->hasPermission('technicians.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('technicians.create');
    }

    public function update(User $user, Technician $technician): bool
    {
        return $user->hasPermission('technicians.update');
    }

    public function delete(User $user, Technician $technician): bool
    {
        return $user->hasPermission('technicians.delete');
    }

    public function restore(User $user, Technician $technician): bool
    {
        return $user->hasPermission('technicians.restore');
    }
}
