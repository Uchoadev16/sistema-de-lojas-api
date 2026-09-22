<?php

namespace App\Actions\V1\Roles;

use App\Models\Permission;
use App\Models\Role;
use App\Services\Rbac\PermissionChecker;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Facades\DB;

class UpdateRoleAction
{
    public function execute(Role $role, array $input): Role
    {
        return DB::transaction(function () use ($role, $input): Role {
            $role->fill([
                'name' => $input['name'] ?? $role->name,
                'slug' => $input['slug'] ?? $role->slug,
                'description' => array_key_exists('description', $input) ? $input['description'] : $role->description,
                'color_hex' => array_key_exists('color_hex', $input) ? $input['color_hex'] : $role->color_hex,
                'sort_order' => array_key_exists('sort_order', $input) ? (int) $input['sort_order'] : $role->sort_order,
            ]);

            if (isset($input['is_active'])) {
                $role->is_active = (bool) $input['is_active'];
            }

            $role->save();

            if (isset($input['permission_ids'])) {
                $tenantId = $role->tenant_id ?: (app(TenantContext::class)->hasTenant() ? app(TenantContext::class)->id() : null);
                $permissionIds = Permission::query()
                    ->where(fn ($q) => $q->where('tenant_id', null)->orWhere('tenant_id', $tenantId))
                    ->whereIn('id', $input['permission_ids'])
                    ->pluck('id')
                    ->toArray();
                $role->permissions()->sync($permissionIds);
            }

            $role->userTenants->each(function ($userTenant): void {
                if ($userTenant->user) {
                    app(PermissionChecker::class)->flushCacheForUser($userTenant->user);
                }
            });

            return $role->load('permissions');
        });
    }
}
