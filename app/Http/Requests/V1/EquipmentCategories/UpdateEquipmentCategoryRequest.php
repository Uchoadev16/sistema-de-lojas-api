<?php

namespace App\Http\Requests\V1\EquipmentCategories;

use App\Models\EquipmentCategory;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipmentCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('equipment_category') ?? EquipmentCategory::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:2', 'max:100'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:120'],
            'code' => ['sometimes', 'nullable', 'string', 'max:50'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'is_system' => ['sometimes', 'boolean'],
            'color' => ['sometimes', 'nullable', 'string', 'max:30'],
        ];
    }
}
