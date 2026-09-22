<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class MfaDisableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'current_password'],
            'mfa_method_id' => ['nullable', 'string'],
            'code' => ['nullable', 'string', 'max:12'],
        ];
    }
}
