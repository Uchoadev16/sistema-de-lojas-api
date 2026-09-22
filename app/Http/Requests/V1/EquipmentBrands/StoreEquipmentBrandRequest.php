<?php

namespace App\Http\Requests\V1\EquipmentBrands;

use App\Models\EquipmentBrand;
use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', EquipmentBrand::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'slug' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'website' => ['nullable', 'string', 'url', 'max:255'],
            'logo_url' => ['nullable', 'string', 'max:500'],
            'is_system' => ['nullable', 'boolean'],
        ];
    }
}
