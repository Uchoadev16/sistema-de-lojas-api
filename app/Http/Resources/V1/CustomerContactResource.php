<?php

namespace App\Http\Resources\V1;

use App\Models\CustomerContact;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var CustomerContact $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'customer_id' => $this->customer_id,
            'name' => $this->name,
            'position' => $this->position,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'is_primary' => (bool) $this->is_primary,
            'receives_notifications' => (bool) $this->receives_notifications,
            'notes' => $this->notes,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'created_at' => $fmt($this->created_at),
            'updated_at' => $fmt($this->updated_at),
            'deleted_at' => $fmt($this->deleted_at),
        ];
    }
}
