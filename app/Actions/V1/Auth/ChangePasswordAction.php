<?php

namespace App\Actions\V1\Auth;

use App\Events\Auth\UserPasswordChanged;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChangePasswordAction
{
    public function execute(array $input, User $user, ?User $currentUser = null): array
    {
        $currentUser ??= request()->user();

        return DB::transaction(function () use ($input, $user, $currentUser): array {
            $user->password = Hash::make($input['password']);
            $user->save();

            $logoutOther = (bool) ($input['logout_other_devices'] ?? false);
            $otherSessionsLoggedOut = false;
            if ($logoutOther) {
                $tokenId = $currentUser?->currentAccessToken()?->getKey();
                app(RevokeAllUserTokensAction::class)->execute($user, $tokenId);
                $otherSessionsLoggedOut = true;
            }

            event(new UserPasswordChanged($user, $logoutOther));

            return ['changed' => true, 'other_sessions_logged_out' => $otherSessionsLoggedOut];
        });
    }
}
