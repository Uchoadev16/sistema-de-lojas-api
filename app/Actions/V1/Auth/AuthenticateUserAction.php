<?php

namespace App\Actions\V1\Auth;

use App\Events\Auth\UserLoggedIn;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Models\User;
use App\Support\Enums\UserTenantStatus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticateUserAction
{
    public function execute(LoginRequest $request, ?string $ip = null, ?string $userAgent = null): array
    {
        $ip ??= request()?->ip();
        $userAgent ??= request()?->userAgent();
        if ($request->filled('mfa_challenge_id')) {
            return $this->handleMfaStep($request, $ip, $userAgent);
        }

        [$user, $remember] = $request->authenticate();

        $user->timestamps = false;
        $user->last_login_at = now();
        $user->last_login_ip = $request->ip();
        $user->last_login_user_agent = substr((string) $request->userAgent(), 0, 500);
        $user->saveQuietly();

        $activeUserTenants = $user->userTenants()
            ->with(['tenant', 'role'])
            ->where('status', UserTenantStatus::Active->value)
            ->where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->get();

        if ($user->mfa_enabled) {
            $challengeId = Str::random(40);
            $user->current_mfa_challenge_id = $challengeId;
            $user->saveQuietly();

            $methods = $user->mfaMethods()
                ->where('is_enabled', true)
                ->get(['id', 'method_type', 'label']);

            return [
                'mfa_required' => true,
                'mfa_challenge_id' => $challengeId,
                'available_methods' => $methods,
            ];
        }

        if ($activeUserTenants->isEmpty()) {
            throw ValidationException::withMessages([
                'email' => __('auth.no_tenants'),
            ])->status(403);
        }

        $activeUserTenants->load('tenant');
        $defaultUserTenant = $activeUserTenants->firstWhere('is_default', true) ?? $activeUserTenants->first();

        $tokenAction = app(IssueAuthTokenAction::class);
        $tokenResult = $tokenAction->execute($user, [
            'device_name' => $request->input('device_name') ?: ($request->userAgent() ? substr($request->userAgent(), 0, 110) : 'web-login'),
            'device_platform' => $request->input('device_platform'),
            'device_unique_id' => $request->input('device_unique_id'),
            'expires_at' => $remember ? now()->addDays(30) : now()->addHours(12),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        event(new UserLoggedIn(
            $user,
            $defaultUserTenant?->tenant,
            [
                'token_id' => $tokenResult['token']?->getKey(),
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]
        ));

        return [
            'token' => $tokenResult['token'],
            'plain_text_token' => $tokenResult['plain_text_token'],
            'expires_in' => $tokenResult['expires_in'],
            'token_name' => $tokenResult['token_name'],
            'abilities' => $tokenResult['abilities'],
            'user' => $user,
            'tenant' => $defaultUserTenant?->tenant,
            'role' => $defaultUserTenant?->role,
            'available_tenants' => $activeUserTenants->pluck('tenant')->filter(),
        ];
    }

    protected function handleMfaStep(LoginRequest $request, ?string $ip = null, ?string $userAgent = null): array
    {
        $ip ??= request()?->ip();
        $userAgent ??= request()?->userAgent();
        $userId = Cache::get('mfa_challenge:'.$request->input('mfa_challenge_id'));
        $user = $userId ? User::find($userId) : null;

        if (! $user || $user->current_mfa_challenge_id !== $request->input('mfa_challenge_id')) {
            throw ValidationException::withMessages([
                'mfa_code' => __('mfa.invalid_challenge'),
            ])->status(422);
        }

        $verifyAction = app(VerifyMfaAction::class);
        $verified = $verifyAction->execute($user, [
            'mfa_method_id' => $request->input('mfa_method_id'),
            'code' => $request->input('mfa_code'),
        ]);

        if (! $verified) {
            throw ValidationException::withMessages([
                'mfa_code' => __('mfa.invalid_code'),
            ])->status(422);
        }

        $user->current_mfa_challenge_id = null;
        $user->saveQuietly();

        $activeUserTenants = $user->userTenants()
            ->with(['tenant', 'role'])
            ->where('status', UserTenantStatus::Active->value)
            ->where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->get();

        $defaultUserTenant = $activeUserTenants->firstWhere('is_default', true) ?? $activeUserTenants->first();

        $tokenAction = app(IssueAuthTokenAction::class);
        $tokenResult = $tokenAction->execute($user, [
            'device_name' => $request->input('device_name') ?: 'web-mfa-login',
            'device_platform' => $request->input('device_platform'),
            'device_unique_id' => $request->input('device_unique_id'),
            'expires_at' => now()->addHours(12),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        event(new UserLoggedIn(
            $user,
            $defaultUserTenant?->tenant,
            [
                'token_id' => $tokenResult['token']?->getKey(),
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]
        ));

        return [
            'token' => $tokenResult['token'],
            'plain_text_token' => $tokenResult['plain_text_token'],
            'expires_in' => $tokenResult['expires_in'],
            'token_name' => $tokenResult['token_name'],
            'abilities' => $tokenResult['abilities'],
            'user' => $user,
            'tenant' => $defaultUserTenant?->tenant,
            'role' => $defaultUserTenant?->role,
            'available_tenants' => $activeUserTenants->pluck('tenant')->filter(),
        ];
    }
}
