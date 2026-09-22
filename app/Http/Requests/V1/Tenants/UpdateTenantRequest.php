<?php

namespace App\Http\Requests\V1\Tenants;

use App\Services\Tenancy\TenantContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', app(TenantContext::class)->tenant()) ?? false;
    }

    public function rules(): array
    {
        $tenantId = app(TenantContext::class)->hasTenant()
            ? app(TenantContext::class)->id()
            : 'null';

        return [
            'legal_name' => ['sometimes', 'required', 'string', 'min:2', 'max:180'],
            'trade_name' => ['sometimes', 'required', 'string', 'min:2', 'max:180'],
            'slug' => ['sometimes', 'string', 'max:120', 'alpha_dash',
                Rule::unique('tenants', 'slug')->ignore($tenantId, 'id')],
            'document' => ['sometimes', 'nullable', 'string', 'max:20'],
            'ie_rg' => ['sometimes', 'nullable', 'string', 'max:30'],
            'im' => ['sometimes', 'nullable', 'string', 'max:30'],
            'email' => ['sometimes', 'nullable', 'string', 'email:rfc,dns', 'max:150'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'mobile' => ['sometimes', 'nullable', 'string', 'max:30'],
            'website' => ['sometimes', 'nullable', 'string', 'url', 'max:255'],
            'logo_path' => ['sometimes', 'nullable', 'string', 'max:500'],
            'banner_path' => ['sometimes', 'nullable', 'string', 'max:500'],
            'primary_color' => ['sometimes', 'nullable', 'string', 'max:12'],
            'secondary_color' => ['sometimes', 'nullable', 'string', 'max:12'],
            'address_street' => ['sometimes', 'nullable', 'string', 'max:180'],
            'address_number' => ['sometimes', 'nullable', 'string', 'max:30'],
            'address_complement' => ['sometimes', 'nullable', 'string', 'max:120'],
            'address_neighborhood' => ['sometimes', 'nullable', 'string', 'max:100'],
            'address_city' => ['sometimes', 'nullable', 'string', 'max:100'],
            'address_state' => ['sometimes', 'nullable', 'string', 'size:2'],
            'address_postal_code' => ['sometimes', 'nullable', 'string', 'max:14'],
            'address_latitude' => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
            'address_longitude' => ['sometimes', 'nullable', 'numeric', 'between:-180,180'],
            'timezone' => ['sometimes', 'nullable', 'string', 'max:60'],
            'locale' => ['sometimes', 'nullable', 'string', 'max:8'],
            'currency' => ['sometimes', 'nullable', 'string', 'max:6'],
            'date_format' => ['sometimes', 'nullable', 'string', 'max:16'],
            'about' => ['sometimes', 'nullable', 'string', 'max:10000'],
            'business_hours' => ['sometimes', 'nullable', 'string'],
            'cancellation_policy' => ['sometimes', 'nullable', 'string'],
            'settings' => ['sometimes', 'nullable', 'array'],
            'preferences' => ['sometimes', 'nullable', 'array'],
        ];
    }
}
