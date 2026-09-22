<?php

namespace App\Actions\V1\Auth;

use App\Models\User;

class LogoutAllAction
{
    public function __construct(private RevokeAllUserTokensAction $revokeAll) {}

    public function execute(?User $user = null): int
    {
        $user ??= request()->user();

        return $this->revokeAll->execute(user: $user, revokeCurrent: true);
    }
}
