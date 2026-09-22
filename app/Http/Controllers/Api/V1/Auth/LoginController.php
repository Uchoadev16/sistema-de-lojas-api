<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\V1\Auth\AuthenticateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Resources\V1\AuthTokenResource;

class LoginController extends Controller
{
    public function store(LoginRequest $request, AuthenticateUserAction $authAction)
    {
        $result = $authAction->execute($request, $request->ip(), $request->userAgent());

        $resource = new AuthTokenResource($result);
        $status = isset($result['mfa_required']) && $result['mfa_required'] ? 202 : 200;

        return $this->success($resource->toResponse($request)->getData(true), $status);
    }
}
