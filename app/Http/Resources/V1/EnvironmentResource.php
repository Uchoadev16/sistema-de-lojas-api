<?php

namespace App\Http\Resources\V1;

use App\Models\Environment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnvironmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Environment $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'unit_id' => $this->unit_id,
            'name' => $this->name,
            'code' => $this->code,
            'floor' => $this->floor,
            'area' => $this->area,
            'purpose' => $this->purpose,
            'description' => $this->description,
            'status' => $this->status?->value ?? $this->status,
            'is_active' => (bool) $this->is_active,
            'metadata' => $this->metadata,
            'unit' => new UnitResource($this->whenLoaded('unit')),
            'created_at' => $fmt($this->created_at),
            'updated_at' => $fmt($this->updated_at),
            'deleted_at' => $fmt($this->deleted_at),
        ];
    }
}
