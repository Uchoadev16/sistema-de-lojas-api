<?php

namespace App\Http\Requests\V1\Environments;

use App\Support\Enums\EnvironmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEnvironmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $environment = $this->route('environment');

        return $this->user()?->can('update', $environment) ?? false;
    }

    public function rules(): array
    {
        return [
            'unit_id' => ['sometimes', 'string', 'exists:units,id'],
            'name' => ['sometimes', 'string', 'min:2', 'max:150'],
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
