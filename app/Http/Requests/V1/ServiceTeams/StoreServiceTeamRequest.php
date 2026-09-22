<?php

namespace App\Http\Requests\V1\ServiceTeams;

use App\Models\ServiceTeam;
use App\Support\Enums\ServiceTeamStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreServiceTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', ServiceTeam::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'code' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', new Enum(ServiceTeamStatus::class)],
            'is_active' => ['nullable', 'boolean'],
            'leader_technician_id' => ['nullable', 'uuid', 'exists:technicians,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
