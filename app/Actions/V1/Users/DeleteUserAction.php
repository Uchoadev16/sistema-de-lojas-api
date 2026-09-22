<?php

namespace App\Actions\V1\Users;

use App\Exceptions\CannotRemoveLastOwnerException;
use App\Models\User;
use App\Models\UserTenant;
use App\Services\Rbac\PermissionChecker;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Facades\DB;

class DeleteUserAction
{
    public function execute(User $targetUser): bool
    {
        $tenantId = app(TenantContext::class)->id();

        return DB::transaction(function () use ($targetUser, $tenantId): bool {
            $userTenant = $targetUser->userTenants()
                ->where('tenant_id', $tenantId)
                ->first();

            if (! $userTenant) {
                return false;
            }

            if ($userTenant->is_owner) {
                $ownerCount = UserTenant::where('tenant_id', $tenantId)
                    ->where('is_owner', true)
                    ->count();
                if ($ownerCount <= 1) {
                    throw new CannotRemoveLastOwnerException(
                        'Cannot remove the last owner of this tenant.'
                    );
                }
            }

            $userTenant->delete();

            $activeTenantsCount = $targetUser->userTenants()->count();
            if ($activeTenantsCount === 0 && ! $targetUser->is_platform_admin) {
                $targetUser->delete();
            }

            app(PermissionChecker::class)->flushCacheForUser($targetUser);

            return true;
        });
    }
}
