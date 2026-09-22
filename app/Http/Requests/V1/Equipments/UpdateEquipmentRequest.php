<?php

namespace App\Http\Requests\V1\Equipments;

use App\Models\Equipment;
use App\Support\Enums\EquipmentCondition;
use App\Support\Enums\EquipmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateEquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('equipment') ?? Equipment::class) ?? false;
    }

    public function rules(): array
    {
        $equipmentId = $this->route('equipment')?->id ?? $this->route('equipment');

        return [
            'customer_id' => ['sometimes', 'uuid'],
            'unit_id' => ['sometimes', 'nullable', 'uuid'],
            'environment_id' => ['sometimes', 'nullable', 'uuid'],
            'category_id' => ['sometimes', 'nullable', 'uuid', 'exists:equipment_categories,id'],
            'brand_id' => ['sometimes', 'nullable', 'uuid', 'exists:equipment_brands,id'],
            'model_id' => ['sometimes', 'nullable', 'uuid', 'exists:equipment_models,id'],
            'identifier' => ['sometimes', 'string', 'max:80'],
            'asset_tag' => ['sometimes', 'nullable', 'string', 'max:80'],
            'serial_number' => ['sometimes', 'nullable', 'string', 'max:100'],
            'equipment_type' => ['sometimes', 'nullable', 'string', 'max:100'],
            'manufacturer' => ['sometimes', 'nullable', 'string', 'max:100'],
            'model_name' => ['sometimes', 'nullable', 'string', 'max:100'],
            'capacity' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'capacity_unit' => ['sometimes', 'nullable', 'string', 'max:30'],
            'refrigerant' => ['sometimes', 'nullable', 'string', 'max:50'],
            'voltage' => ['sometimes', 'nullable', 'string', 'max:30'],
            'power_kw' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'installed_at' => ['sometimes', 'nullable', 'date'],
            'warranty_start' => ['sometimes', 'nullable', 'date'],
            'warranty_end' => ['sometimes', 'nullable', 'date'],
            'purchase_date' => ['sometimes', 'nullable', 'date'],
            'purchase_price_cents' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'status' => ['sometimes', 'nullable', new Enum(EquipmentStatus::class)],
            'is_active' => ['sometimes', 'boolean'],
            'qr_code_value' => ['sometimes', 'nullable', 'string', 'max:150', 'unique:equipments,qr_code_value,'.$equipmentId],
            'condition' => ['sometimes', 'nullable', new Enum(EquipmentCondition::class)],
            'last_maintenance_at' => ['sometimes', 'nullable', 'date'],
            'next_maintenance_at' => ['sometimes', 'nullable', 'date'],
            'description' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'external_id' => ['sometimes', 'nullable', 'string', 'max:120'],
            'metadata' => ['sometimes', 'nullable', 'array'],
        ];
    }
}
