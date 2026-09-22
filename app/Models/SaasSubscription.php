<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use App\Support\Enums\BillingCycle;
use App\Support\Enums\SaasSubscriptionStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaasSubscription extends BaseGlobalModel
{
    use SoftDeletes;

    protected $fillable = [
        'saas_plan_id',
        'status',
        'is_active',
        'billing_cycle',
        'quantity',
        'currency',
        'unit_price_cents',
        'trial_ends_at',
        'current_period_start',
        'current_period_end',
        'starts_at',
        'ends_at',
        'canceled_at',
        'cancel_at_period_end',
        'next_billing_at',
        'paid_at',
        'payment_provider',
        'payment_method',
        'payment_reference',
        'last_payment_at',
        'last_payment_amount_cents',
        'features_override',
        'limits_override',
        'settings',
        'downgrade_to_plan_id',
        'metadata',
        'notes',
    ];

    protected $casts = [
        'status' => SaasSubscriptionStatus::class,
        'billing_cycle' => BillingCycle::class,
        'is_active' => 'boolean',
        'amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'trial_ends_at' => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'cancel_at_period_end' => 'datetime',
        'canceled_at' => 'datetime',
        'ended_at' => 'datetime',
        'paused_at' => 'datetime',
        'resumes_at' => 'datetime',
        'metadata' => 'json',
        'plan_snapshot' => 'json',
        'billing_details' => 'json',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function saasPlan(): BelongsTo
    {
        return $this->belongsTo(SaasPlan::class, 'saas_plan_id');
    }
}
