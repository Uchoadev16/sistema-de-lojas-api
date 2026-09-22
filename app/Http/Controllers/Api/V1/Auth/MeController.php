<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\V1\Auth\MePayloadAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\MfaMethodResource;
use App\Http\Resources\V1\TenantResource;
use App\Support\Enums\UserTenantStatus;

class MeController extends Controller
{
    public function show(MePayloadAction $action)
    {
        $user = request()->user()
            ->load([
                'userTenants' => function ($q): void {
                    $q->where('status', UserTenantStatus::Active->value)
                        ->where('is_active', true)
                        ->with(['tenant', 'role.permissions']);
                },
                'mfaMethods',
            ]);

        $payload = $action->execute($user);

        $payload['tenants'] = TenantResource::collection(
            $user->userTenants->pluck('tenant')->filter()->values()
        );

        $payload['mfa_methods'] = MfaMethodResource::collection($user->mfaMethods);

        return $this->success($payload);
    }
}
