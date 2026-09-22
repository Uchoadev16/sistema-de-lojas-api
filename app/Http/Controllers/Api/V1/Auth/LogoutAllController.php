<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\V1\Auth\LogoutAllAction;
use App\Http\Controllers\Controller;

class LogoutAllController extends Controller
{
    public function destroy(LogoutAllAction $action)
    {
        $user = request()->user();
        $count = $action->execute($user);

        try {
            $request = request();
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        } catch (\Throwable) {
        }

        return $this->success([
            'logged_out_all' => true,
            'tokens_revoked' => $count,
        ], 200);
    }
}
