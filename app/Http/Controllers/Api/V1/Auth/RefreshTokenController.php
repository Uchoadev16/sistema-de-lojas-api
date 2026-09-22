<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\V1\Auth\RefreshTokenAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AuthTokenResource;
use App\Services\Tenancy\TenantContext;
use App\Support\Enums\UserTenantStatus;

class RefreshTokenController extends Controller
{
    public function store(RefreshTokenAction $refreshAction)
    {
        $result = $refreshAction->execute();
        $user = $result['user'];
        $plainTextToken = $result['token'];

        $activeTenants = $user->userTenants()
            ->with(['tenant', 'role'])
            ->where('status', UserTenantStatus::Active->value)
            ->where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->get();
        $defaultUT = $activeTenants->firstWhere('is_default', true) ?? $activeTenants->first();
        if ($defaultUT && ! app(TenantContext::class)->hasTenant()) {
            app(TenantContext::class)->set($defaultUT->tenant);
        }

        $resource = new AuthTokenResource([
            'plain_text_token' => $plainTextToken,
            'token' => $plainTextToken,
            'user' => $user,
            'tenant' => $defaultUT?->tenant,
            'role' => $defaultUT?->role,
        ]);

        return $this->success($resource->toArray(request()), 200);
    }
}
