<?php

namespace App\Actions\V1\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;

class VerifyEmailAction
{
    public function execute(string $id, string $hash): void
    {
        $user = User::findOrFail($id);
        if (! hash_equals((string) $id, (string) $user->getKey()) || ! hash_equals($hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid signature.');
        }
        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }
    }
}
