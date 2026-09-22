<?php

namespace App\Actions\V1\Auth;

use App\Notifications\Auth\ResetPasswordNotification;
use Illuminate\Support\Facades\Password;

class SendPasswordResetLinkAction
{
    public function execute(array $input): array
    {
        $email = $input['email'];
        $credentials = ['email' => $email];

        $status = Password::sendResetLink(
            $credentials,
            function ($user, $token) use ($input): void {
                try {
                    $user->notify(new ResetPasswordNotification(
                        $token,
                        $user,
                        $input['return_url'] ?? null,
                        $input['locale'] ?? null,
                    ));
                } catch (\Throwable) {
                }
            }
        );

        return [
            'status' => $status,
            'success' => $status === Password::RESET_LINK_SENT,
            'message' => __($status),
        ];
    }
}
