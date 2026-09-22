<?php

namespace App\Http\Resources\V1;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Address $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'address_type' => $this->address_type,
            'postal_code' => $this->postal_code,
            'street' => $this->street,
            'number' => $this->number,
            'complement' => $this->complement,
            'neighborhood' => $this->neighborhood,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'metadata' => $this->metadata,
            'pivot' => $this->whenPivotLoaded('customer_addresses', fn () => [
                'type' => $this->pivot->type ?? null,
                'is_primary' => isset($this->pivot->is_primary) ? (bool) $this->pivot->is_primary : null,
            ]),
            'created_at' => $fmt($this->created_at),
            'updated_at' => $fmt($this->updated_at),
        ];
    }
}
