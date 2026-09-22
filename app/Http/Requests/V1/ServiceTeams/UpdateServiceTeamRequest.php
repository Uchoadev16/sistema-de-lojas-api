<?php

namespace App\Http\Requests\V1\ServiceTeams;

use App\Models\ServiceTeam;
use App\Support\Enums\ServiceTeamStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateServiceTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('service_team') ?? ServiceTeam::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:2', 'max:100'],
            'code' => ['sometimes', 'nullable', 'string', 'max:50'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'status' => ['sometimes', 'nullable', new Enum(ServiceTeamStatus::class)],
            'is_active' => ['sometimes', 'boolean'],
            'leader_technician_id' => ['sometimes', 'nullable', 'uuid', 'exists:technicians,id'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'metadata' => ['sometimes', 'nullable', 'array'],
        ];
    }
}
