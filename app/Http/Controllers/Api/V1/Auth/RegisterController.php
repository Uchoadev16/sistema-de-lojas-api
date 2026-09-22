<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\V1\Auth\IssueAuthTokenAction;
use App\Actions\V1\Auth\RegisterTenantAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Resources\V1\AuthTokenResource;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request, RegisterTenantAction $registerAction, IssueAuthTokenAction $tokenAction)
    {
        $result = $registerAction->execute($request->validated(), $request->ip(), $request->userAgent());

        $user = $result['user'];
        $tenant = $result['tenant'];

        $token = $tokenAction->execute($user, [
            'device_name' => $request->input('device_name') ?: 'web-register',
            'device_platform' => $request->input('device_platform'),
            'expires_at' => now()->addHours(12),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $resource = new AuthTokenResource([
            ...$token,
            'user' => $user,
            'tenant' => $tenant,
            'role' => $result['role'],
            'available_tenants' => [$tenant],
        ]);

        return $this->success($resource->toResponse($request)->getData(true), 201);
    }
}
