<?php

namespace App\Actions\V1\Roles;

use App\Models\Permission;
use App\Models\Role;
use App\Services\Rbac\PermissionChecker;
use App\Services\Tenancy\TenantContext;

class SyncRolePermissionsAction
{
    public function execute(Role $role, array $input): Role
    {
        $tenantId = $role->tenant_id ?: (app(TenantContext::class)->hasTenant() ? app(TenantContext::class)->id() : null);
        $permissionIds = Permission::query()
            ->where(fn ($q) => $q->where('tenant_id', null)->orWhere('tenant_id', $tenantId))
            ->whereIn('id', $input['permission_ids'] ?? [])
            ->pluck('id')
            ->toArray();

        $replace = (bool) ($input['replace_all'] ?? true);
        if ($replace) {
            $role->permissions()->sync($permissionIds);
        } else {
            $role->permissions()->syncWithoutDetaching($permissionIds);
        }

        $role->userTenants->each(function ($userTenant): void {
            if ($userTenant->user) {
                app(PermissionChecker::class)->flushCacheForUser($userTenant->user);
            }
        });

        return $role->load('permissions');
    }
}
