<?php

namespace App\Http\Requests\V1\Equipments;

use App\Models\Equipment;
use App\Support\Enums\EquipmentCondition;
use App\Support\Enums\EquipmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreEquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Equipment::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'uuid'],
            'unit_id' => ['nullable', 'uuid'],
            'environment_id' => ['nullable', 'uuid'],
            'category_id' => ['nullable', 'uuid', 'exists:equipment_categories,id'],
            'brand_id' => ['nullable', 'uuid', 'exists:equipment_brands,id'],
            'model_id' => ['nullable', 'uuid', 'exists:equipment_models,id'],
            'identifier' => ['required', 'string', 'max:80'],
            'asset_tag' => ['nullable', 'string', 'max:80'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'equipment_type' => ['nullable', 'string', 'max:100'],
            'manufacturer' => ['nullable', 'string', 'max:100'],
            'model_name' => ['nullable', 'string', 'max:100'],
            'capacity' => ['nullable', 'numeric', 'min:0'],
            'capacity_unit' => ['nullable', 'string', 'max:30'],
            'refrigerant' => ['nullable', 'string', 'max:50'],
            'voltage' => ['nullable', 'string', 'max:30'],
            'power_kw' => ['nullable', 'numeric', 'min:0'],
            'installed_at' => ['nullable', 'date'],
            'warranty_start' => ['nullable', 'date'],
            'warranty_end' => ['nullable', 'date'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_price_cents' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', new Enum(EquipmentStatus::class)],
            'is_active' => ['nullable', 'boolean'],
            'qr_code_value' => ['nullable', 'string', 'max:150', 'unique:equipments,qr_code_value'],
            'condition' => ['nullable', new Enum(EquipmentCondition::class)],
            'last_maintenance_at' => ['nullable', 'date'],
            'next_maintenance_at' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'external_id' => ['nullable', 'string', 'max:120'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
