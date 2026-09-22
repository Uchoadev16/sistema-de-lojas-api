<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
            'remember' => ['nullable', 'boolean'],
            'device_name' => ['nullable', 'string', 'max:120'],
            'device_platform' => ['nullable', 'string', 'max:30'],
            'device_unique_id' => ['nullable', 'string', 'max:160'],
            'mfa_challenge_id' => ['nullable', 'string', 'max:80'],
            'mfa_code' => ['nullable', 'string', 'max:12'],
            'mfa_method_id' => ['nullable', 'string'],
        ];
    }

    public function authenticate(): array
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');
        if (! Auth::attempt($credentials, (bool) $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        $user = Auth::user();
        Auth::logoutCurrentDevice();

        return [$user, $this->remember()];
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ])->status(429);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

    public function remember(): bool
    {
        return $this->boolean('remember', false);
    }
}
