<?php

namespace App\Http\Requests\V1\Tags;

use App\Support\Enums\TagColor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        $tag = $this->route('tag');

        return $this->user()?->can('update', $tag) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:2', 'max:80'],
            'slug' => ['nullable', 'string', 'max:100', 'alpha_dash'],
            'color' => ['sometimes', 'string', Rule::enum(TagColor::class)],
            'description' => ['nullable', 'string'],
            'is_system' => ['sometimes', 'boolean'],
        ];
    }
}
