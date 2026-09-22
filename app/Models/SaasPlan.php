<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use App\Support\Enums\BillingCycle;
use App\Support\Enums\SaasPlanStatus;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaasPlan extends BaseGlobalModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'code',
        'trial_days',
        'grace_days',
        'billing_cycle',
        'price_monthly_cents',
        'price_annual_cents',
        'currency',
        'max_tenants_per_user',
        'max_users_per_tenant',
        'max_technicians_per_tenant',
        'storage_mb',
        'max_contracts',
        'max_work_orders_per_month',
        'support_pmoc',
        'support_inventory',
        'support_billing',
        'portal_cliente',
        'priority_support',
        'sla_hours',
        'features',
        'limits',
        'settings',
        'is_active',
        'status',
        'metadata',
    ];

    protected $casts = [
        'status' => SaasPlanStatus::class,
        'billing_cycle' => BillingCycle::class,
        'price_monthly' => 'decimal:2',
        'price_annual' => 'decimal:2',
        'price_biennial' => 'decimal:2',
        'support_pmoc' => 'boolean',
        'support_qrcode' => 'boolean',
        'support_mobile' => 'boolean',
        'support_billing' => 'boolean',
        'support_inventory' => 'boolean',
        'support_reports' => 'boolean',
        'support_portal_cliente' => 'boolean',
        'support_api' => 'boolean',
        'features' => 'json',
        'limits' => 'json',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(SaasSubscription::class);
    }

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }
}
