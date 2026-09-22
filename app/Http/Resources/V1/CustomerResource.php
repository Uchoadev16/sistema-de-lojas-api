<?php

namespace App\Http\Resources\V1;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Customer $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'customer_type' => $this->customer_type?->value ?? $this->customer_type,
            'name' => $this->name,
            'legal_name' => $this->legal_name,
            'trade_name' => $this->trade_name,
            'tax_id' => $this->tax_id,
            'state_registration' => $this->state_registration,
            'email' => $this->email,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'status' => $this->status?->value ?? $this->status,
            'is_active' => (bool) $this->is_active,
            'notes' => $this->notes,
            'external_id' => $this->external_id,
            'created_by_user_id' => $this->created_by_user_id,
            'preferences' => $this->preferences,
            'metadata' => $this->metadata,
            'contacts' => CustomerContactResource::collection($this->whenLoaded('contacts')),
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'units' => UnitResource::collection($this->whenLoaded('units')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'contacts_count' => $this->whenCounted('contacts'),
            'units_count' => $this->whenCounted('units'),
            'tags_count' => $this->whenCounted('tags'),
            'created_at' => $fmt($this->created_at),
            'updated_at' => $fmt($this->updated_at),
            'deleted_at' => $fmt($this->deleted_at),
        ];
    }
}
