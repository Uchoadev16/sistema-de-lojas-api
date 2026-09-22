<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc,dns', 'max:150'],
            'return_url' => ['nullable', 'string', 'url', 'max:500'],
            'locale' => ['nullable', 'string', 'max:8'],
        ];
    }
}
