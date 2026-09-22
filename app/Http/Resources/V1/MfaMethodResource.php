<?php

namespace App\Http\Resources\V1;

use App\Models\MfaMethod;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MfaMethodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var MfaMethod $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'method_type' => $this->method_type?->value ?? $this->method_type,
            'label' => $this->label,
            'is_default' => (bool) $this->is_default,
            'is_enabled' => (bool) $this->is_enabled,
            'last_used_at' => $fmt($this->last_used_at),
            'verified_at' => $fmt($this->verified_at),
            'phone_number_masked' => $this->phone_number ? $this->maskPhone($this->phone_number) : null,
            'email_address_masked' => $this->email_address ? $this->maskEmail($this->email_address) : null,
            'created_at' => $fmt($this->created_at),
        ];
    }

    protected function maskPhone(string $phone): string
    {
        $len = strlen($phone);
        if ($len <= 6) {
            return str_repeat('*', $len);
        }

        return substr($phone, 0, 3).str_repeat('*', $len - 5).substr($phone, -2);
    }

    protected function maskEmail(string $email): string
    {
        [$local, $domain] = array_pad(explode('@', $email, 2), 2, '');
        if (! $domain) {
            return $email;
        }
        $len = strlen($local);
        if ($len <= 2) {
            return str_repeat('*', $len).'@'.$domain;
        }

        return $local[0].str_repeat('*', $len - 2).$local[$len - 1].'@'.$domain;
    }
}
