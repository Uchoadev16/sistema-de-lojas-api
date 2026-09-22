<?php

namespace App\Actions\V1\Auth;

use App\Events\Auth\MfaEnabled;
use App\Models\MfaMethod;
use App\Models\User;
use App\Support\Enums\MfaType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use ParagonIE\ConstantTime\Base32;

class ConfirmMfaTotpAction
{
    public function execute(User $user, array $input): array
    {
        return DB::transaction(function () use ($user, $input): array {
            $method = MfaMethod::where('id', $input['mfa_method_id'])
                ->where('user_id', $user->id)
                ->where('method_type', MfaType::Totp->value)
                ->where('is_enabled', false)
                ->firstOrFail();

            $code = preg_replace('/\s+/', '', (string) $input['code']);
            if (! $this->verifyTotp($method, $code)) {
                throw ValidationException::withMessages([
                    'code' => __('mfa.invalid_code'),
                ])->status(422);
            }

            $user->mfaMethods()
                ->where('is_default', true)
                ->where('id', '!=', $method->id)
                ->update(['is_default' => false]);

            $method->is_enabled = true;
            $method->is_default = (bool) ($input['is_default'] ?? true);
            $method->verified_at = now();
            $method->save();

            $generateRecovery = (bool) ($input['generate_recovery_codes'] ?? true);
            $recoveryCodes = [];
            if ($generateRecovery) {
                $codes = [];
                for ($i = 0; $i < 8; $i++) {
                    $c = strtoupper(Str::random(5).'-'.Str::random(5));
                    $codes[$c] = false;
                }
                $method->recovery_codes = $codes;
                $method->save();
                $recoveryCodes = array_keys($codes);
            }

            $user->mfa_enabled = true;
            $user->save();

            event(new MfaEnabled($user, $method));

            return [
                'mfa_method' => $method,
                'recovery_codes' => $recoveryCodes,
            ];
        });
    }

    protected function verifyTotp(MfaMethod $method, string $code): bool
    {
        try {
            $secret = decrypt($method->secret);
        } catch (\Throwable) {
            return false;
        }

        $period = 30;
        $digits = 6;
        $algo = 'sha1';

        $decode = Base32::decodeUpper($secret);
        $timestamp = (int) floor(time() / $period);

        for ($offset = -1; $offset <= 1; $offset++) {
            $computed = $this->computeTotp($decode, $timestamp + $offset, $digits, $algo);
            if (hash_equals($computed, $code)) {
                return true;
            }
        }

        return false;
    }

    protected function computeTotp(string $key, int $counter, int $digits, string $algo): string
    {
        $time = str_pad(pack('N*', 0).pack('N*', $counter), 8, chr(0), STR_PAD_LEFT);
        $hash = hash_hmac($algo, $time, $key, true);
        $offset = ord($hash[strlen($hash) - 1]) & 0xF;
        $code = (
            ((ord($hash[$offset]) & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8) |
            (ord($hash[$offset + 3]) & 0xFF)
        );
        $mod = 10 ** $digits;

        return str_pad((string) ($code % $mod), $digits, '0', STR_PAD_LEFT);
    }
}
