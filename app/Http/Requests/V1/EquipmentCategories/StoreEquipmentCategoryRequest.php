<?php

namespace App\Http\Requests\V1\EquipmentCategories;

use App\Models\EquipmentCategory;
use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', EquipmentCategory::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120'],
            'code' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_system' => ['nullable', 'boolean'],
            'color' => ['nullable', 'string', 'max:30'],
        ];
    }
}
