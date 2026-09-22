<?php

namespace App\Http\Requests\V1\Roles;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Role::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'slug' => ['required', 'string', 'max:80', 'alpha_dash'],
            'description' => ['nullable', 'string', 'max:1000'],
            'color_hex' => ['nullable', 'string', 'max:12'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['string', 'exists:permissions,id'],
        ];
    }
}
