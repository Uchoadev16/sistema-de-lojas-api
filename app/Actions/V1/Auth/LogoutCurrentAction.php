<?php

namespace App\Actions\V1\Auth;

use App\Models\User;

class LogoutCurrentAction
{
    public function execute(?User $user = null): void
    {
        $user ??= request()->user();
        $current = $user?->currentAccessToken();
        if ($current) {
            $current->delete();
        }
    }
}
