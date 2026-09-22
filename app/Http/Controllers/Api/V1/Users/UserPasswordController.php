<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Actions\V1\Users\ResetUserPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Users\ResetUserPasswordRequest;
use App\Models\User;

class UserPasswordController extends Controller
{
    public function reset(ResetUserPasswordRequest $request, User $user, ResetUserPasswordAction $action)
    {
        $result = $action->execute($user, $request->validated());

        return $this->success($result);
    }
}
