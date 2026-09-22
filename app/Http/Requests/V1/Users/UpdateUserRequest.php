<?php

namespace App\Http\Requests\V1\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $target = $this->route('user');
        if ($target instanceof User) {
            return $this->user()?->can('update', $target) ?? false;
        }

        return false;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->getKey() ?? 'null';

        return [
            'name' => ['sometimes', 'required', 'string', 'min:2', 'max:140'],
            'email' => ['sometimes', 'required', 'string', 'email:rfc,dns', 'max:150', 'unique:users,email,'.$userId.',id'],
            'document' => ['sometimes', 'nullable', 'string', 'max:20', 'unique:users,document,'.$userId.',id'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'mobile' => ['sometimes', 'nullable', 'string', 'max:30'],
            'birth_date' => ['sometimes', 'nullable', 'date', 'before:today'],
            'gender' => ['sometimes', 'nullable', 'string', 'max:20'],
            'avatar_path' => ['sometimes', 'nullable', 'string', 'max:500'],
            'role_id' => ['sometimes', 'required', 'string', 'exists:roles,id'],
            'is_active' => ['sometimes', 'boolean'],
            'receive_email_notifications' => ['sometimes', 'boolean'],
            'receive_sms_notifications' => ['sometimes', 'boolean'],
            'receive_whatsapp_notifications' => ['sometimes', 'boolean'],
            'receive_push_notifications' => ['sometimes', 'boolean'],
            'locale' => ['sometimes', 'nullable', 'string', 'max:8'],
            'timezone' => ['sometimes', 'nullable', 'string', 'max:60'],
            'can_approve_budget' => ['sometimes', 'boolean'],
            'max_budget_amount_cents' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'approval_limit_scope' => ['sometimes', 'nullable', 'string', 'max:60'],
            'external_id' => ['sometimes', 'nullable', 'string', 'max:120'],
            'preferences' => ['sometimes', 'nullable', 'array'],
        ];
    }
}
