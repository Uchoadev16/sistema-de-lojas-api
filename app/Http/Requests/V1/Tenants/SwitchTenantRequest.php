<?php

namespace App\Http\Requests\V1\Tenants;

use Illuminate\Foundation\Http\FormRequest;

class SwitchTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'tenant_id' => ['required', 'string', 'exists:tenants,id'],
            'device_id' => ['nullable', 'string', 'exists:devices,id'],
        ];
    }
}
