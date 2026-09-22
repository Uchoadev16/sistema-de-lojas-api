<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class MfaVerifyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mfa_challenge_id' => ['required', 'string', 'max:80'],
            'mfa_code' => ['required', 'string', 'min:4', 'max:12'],
            'mfa_method_id' => ['nullable', 'string'],
            'remember_device' => ['nullable', 'boolean'],
        ];
    }
}
