<?php

namespace App\Http\Resources\V1;

use App\Models\ServiceTeam;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceTeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var ServiceTeam $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'status' => $this->status?->value ?? $this->status,
            'is_active' => (bool) $this->is_active,
            'leader_technician_id' => $this->leader_technician_id,
            'notes' => $this->notes,
            'metadata' => $this->metadata ?? (object) [],
            'created_by_user_id' => $this->created_by_user_id,
            'created_at' => $fmt($this->created_at),
            'updated_at' => $fmt($this->updated_at),
            'deleted_at' => $fmt($this->deleted_at),
            'technicians_count' => $this->whenCounted('technicians'),
            'leader' => new TechnicianResource($this->whenLoaded('leader')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'technicians' => TechnicianResource::collection($this->whenLoaded('technicians')),
        ];
    }
}
