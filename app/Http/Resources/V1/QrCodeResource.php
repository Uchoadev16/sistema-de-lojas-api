<?php

namespace App\Http\Resources\V1;

use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QrCodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var QrCode $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'code' => $this->code,
            'entity_type' => $this->entity_type,
            'entity_id' => $this->entity_id,
            'linked_at' => $fmt($this->linked_at),
            'generated_by_user_id' => $this->generated_by_user_id,
            'status' => $this->status?->value ?? $this->status,
            'printed' => (bool) $this->printed,
            'is_active' => (bool) $this->is_active,
            'metadata' => $this->metadata ?? (object) [],
            'created_at' => $fmt($this->created_at),
            'updated_at' => $fmt($this->updated_at),
            'deleted_at' => $fmt($this->deleted_at),
            'generated_by' => new UserResource($this->whenLoaded('generatedBy')),
            'entity_equipment' => new EquipmentResource($this->whenLoaded('entityEquipment')),
        ];
    }
}
