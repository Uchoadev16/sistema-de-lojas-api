<?php

namespace App\Http\Requests\V1\Addresses;

use App\Models\Address;
use App\Support\Enums\AddressType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Address::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'address_type' => ['sometimes', 'string', Rule::enum(AddressType::class)],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'street' => ['required', 'string', 'min:2', 'max:200'],
            'number' => ['nullable', 'string', 'max:30'],
            'complement' => ['nullable', 'string', 'max:100'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'min:2', 'max:100'],
            'state' => ['required', 'string', 'min:2', 'max:100'],
            'country' => ['sometimes', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
