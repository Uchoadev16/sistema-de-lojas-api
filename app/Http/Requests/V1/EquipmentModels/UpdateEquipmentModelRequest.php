<?php

namespace App\Http\Requests\V1\EquipmentModels;

use App\Models\EquipmentModel;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipmentModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('equipment_model') ?? EquipmentModel::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'brand_id' => ['sometimes', 'uuid', 'exists:equipment_brands,id'],
            'category_id' => ['sometimes', 'nullable', 'uuid', 'exists:equipment_categories,id'],
            'name' => ['sometimes', 'string', 'min:2', 'max:150'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:180'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'image_url' => ['sometimes', 'nullable', 'string', 'max:500'],
            'technical_specifications' => ['sometimes', 'nullable', 'array'],
        ];
    }
}
