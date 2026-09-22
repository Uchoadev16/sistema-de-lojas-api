<?php

namespace App\Actions\V1\Users;

use App\Events\Auth\UserDeactivated;
use App\Models\User;
use App\Services\Rbac\PermissionChecker;
use App\Services\Tenancy\TenantContext;
use App\Support\Enums\UserTenantStatus;

class DeactivateUserAction
{
    public function execute(User $targetUser): array
    {
        $tenantId = app(TenantContext::class)->id();

        $userTenant = $targetUser->userTenants()
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        $userTenant->update([
            'status' => UserTenantStatus::Suspended->value,
            'is_active' => false,
        ]);

        $targetUser->tokens()->delete();
        app(PermissionChecker::class)->flushCacheForUser($targetUser);

        event(new UserDeactivated($targetUser, $tenantId));

        return [
            'status' => 'deactivated',
            'user_tenant_status' => UserTenantStatus::Suspended->value,
            'tokens_revoked' => true,
        ];
    }
}
