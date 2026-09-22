<?php

namespace App\Http\Resources\V1;

use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TechnicianResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Technician $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;
        $fmtDateOnly = static fn ($v): ?string => $v ? (is_string($v) ? $v : (method_exists($v, 'toDateString') ? $v->toDateString() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'document' => $this->document,
            'email' => $this->email,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'whatsapp' => $this->whatsapp,
            'photo_url' => $this->photo_url,
            'specialty' => $this->specialty,
            'professional_registration' => $this->professional_registration,
            'service_region' => $this->service_region,
            'color' => $this->color,
            'status' => $this->status?->value ?? $this->status,
            'availability_status' => $this->availability_status?->value ?? $this->availability_status,
            'hire_date' => $fmtDateOnly($this->hire_date),
            'termination_date' => $fmtDateOnly($this->termination_date),
            'salary_cents' => (int) $this->salary_cents,
            'salary' => $this->salary_cents ? number_format($this->salary_cents / 100, 2, ',', '.') : null,
            'notes' => $this->notes,
            'metadata' => $this->metadata ?? (object) [],
            'created_by_user_id' => $this->created_by_user_id,
            'created_at' => $fmt($this->created_at),
            'updated_at' => $fmt($this->updated_at),
            'deleted_at' => $fmt($this->deleted_at),
            'service_teams_count' => $this->whenCounted('serviceTeams'),
            'user' => new UserResource($this->whenLoaded('user')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'service_teams' => ServiceTeamResource::collection($this->whenLoaded('serviceTeams')),
        ];
    }
}
