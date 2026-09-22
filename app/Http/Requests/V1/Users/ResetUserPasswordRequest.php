<?php

namespace App\Http\Requests\V1\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetUserPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        $target = $this->route('user');
        if ($target instanceof User) {
            return $this->user()?->can('resetPassword', $target) ?? false;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
            'send_by_email' => ['nullable', 'boolean'],
            'generate_random' => ['nullable', 'boolean'],
            'return_url' => ['nullable', 'string', 'url', 'max:500'],
        ];
    }
}
