<?php

namespace App\Http\Requests\V1\Roles;

use App\Models\Role;
use App\Services\Tenancy\TenantContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = $this->route('role');
        if ($role instanceof Role) {
            return $this->user()?->can('update', $role) ?? false;
        }

        return false;
    }

    public function rules(): array
    {
        $roleId = $this->route('role')?->getKey() ?? 'null';
        $tenantId = app(TenantContext::class)->hasTenant()
            ? app(TenantContext::class)->id()
            : null;

        return [
            'name' => ['sometimes', 'required', 'string', 'min:2', 'max:80'],
            'slug' => ['sometimes', 'required', 'string', 'max:80', 'alpha_dash',
                Rule::unique('roles', 'slug')
                    ->where(fn ($q) => $q->where('tenant_id', $tenantId))
                    ->ignore($roleId, 'id')],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'color_hex' => ['sometimes', 'nullable', 'string', 'max:12'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:1000'],
            'permission_ids' => ['sometimes', 'nullable', 'array'],
            'permission_ids.*' => ['string', 'exists:permissions,id'],
        ];
    }
}
