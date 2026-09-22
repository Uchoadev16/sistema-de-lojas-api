<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\V1\Auth\LogoutCurrentAction;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    public function __invoke() {}

    public function destroy(LogoutCurrentAction $action)
    {
        $user = request()->user();
        $currentTokenId = $user?->currentAccessToken()?->getKey();

        $revoked = 0;
        if ($currentTokenId) {
            try {
                $action->execute($user);
                $revoked = 1;
            } catch (\Throwable) {
            }
        }

        try {
            $request = request();
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        } catch (\Throwable) {
        }

        return $this->success([
            'logged_out' => true,
            'tokens_revoked' => $revoked,
        ], 200);
    }
}
