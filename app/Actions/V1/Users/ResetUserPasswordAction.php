<?php

namespace App\Actions\V1\Users;

use App\Actions\V1\Auth\RevokeAllUserTokensAction;
use App\Jobs\Auth\SendResetPasswordEmailJob;
use App\Models\User;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetUserPasswordAction
{
    public function execute(User $targetUser, array $input): array
    {
        $password = $input['password'] ?? null;
        $auto = (bool) ($input['generate_random'] ?? false) || ! $password;

        if ($auto) {
            $password = (string) Str::password(12, true, true, true, false);
        }

        $sendByEmail = (bool) ($input['send_by_email'] ?? false);
        $resetToken = null;

        DB::transaction(function () use ($targetUser, $password, $sendByEmail, &$resetToken): void {
            $targetUser->password = Hash::make($password);
            $targetUser->save();

            if ($sendByEmail) {
                $resetToken = app(PasswordBrokerManager::class)
                    ->broker()
                    ->createToken($targetUser);
            }

            app(RevokeAllUserTokensAction::class)->execute($targetUser);
        });

        if ($sendByEmail && $resetToken) {
            try {
                SendResetPasswordEmailJob::dispatch($targetUser, $resetToken, $input['return_url'] ?? null)
                    ->onQueue('emails');
            } catch (\Throwable) {
            }
        }

        return [
            'password_reset' => true,
            'password_generated' => $auto,
            'sent_by_email' => $sendByEmail,
            'other_sessions_revoked' => true,
        ];
    }
}
