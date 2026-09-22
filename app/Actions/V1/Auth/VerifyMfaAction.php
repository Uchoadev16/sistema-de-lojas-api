<?php

namespace App\Actions\V1\Auth;

use App\Models\MfaMethod;
use App\Models\User;
use App\Support\Enums\MfaType;
use Illuminate\Support\Facades\Cache;

class VerifyMfaAction
{
    public function execute(User $user, array $input): bool
    {
        $methodId = $input['mfa_method_id'] ?? null;
        $code = preg_replace('/\s+/', '', (string) ($input['code'] ?? ''));

        $query = $user->mfaMethods()
            ->where('is_enabled', true);
        if ($methodId) {
            $query->where('id', $methodId);
        }
        $methods = $query->get();

        foreach ($methods as $method) {
            if ($this->verifyByMethod($method, $code)) {
                return true;
            }
        }

        return false;
    }

    protected function verifyByMethod(MfaMethod $method, string $code): bool
    {
        return match ($method->method_type->value ?? $method->method_type) {
            MfaType::Totp->value => $this->verifyTotp($method, $code),
            MfaType::Recovery->value => $this->verifyRecovery($method, $code),
            MfaType::Email->value, MfaType::Sms->value => $this->verifyOnetime($method, $code),
            default => false,
        };
    }

    protected function verifyTotp(MfaMethod $method, string $code): bool
    {
        return app(ConfirmMfaTotpAction::class)->{'verifyTotp'}($method, $code);
    }

    protected function verifyRecovery(MfaMethod $method, string $code): bool
    {
        $codes = $method->recovery_codes ?? [];
        if (! is_array($codes)) {
            return false;
        }
        $normalized = strtoupper(trim($code));
        foreach ($codes as $c => $used) {
            if (is_string($c) && strtoupper($c) === $normalized && ! $used) {
                $codes[$c] = true;
                $method->recovery_codes = $codes;
                $method->save();

                return true;
            }
        }

        return false;
    }

    protected function verifyOnetime(MfaMethod $method, string $code): bool
    {
        $stored = Cache::get('mfa_code:'.$method->id);
        if (! $stored) {
            return false;
        }
        if (hash_equals((string) $stored, $code)) {
            Cache::forget('mfa_code:'.$method->id);

            return true;
        }

        return false;
    }
}
