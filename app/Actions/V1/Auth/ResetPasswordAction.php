<?php

namespace App\Actions\V1\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordAction
{
    public function execute(array $input): array
    {
        $status = Password::reset(
            [
                'email' => $input['email'],
                'password' => $input['password'],
                'password_confirmation' => $input['password_confirmation'] ?? $input['password'],
                'token' => $input['token'],
            ],
            function (User $user) use ($input): void {
                $user->forceFill([
                    'password' => Hash::make($input['password']),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                $revoke = app(RevokeAllUserTokensAction::class);
                $revoke->execute($user);
            }
        );

        $success = $status === Password::PASSWORD_RESET;

        return [
            'status' => $status,
            'success' => $success,
            'message' => __($status),
        ];
    }
}
