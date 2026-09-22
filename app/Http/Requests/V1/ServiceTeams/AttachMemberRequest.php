<?php

namespace App\Http\Requests\V1\ServiceTeams;

use App\Models\ServiceTeam;
use Illuminate\Foundation\Http\FormRequest;

class AttachMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('service_team') ?? ServiceTeam::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'technician_ids' => ['nullable', 'array', 'min:1'],
            'technician_ids.*' => ['uuid', 'exists:technicians,id'],
            'technician_id' => ['nullable', 'uuid', 'exists:technicians,id'],
            'is_lead' => ['nullable', 'boolean'],
            'joined_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:255'],
        ];
    }
}
