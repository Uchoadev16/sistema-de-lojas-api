<?php

namespace App\Http\Requests\V1\Roles;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class SyncRolePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = $this->route('role');
        if ($role instanceof Role) {
            return $this->user()?->can('syncPermissions', $role) ?? false;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'permission_ids' => ['required', 'array'],
            'permission_ids.*' => ['string', 'exists:permissions,id'],
            'replace_all' => ['nullable', 'boolean'],
        ];
    }
}
