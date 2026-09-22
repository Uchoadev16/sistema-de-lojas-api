<?php

namespace App\Http\Requests\V1\Tags;

use App\Models\Tag;
use App\Support\Enums\TagColor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Tag::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'slug' => ['nullable', 'string', 'max:100', 'alpha_dash'],
            'color' => ['sometimes', 'string', Rule::enum(TagColor::class)],
            'description' => ['nullable', 'string'],
            'is_system' => ['sometimes', 'boolean'],
        ];
    }
}
