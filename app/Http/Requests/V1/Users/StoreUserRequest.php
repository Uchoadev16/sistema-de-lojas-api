<?php

namespace App\Http\Requests\V1\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', User::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:140'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:150', 'unique:users,email'],
            'document' => ['nullable', 'string', 'max:20', 'unique:users,document'],
            'phone' => ['nullable', 'string', 'max:30'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', 'max:20'],
            'avatar_path' => ['nullable', 'string', 'max:500'],
            'role_id' => ['required', 'string', 'exists:roles,id'],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
            'send_invite' => ['nullable', 'boolean'],
            'message' => ['nullable', 'string', 'max:1000'],
            'is_owner' => ['nullable', 'boolean'],
            'can_approve_budget' => ['nullable', 'boolean'],
            'max_budget_amount_cents' => ['nullable', 'integer', 'min:0'],
            'approval_limit_scope' => ['nullable', 'string', 'max:60'],
            'external_id' => ['nullable', 'string', 'max:120'],
        ];
    }
}
