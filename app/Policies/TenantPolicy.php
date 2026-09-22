<?php

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;
use App\Support\Enums\UserTenantStatus;

class TenantPolicy
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
        return false;
    }

    public function view(User $user, Tenant $tenant): bool
    {
        return $user->userTenants()
            ->where('tenant_id', $tenant->id)
            ->where('status', UserTenantStatus::Active->value)
            ->where('is_active', true)
            ->exists();
    }

    public function create(User $user): bool
    {
        return $user->is_platform_admin;
    }

    public function update(User $user, Tenant $tenant): bool
    {
        return $user->userTenants()
            ->where('tenant_id', $tenant->id)
            ->where('status', UserTenantStatus::Active->value)
            ->where(fn ($q) => $q->where('is_owner', true)->orWhereHas('role', fn ($rq) => $rq->whereHas('permissions', fn ($pq) => $pq->where('slug', 'tenant.update'))))
            ->exists();
    }

    public function delete(User $user, Tenant $tenant): bool
    {
        return false;
    }

    public function switch(User $user, Tenant $tenant): bool
    {
        return $user->userTenants()
            ->where('tenant_id', $tenant->id)
            ->where('status', UserTenantStatus::Active->value)
            ->where('is_active', true)
            ->exists();
    }
}
