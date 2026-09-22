<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\V1\Auth\VerifyEmailAction;
use App\Http\Controllers\Controller;
use App\Support\Enums\UserStatus;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    public function verify(EmailVerificationRequest $request, VerifyEmailAction $action)
    {
        $alreadyVerified = $request->user()->hasVerifiedEmail();

        if (! $alreadyVerified) {
            $action->execute((string) $request->route('id'), (string) $request->route('hash'));
            $request->user()->update(['status' => UserStatus::Active->value]);
        }

        return $this->success(['verified' => true, 'already_verified' => $alreadyVerified]);
    }
}
