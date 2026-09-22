<?php

namespace App\Http\Requests\V1\EquipmentBrands;

use App\Models\EquipmentBrand;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipmentBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('equipment_brand') ?? EquipmentBrand::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:2', 'max:100'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:120'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'website' => ['sometimes', 'nullable', 'string', 'url', 'max:255'],
            'logo_url' => ['sometimes', 'nullable', 'string', 'max:500'],
            'is_system' => ['sometimes', 'boolean'],
        ];
    }
}
