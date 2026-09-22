<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;
        $ts = static fn ($ts): ?string => $ts ? Carbon::createFromTimestamp($ts)->toIso8601String() : null;
        $diff = static fn ($ts): ?string => $ts ? Carbon::createFromTimestamp($ts)->diffForHumans() : null;

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'tenant_id' => $this->tenant_id,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'device_platform' => $this->device_platform?->value ?? $this->device_platform,
            'device_name' => $this->device_name,
            'device_model' => $this->device_model,
            'device_os_version' => $this->device_os_version,
            'app_version' => $this->app_version,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'last_activity_human' => $diff($this->last_activity),
            'last_activity_at' => $ts($this->last_activity),
            'expires_at' => $fmt($this->expires_at),
            'is_current' => $this->id === $request->session()?->getId(),
        ];
    }
}
