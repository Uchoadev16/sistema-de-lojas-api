<?php

namespace App\Http\Requests\V1\Technicians;

use App\Models\Technician;
use App\Support\Enums\TechnicianAvailability;
use App\Support\Enums\TechnicianStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreTechnicianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Technician::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'uuid', 'exists:users,id'],
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'document' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'string', 'email:filter', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'photo_url' => ['nullable', 'string', 'max:500'],
            'specialty' => ['nullable', 'string', 'max:100'],
            'professional_registration' => ['nullable', 'string', 'max:100'],
            'service_region' => ['nullable', 'string', 'max:150'],
            'color' => ['nullable', 'string', 'max:30'],
            'status' => ['nullable', new Enum(TechnicianStatus::class)],
            'availability_status' => ['nullable', new Enum(TechnicianAvailability::class)],
            'hire_date' => ['nullable', 'date'],
            'termination_date' => ['nullable', 'date'],
            'salary_cents' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
