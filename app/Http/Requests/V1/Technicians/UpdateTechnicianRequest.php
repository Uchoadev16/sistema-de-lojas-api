<?php

namespace App\Http\Requests\V1\Technicians;

use App\Models\Technician;
use App\Support\Enums\TechnicianAvailability;
use App\Support\Enums\TechnicianStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateTechnicianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('technician') ?? Technician::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'nullable', 'uuid', 'exists:users,id'],
            'name' => ['sometimes', 'string', 'min:2', 'max:150'],
            'document' => ['sometimes', 'nullable', 'string', 'max:30'],
            'email' => ['sometimes', 'nullable', 'string', 'email:filter', 'max:150'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'mobile' => ['sometimes', 'nullable', 'string', 'max:30'],
            'whatsapp' => ['sometimes', 'nullable', 'string', 'max:30'],
            'photo_url' => ['sometimes', 'nullable', 'string', 'max:500'],
            'specialty' => ['sometimes', 'nullable', 'string', 'max:100'],
            'professional_registration' => ['sometimes', 'nullable', 'string', 'max:100'],
            'service_region' => ['sometimes', 'nullable', 'string', 'max:150'],
            'color' => ['sometimes', 'nullable', 'string', 'max:30'],
            'status' => ['sometimes', 'nullable', new Enum(TechnicianStatus::class)],
            'availability_status' => ['sometimes', 'nullable', new Enum(TechnicianAvailability::class)],
            'hire_date' => ['sometimes', 'nullable', 'date'],
            'termination_date' => ['sometimes', 'nullable', 'date'],
            'salary_cents' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'metadata' => ['sometimes', 'nullable', 'array'],
        ];
    }
}
