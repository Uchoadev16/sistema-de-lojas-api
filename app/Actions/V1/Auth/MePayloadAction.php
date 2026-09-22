<?php

namespace App\Actions\V1\Auth;

use App\Http\Resources\V1\RoleResource;
use App\Http\Resources\V1\TenantResource;
use App\Http\Resources\V1\UserResource;
use App\Models\Permission;
use App\Models\User;
use App\Services\Tenancy\TenantContext;
use App\Support\Enums\PermissionEffect;
use App\Support\Enums\UserTenantStatus;

class MePayloadAction
{
    public function __construct(private TenantContext $tenantContext) {}

    public function execute(?User $user = null): array
    {
        $user ??= request()->user();
        $tenant = $this->tenantContext->hasTenant() ? $this->tenantContext->tenant() : null;
        $userTenant = $tenant ? $user?->userTenants()
            ->with(['tenant', 'role.permissions', 'userPermissions.permission'])
            ->where('tenant_id', $tenant->id)
            ->where('status', UserTenantStatus::Active->value)
            ->where('is_active', true)
            ->first() : null;
        $role = $userTenant?->role;
        $permissions = $this->resolvePermissions($user, $userTenant);

        return [
            'user' => new UserResource($user),
            'tenant' => $tenant ? new TenantResource($tenant) : null,
            'role' => $role ? new RoleResource($role) : null,
            'permissions' => $permissions,
        ];
    }

    private function resolvePermissions(?User $user, $userTenant): array
    {
        if (! $user) {
            return [];
        }

        if ($user->is_platform_admin) {
            return Permission::query()
                ->whereNull('tenant_id')
                ->where('is_active', true)
                ->pluck('slug')
                ->all();
        }

        if (! $userTenant) {
            return [];
        }

        $rolePermissions = collect($userTenant->role?->permissions ?? [])
            ->pluck('slug');

        $userPermissions = collect($userTenant->userPermissions ?? [])
            ->filter(fn ($up) => $up->effect === PermissionEffect::Allow && $up->permission)
            ->map(fn ($up) => $up->permission->slug);

        return $rolePermissions
            ->concat($userPermissions)
            ->unique()
            ->values()
            ->all();
    }
}
