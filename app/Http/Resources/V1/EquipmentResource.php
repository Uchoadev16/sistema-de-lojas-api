<?php

namespace App\Http\Resources\V1;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Equipment $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;
        $fmtDateOnly = static fn ($v): ?string => $v ? (is_string($v) ? $v : (method_exists($v, 'toDateString') ? $v->toDateString() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'customer_id' => $this->customer_id,
            'unit_id' => $this->unit_id,
            'environment_id' => $this->environment_id,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'model_id' => $this->model_id,
            'identifier' => $this->identifier,
            'asset_tag' => $this->asset_tag,
            'serial_number' => $this->serial_number,
            'equipment_type' => $this->equipment_type,
            'manufacturer' => $this->manufacturer,
            'model_name' => $this->model_name,
            'capacity' => $this->capacity,
            'capacity_unit' => $this->capacity_unit,
            'refrigerant' => $this->refrigerant,
            'voltage' => $this->voltage,
            'power_kw' => $this->power_kw,
            'installed_at' => $fmt($this->installed_at),
            'warranty_start' => $fmt($this->warranty_start),
            'warranty_end' => $fmt($this->warranty_end),
            'purchase_date' => $fmtDateOnly($this->purchase_date),
            'purchase_price_cents' => (int) $this->purchase_price_cents,
            'purchase_price' => $this->purchase_price_cents ? number_format($this->purchase_price_cents / 100, 2, ',', '.') : null,
            'status' => $this->status?->value ?? $this->status,
            'is_active' => (bool) $this->is_active,
            'qr_code_value' => $this->qr_code_value,
            'condition' => $this->condition?->value ?? $this->condition,
            'last_maintenance_at' => $fmt($this->last_maintenance_at),
            'next_maintenance_at' => $fmt($this->next_maintenance_at),
            'description' => $this->description,
            'notes' => $this->notes,
            'external_id' => $this->external_id,
            'metadata' => $this->metadata ?? (object) [],
            'created_by_user_id' => $this->created_by_user_id,
            'created_at' => $fmt($this->created_at),
            'updated_at' => $fmt($this->updated_at),
            'deleted_at' => $fmt($this->deleted_at),
            'category' => new EquipmentCategoryResource($this->whenLoaded('category')),
            'brand' => new EquipmentBrandResource($this->whenLoaded('brand')),
            'model' => new EquipmentModelResource($this->whenLoaded('model')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'qr_code' => new QrCodeResource($this->whenLoaded('qrCode')),
        ];
    }
}
