<?php

namespace App\Http\Requests\V1\CustomerContacts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        $contact = $this->route('contact');

        return $this->user()?->can('update', $contact) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:2', 'max:150'],
            'position' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email:filter', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'is_primary' => ['sometimes', 'boolean'],
            'receives_notifications' => ['sometimes', 'boolean'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
