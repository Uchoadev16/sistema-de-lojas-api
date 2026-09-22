<?php

namespace App\Actions\V1\Roles;

use App\Exceptions\RoleInUseException;
use App\Models\Role;
use App\Services\Rbac\PermissionChecker;
use Illuminate\Support\Facades\DB;

class DeleteRoleAction
{
    public function execute(Role $role): bool
    {
        return DB::transaction(function () use ($role): bool {
            $inUseCount = $role->userTenants()->count();
            if ($inUseCount > 0) {
                throw new RoleInUseException('Role is in use by users in this tenant.');
            }

            $role->permissions()->detach();

            $role->userTenants->each(function ($userTenant): void {
                if ($userTenant->user) {
                    app(PermissionChecker::class)->flushCacheForUser($userTenant->user);
                }
            });

            $role->delete();

            return true;
        });
    }
}
