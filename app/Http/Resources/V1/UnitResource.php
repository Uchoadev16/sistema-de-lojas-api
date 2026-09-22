<?php

namespace App\Http\Resources\V1;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Unit $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'customer_id' => $this->customer_id,
            'address_id' => $this->address_id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'status' => $this->status?->value ?? $this->status,
            'is_active' => (bool) $this->is_active,
            'responsible_contact_id' => $this->responsible_contact_id,
            'notes' => $this->notes,
            'external_id' => $this->external_id,
            'area_m2' => $this->area_m2,
            'floors' => (int) $this->floors,
            'metadata' => $this->metadata,
            'created_by_user_id' => $this->created_by_user_id,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'address' => new AddressResource($this->whenLoaded('address')),
            'environments' => EnvironmentResource::collection($this->whenLoaded('environments')),
            'responsible_contact' => new CustomerContactResource($this->whenLoaded('responsibleContact')),
            'environments_count' => $this->whenCounted('environments'),
            'created_at' => $fmt($this->created_at),
            'updated_at' => $fmt($this->updated_at),
            'deleted_at' => $fmt($this->deleted_at),
        ];
    }
}
