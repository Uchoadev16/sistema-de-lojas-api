<?php

namespace App\Actions\V1\Auth;

use App\Events\Auth\MfaDisabled;
use App\Models\MfaMethod;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DisableMfaAction
{
    public function execute(User $user, array $input): array
    {
        return DB::transaction(function () use ($user, $input): array {
            $methodId = $input['mfa_method_id'] ?? null;

            if ($methodId) {
                $method = MfaMethod::where('id', $methodId)
                    ->where('user_id', $user->id)
                    ->firstOrFail();
                $method->delete();
            } else {
                $user->mfaMethods()->delete();
            }

            $remaining = $user->mfaMethods()->count();
            if ($remaining === 0) {
                $user->mfa_enabled = false;
                $user->current_mfa_challenge_id = null;
                $user->save();
            }

            event(new MfaDisabled($user, $methodId));

            return [
                'disabled' => true,
                'remaining_methods' => $remaining,
                'mfa_enabled' => $remaining > 0,
            ];
        });
    }
}
