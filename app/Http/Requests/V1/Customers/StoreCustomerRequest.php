<?php

namespace App\Http\Requests\V1\Customers;

use App\Models\Customer;
use App\Support\Enums\CustomerStatus;
use App\Support\Enums\CustomerType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Customer::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'customer_type' => ['sometimes', 'string', Rule::enum(CustomerType::class)],
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'legal_name' => ['nullable', 'string', 'max:200'],
            'trade_name' => ['nullable', 'string', 'max:150'],
            'tax_id' => ['nullable', 'string', 'max:30'],
            'state_registration' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email:filter', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'status' => ['sometimes', 'string', Rule::enum(CustomerStatus::class)],
            'is_active' => ['sometimes', 'boolean'],
            'notes' => ['nullable', 'string'],
            'external_id' => ['nullable', 'string', 'max:120'],
            'preferences' => ['nullable', 'array'],
            'metadata' => ['nullable', 'array'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['string', 'exists:tags,id'],
        ];
    }
}
