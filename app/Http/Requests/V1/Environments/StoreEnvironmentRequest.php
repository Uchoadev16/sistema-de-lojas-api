<?php

namespace App\Http\Requests\V1\Environments;

use App\Models\Environment;
use App\Support\Enums\EnvironmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnvironmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Environment::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'unit_id' => ['required', 'string', 'exists:units,id'],
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'code' => ['nullable', 'string', 'max:50'],
            'floor' => ['nullable', 'string', 'max:50'],
            'area' => ['nullable', 'numeric', 'min:0'],
            'purpose' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'string', Rule::enum(EnvironmentStatus::class)],
            'is_active' => ['sometimes', 'boolean'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
