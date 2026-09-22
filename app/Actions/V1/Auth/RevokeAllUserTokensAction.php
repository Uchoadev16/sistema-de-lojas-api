<?php

namespace App\Actions\V1\Auth;

use App\Events\Auth\UserSessionsRevoked;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\Contracts\HasApiTokens;

class RevokeAllUserTokensAction
{
    public function execute(User $user, ?string $exceptTokenId = null, ?User $currentUser = null, bool $revokeCurrent = false): int
    {
        $currentUser ??= request()->user();
        if ($exceptTokenId === null && $currentUser && ! $revokeCurrent) {
            $exceptTokenId = $currentUser?->currentAccessToken()?->getKey();
        }

        $query = $user->tokens();
        if ($exceptTokenId) {
            $query->where('id', '!=', $exceptTokenId);
        }

        $count = $query->delete();

        event(new UserSessionsRevoked($user, $exceptTokenId));

        return $count;
    }

    public function revokeCurrent(User $user, $request = null, ?User $currentUser = null): bool
    {
        $currentUser ??= request()->user();
        if (! $request instanceof Request) {
            $request = request();
        }

        /** @var HasApiTokens $user */
        $token = $currentUser?->currentAccessToken() ?? $request->user()?->currentAccessToken();
        if ($token) {
            $token->delete();

            return true;
        }

        return false;
    }
}
