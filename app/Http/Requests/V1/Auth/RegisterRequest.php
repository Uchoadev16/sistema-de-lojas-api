<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:140'],
            'email' => ['required', 'string', 'email:filter', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
            'document' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:30'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'locale' => ['nullable', 'string', 'max:8'],
            'timezone' => ['nullable', 'string', 'max:60'],

            'tenant' => ['required', 'array'],
            'tenant.trade_name' => ['required', 'string', 'min:2', 'max:180'],
            'tenant.legal_name' => ['nullable', 'string', 'min:2', 'max:180'],
            'tenant.document' => ['nullable', 'string', 'max:20'],
            'tenant.phone' => ['nullable', 'string', 'max:30'],
            'tenant.mobile' => ['nullable', 'string', 'max:30'],
            'tenant.email' => ['nullable', 'string', 'email:filter', 'max:150'],
            'tenant.slug' => ['nullable', 'string', 'max:120', 'alpha_dash', 'unique:tenants,slug'],

            'tenant.address_street' => ['nullable', 'string', 'max:180'],
            'tenant.address_number' => ['nullable', 'string', 'max:30'],
            'tenant.address_complement' => ['nullable', 'string', 'max:120'],
            'tenant.address_neighborhood' => ['nullable', 'string', 'max:100'],
            'tenant.address_city' => ['nullable', 'string', 'max:100'],
            'tenant.address_state' => ['nullable', 'string', 'size:2'],
            'tenant.address_postal_code' => ['nullable', 'string', 'max:14'],

            'saas_plan_id' => ['nullable', 'string', 'exists:saas_plans,id'],
            'accept_terms' => ['required', 'boolean', 'accepted'],
            'utm_source' => ['nullable', 'string', 'max:120'],
            'utm_campaign' => ['nullable', 'string', 'max:120'],
        ];
    }
}
