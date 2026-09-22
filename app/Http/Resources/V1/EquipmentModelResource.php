<?php

namespace App\Http\Resources\V1;

use App\Models\EquipmentModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentModelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var EquipmentModel $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'technical_specifications' => $this->technical_specifications ?? (object) [],
            'created_at' => $fmt($this->created_at),
            'updated_at' => $fmt($this->updated_at),
            'equipments_count' => $this->whenCounted('equipments'),
            'brand' => new EquipmentBrandResource($this->whenLoaded('brand')),
            'category' => new EquipmentCategoryResource($this->whenLoaded('category')),
        ];
    }
}
