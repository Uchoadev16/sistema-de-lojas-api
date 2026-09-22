<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\V1\Auth\ResetPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    public function store(ResetPasswordRequest $request, ResetPasswordAction $action)
    {
        $result = $action->execute($request->validated());

        return $this->success($result, $result['success'] ? 200 : 422);
    }
}
