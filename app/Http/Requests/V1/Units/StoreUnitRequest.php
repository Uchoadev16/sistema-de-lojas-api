<?php

namespace App\Http\Requests\V1\Units;

use App\Models\Unit;
use App\Support\Enums\UnitStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Unit::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'string', 'exists:customers,id'],
            'address_id' => ['nullable', 'string', 'exists:addresses,id'],
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'code' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'string', Rule::enum(UnitStatus::class)],
            'is_active' => ['sometimes', 'boolean'],
            'responsible_contact_id' => ['nullable', 'string', 'exists:customer_contacts,id'],
            'notes' => ['nullable', 'string'],
            'external_id' => ['nullable', 'string', 'max:120'],
            'area_m2' => ['nullable', 'numeric', 'min:0'],
            'floors' => ['nullable', 'integer', 'min:1'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
