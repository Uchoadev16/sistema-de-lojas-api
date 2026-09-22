<?php

namespace App\Http\Requests\V1\ServiceTeams;

use App\Models\ServiceTeam;
use Illuminate\Foundation\Http\FormRequest;

class DetachMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('service_team') ?? ServiceTeam::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'technician_ids' => ['required', 'array', 'min:1'],
            'technician_ids.*' => ['uuid', 'exists:technicians,id'],
            'left_at' => ['nullable', 'date'],
        ];
    }
}
