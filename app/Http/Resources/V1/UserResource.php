<?php

namespace App\Http\Resources\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var User $this */
        $fmtDate = static fn ($v, $method = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $method) ? $v->{$method}() : (string) $v)) : null;
        $fmtDateOnly = static fn ($v): ?string => $v ? (is_string($v) ? $v : (method_exists($v, 'toDateString') ? $v->toDateString() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $fmtDate($this->email_verified_at),
            'document' => $this->document,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'birth_date' => $fmtDateOnly($this->birth_date),
            'gender' => $this->gender,
            'avatar_url' => $this->avatar_path
                ? (\Storage::disk($this->avatarDisk ?? 'public')->url($this->avatar_path))
                : null,
            'status' => $this->status?->value ?? $this->status,
            'is_active' => (bool) $this->is_active,
            'is_platform_admin' => (bool) $this->is_platform_admin,
            'mfa_enabled' => (bool) $this->mfa_enabled,
            'timezone' => $this->timezone,
            'locale' => $this->locale,
            'preferences' => $this->preferences ?? (object) [],
            'last_login_at' => $fmtDate($this->last_login_at),
            'last_login_ip' => $this->last_login_ip,
            'created_at' => $fmtDate($this->created_at),
            'updated_at' => $fmtDate($this->updated_at),
            'deleted_at' => $fmtDate($this->deleted_at),
            'roles' => RoleResource::collection($this->whenLoaded('userTenants.role')),
            'tenants' => TenantResource::collection($this->whenLoaded('tenants')),
        ];
    }
}
