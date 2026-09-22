<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class MfaConfirmTotpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'mfa_method_id' => ['required', 'string'],
            'code' => ['required', 'string', 'min:4', 'max:12'],
            'is_default' => ['nullable', 'boolean'],
            'generate_recovery_codes' => ['nullable', 'boolean'],
        ];
    }
}
