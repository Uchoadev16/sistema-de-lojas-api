<?php

namespace App\Actions\V1\Tenants;

use App\Models\User;
use App\Services\Tenancy\TenantContext;
use App\Support\Enums\UserTenantStatus;

class SwitchTenantAction
{
    public function execute(User $user, string $tenantId, ?string $deviceId = null): array
    {
        $userTenant = $user->userTenants()
            ->with(['tenant', 'role'])
            ->where('tenant_id', $tenantId)
            ->where('status', UserTenantStatus::Active->value)
            ->where('is_active', true)
            ->firstOrFail();

        UserTenant::where('user_id', $user->id)
            ->where('id', '!=', $userTenant->id)
            ->update(['is_default' => false]);

        if (! $userTenant->is_default) {
            $userTenant->is_default = true;
            $userTenant->saveQuietly();
        }

        $tenant = $userTenant->tenant;
        $context = app(TenantContext::class);
        $context->set($tenant);

        return [
            'tenant' => $tenant,
            'role' => $userTenant->role,
            'userTenant' => $userTenant,
        ];
    }
}
