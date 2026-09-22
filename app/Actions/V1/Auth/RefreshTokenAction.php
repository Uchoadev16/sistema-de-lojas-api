<?php

namespace App\Actions\V1\Auth;

use App\Models\User;
use Illuminate\Support\Str;

class RefreshTokenAction
{
    public function execute(?User $user = null): array
    {
        $user ??= request()->user();
        $user?->currentAccessToken()?->delete();
        $token = $user?->createToken('auth:'.Str::slug($user?->email ?? 'unknown'), ['*']);

        return [
            'token' => $token?->plainTextToken,
            'user' => $user,
        ];
    }
}
