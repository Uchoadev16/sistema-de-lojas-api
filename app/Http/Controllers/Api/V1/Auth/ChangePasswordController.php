<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\V1\Auth\ChangePasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    public function store(ChangePasswordRequest $request, ChangePasswordAction $action)
    {
        $result = $action->execute($request->validated(), $request->user());

        return $this->success($result);
    }
}
