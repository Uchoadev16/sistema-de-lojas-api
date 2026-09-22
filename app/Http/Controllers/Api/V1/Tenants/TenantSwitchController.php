<?php

namespace App\Http\Controllers\Api\V1\Tenants;

use App\Actions\V1\Auth\IssueAuthTokenAction;
use App\Actions\V1\Tenants\SwitchTenantAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Tenants\SwitchTenantRequest;
use App\Http\Resources\V1\RoleResource;
use App\Http\Resources\V1\TenantResource;

class TenantSwitchController extends Controller
{
    public function store(SwitchTenantRequest $request, SwitchTenantAction $action, IssueAuthTokenAction $tokenAction)
    {
        $user = $request->user();
        $result = $action->execute($user, $request->input('tenant_id'), $request->input('device_id'));

        return $this->success([
            'tenant' => new TenantResource($result['tenant']),
            'role' => $result['role'] ? new RoleResource($result['role']) : null,
            'message' => 'Switched tenant context successfully.',
        ]);
    }
}
