<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Actions\V1\Users\ActivateUserAction;
use App\Actions\V1\Users\DeactivateUserAction;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserStatusController extends Controller
{
    public function deactivate(User $user, DeactivateUserAction $action)
    {
        $this->authorize('changeStatus', $user);

        $result = $action->execute($user);

        return $this->success($result);
    }

    public function activate(User $user, ActivateUserAction $action)
    {
        $this->authorize('changeStatus', $user);

        $result = $action->execute($user);

        return $this->success($result);
    }
}
