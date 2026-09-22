<?php

namespace App\Http\Controllers\Api\V1\Roles;

use App\Actions\V1\Roles\SyncRolePermissionsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Roles\SyncRolePermissionsRequest;
use App\Http\Resources\V1\PermissionResource;
use App\Http\Resources\V1\RoleResource;
use App\Models\Role;

class RolePermissionController extends Controller
{
    public function sync(SyncRolePermissionsRequest $request, Role $role, SyncRolePermissionsAction $action)
    {
        $this->authorize('syncPermissions', $role);

        $updated = $action->execute($role, $request->validated());

        return $this->success([
            'role' => new RoleResource($updated),
            'permissions' => PermissionResource::collection($updated->permissions),
        ]);
    }
}
