<?php

namespace App\Actions\V1\Users;

use App\Events\Auth\UserActivated;
use App\Models\User;
use App\Services\Rbac\PermissionChecker;
use App\Services\Tenancy\TenantContext;
use App\Support\Enums\UserTenantStatus;

class ActivateUserAction
{
    public function execute(User $targetUser): array
    {
        $tenantId = app(TenantContext::class)->id();

        $userTenant = $targetUser->userTenants()
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        $userTenant->update([
            'status' => UserTenantStatus::Active->value,
            'is_active' => true,
        ]);

        app(PermissionChecker::class)->flushCacheForUser($targetUser);

        event(new UserActivated($targetUser, $tenantId));

        return [
            'status' => 'activated',
            'user_tenant_status' => UserTenantStatus::Active->value,
        ];
    }
}
