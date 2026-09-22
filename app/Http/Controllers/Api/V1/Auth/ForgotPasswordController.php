<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\V1\Auth\SendPasswordResetLinkAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    public function store(ForgotPasswordRequest $request, SendPasswordResetLinkAction $action)
    {
        $result = $action->execute($request->validated());

        return $this->success($result, $result['success'] ? 200 : 202);
    }
}
