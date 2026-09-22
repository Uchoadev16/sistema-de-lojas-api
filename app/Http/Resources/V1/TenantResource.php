<?php

namespace App\Http\Resources\V1;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Tenant $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'legal_name' => $this->legal_name,
            'trade_name' => $this->trade_name,
            'slug' => $this->slug,
            'document' => $this->document,
            'ie_rg' => $this->ie_rg,
            'im' => $this->im,
            'status' => $this->status?->value ?? $this->status,
            'is_active' => (bool) $this->is_active,
            'email' => $this->email,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'website' => $this->website,
            'logo_url' => $this->logo_path ? \Storage::disk('public')->url($this->logo_path) : null,
            'banner_url' => $this->banner_path ? \Storage::disk('public')->url($this->banner_path) : null,
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,

            'address' => [
                'street' => $this->address_street,
                'number' => $this->address_number,
                'complement' => $this->address_complement,
                'neighborhood' => $this->address_neighborhood,
                'city' => $this->address_city,
                'state' => $this->address_state,
                'postal_code' => $this->address_postal_code,
                'latitude' => $this->address_latitude,
                'longitude' => $this->address_longitude,
            ],

            'timezone' => $this->timezone,
            'locale' => $this->locale,
            'currency' => $this->currency,
            'date_format' => $this->date_format,
            'about' => $this->about,
            'business_hours' => $this->business_hours,

            'saas_plan' => new SaasPlanResource($this->whenLoaded('saasPlan')),

            'settings' => $this->settings ?? (object) [],
            'preferences' => $this->preferences ?? (object) [],

            'trial_ends_at' => $fmt($this->trial_ends_at),
            'suspended_at' => $fmt($this->suspended_at),
            'cancelled_at' => $fmt($this->cancelled_at),
            'onboarded_at' => $fmt($this->onboarded_at),

            'created_at' => $fmt($this->created_at),
            'updated_at' => $fmt($this->updated_at),
            'deleted_at' => $fmt($this->deleted_at),
        ];
    }
}
