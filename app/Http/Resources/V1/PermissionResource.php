<?php

namespace App\Http\Resources\V1;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Permission $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'group' => $this->group,
            'module' => $this->module,
            'description' => $this->description,
            'scope' => $this->scope,
            'is_system' => (bool) $this->is_system,
            'is_active' => (bool) $this->is_active,
            'created_at' => $fmt($this->created_at),
            'updated_at' => $fmt($this->updated_at),
            'deleted_at' => $fmt($this->deleted_at),
        ];
    }
}
