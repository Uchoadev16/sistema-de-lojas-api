<?php

namespace App\Actions\V1\Roles;

use App\Models\Permission;
use App\Models\Role;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Facades\DB;

class CreateRoleAction
{
    public function execute(array $input): Role
    {
        $tenantId = app(TenantContext::class)->id();

        return DB::transaction(function () use ($input, $tenantId): Role {
            $role = Role::create([
                'tenant_id' => $tenantId,
                'name' => $input['name'],
                'slug' => $input['slug'],
                'description' => $input['description'] ?? null,
                'scope' => $input['scope'] ?? 'tenant',
                'is_system' => false,
                'is_active' => true,
                'sort_order' => $input['sort_order'] ?? 0,
                'color_hex' => $input['color_hex'] ?? null,
            ]);

            if (! empty($input['permission_ids'])) {
                $permissionIds = Permission::where('tenant_id', null)
                    ->orWhere('tenant_id', $tenantId)
                    ->whereIn('id', $input['permission_ids'])
                    ->pluck('id')
                    ->toArray();
                $role->permissions()->sync($permissionIds);
            }

            return $role->load('permissions');
        });
    }
}
