<?php

namespace App\Http\Requests\V1\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class InviteUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('invite', User::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc,dns', 'max:150'],
            'name' => ['nullable', 'string', 'min:2', 'max:140'],
            'document' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:30'],
            'role_id' => ['required', 'string', 'exists:roles,id'],
            'message' => ['nullable', 'string', 'max:1000'],
            'locale' => ['nullable', 'string', 'max:8'],
        ];
    }
}
