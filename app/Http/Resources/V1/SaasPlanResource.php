<?php

namespace App\Http\Resources\V1;

use App\Models\SaasPlan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaasPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var SaasPlan $this */
        $fmt = static fn ($v, $m = 'toIso8601String'): ?string => $v ? (is_string($v) ? $v : (method_exists($v, $m) ? $v->{$m}() : (string) $v)) : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status?->value ?? $this->status,
            'is_active' => (bool) $this->is_active,
            'billing_cycle' => $this->billing_cycle?->value ?? $this->billing_cycle,
            'price_monthly' => (float) $this->price_monthly,
            'price_annual' => (float) $this->price_annual,
            'price_biennial' => $this->price_biennial ? (float) $this->price_biennial : null,
            'trial_days' => $this->trial_days,
            'max_users' => $this->max_users,
            'max_customers' => $this->max_customers,
            'max_equipment' => $this->max_equipment,
            'max_technicians' => $this->max_technicians,
            'storage_mb' => $this->storage_mb,
            'features' => $this->features ?? (object) [],
            'limits' => $this->limits ?? (object) [],
            'support_pmoc' => (bool) $this->support_pmoc,
            'support_qrcode' => (bool) $this->support_qrcode,
            'support_mobile' => (bool) $this->support_mobile,
            'support_billing' => (bool) $this->support_billing,
            'support_inventory' => (bool) $this->support_inventory,
            'support_reports' => (bool) $this->support_reports,
            'support_portal_cliente' => (bool) $this->support_portal_cliente,
            'support_api' => (bool) $this->support_api,
            'sort_order' => $this->sort_order,
            'created_at' => $fmt($this->created_at),
        ];
    }
}
