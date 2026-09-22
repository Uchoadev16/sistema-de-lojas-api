<?php

namespace App\Http\Requests\V1\EquipmentModels;

use App\Models\EquipmentModel;
use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', EquipmentModel::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'brand_id' => ['required', 'uuid', 'exists:equipment_brands,id'],
            'category_id' => ['nullable', 'uuid', 'exists:equipment_categories,id'],
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image_url' => ['nullable', 'string', 'max:500'],
            'technical_specifications' => ['nullable', 'array'],
        ];
    }
}
