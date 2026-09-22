<?php

namespace App\Http\Resources\V1;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Tag $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'color' => $this->color,
            'description' => $this->description,
            'is_system' => (bool) $this->is_system,
            'customers_count' => $this->whenCounted('customers'),
            'created_at' => $fmt($this->created_at),
            'updated_at' => $fmt($this->updated_at),
        ];
    }
}
