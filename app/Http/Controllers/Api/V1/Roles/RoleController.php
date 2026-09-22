<?php

namespace App\Http\Controllers\Api\V1\Roles;

use App\Actions\V1\Roles\CreateRoleAction;
use App\Actions\V1\Roles\DeleteRoleAction;
use App\Actions\V1\Roles\UpdateRoleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Roles\StoreRoleRequest;
use App\Http\Requests\V1\Roles\UpdateRoleRequest;
use App\Http\Resources\V1\RoleResource;
use App\Models\Role;
use App\Services\Tenancy\TenantContext;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Role::class);

        $tenantId = app(TenantContext::class)->id();
        $query = Role::query()
            ->withCount(['userTenants', 'permissions'])
            ->with('permissions')
            ->where(fn ($q) => $q->where('tenant_id', $tenantId)->orWhereNull('tenant_id'));

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'ILIKE', "%{$s}%")->orWhere('slug', 'ILIKE', "%{$s}%"));
        }

        if ($request->filled('is_system')) {
            $query->where('is_system', (bool) $request->input('is_system'));
        }

        $roles = $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->get();

        return $this->success(['data' => RoleResource::collection($roles)]);
    }

    public function store(StoreRoleRequest $request, CreateRoleAction $action)
    {
        $role = $action->execute($request->validated());

        return $this->success(['role' => new RoleResource($role)], 201);
    }

    public function show(Role $role)
    {
        $this->authorize('view', $role);

        $role->load(['permissions', 'userTenants'])->loadCount('permissions');

        return $this->success(['role' => new RoleResource($role)]);
    }

    public function update(UpdateRoleRequest $request, Role $role, UpdateRoleAction $action)
    {
        $updated = $action->execute($role, $request->validated());

        return $this->success(['role' => new RoleResource($updated)]);
    }

    public function destroy(Role $role, DeleteRoleAction $action)
    {
        $this->authorize('delete', $role);

        $action->execute($role);

        return $this->success([], 204);
    }
}
