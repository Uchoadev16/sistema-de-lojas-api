<?php

namespace App\Http\Resources\V1;

use App\Models\EquipmentCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var EquipmentCategory $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'description' => $this->description,
            'is_system' => (bool) $this->is_system,
            'color' => $this->color,
            'created_at' => $fmt($this->created_at),
            'updated_at' => $fmt($this->updated_at),
            'equipment_models_count' => $this->whenCounted('equipment_models'),
            'equipments_count' => $this->whenCounted('equipments'),
            'equipment_models' => EquipmentModelResource::collection($this->whenLoaded('equipment_models')),
        ];
    }
}
