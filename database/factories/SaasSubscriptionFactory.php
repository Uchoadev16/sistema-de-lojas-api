<?php

namespace Database\Factories;

use App\Models\SaasPlan;
use App\Models\SaasSubscription;
use App\Models\Tenant;
use App\Support\Enums\BillingCycle;
use App\Support\Enums\SaasSubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaasSubscriptionFactory extends Factory
{
    protected $model = SaasSubscription::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'saas_plan_id' => SaasPlan::factory(),
            'status' => SaasSubscriptionStatus::Trialing->value,
            'is_active' => true,
            'billing_cycle' => BillingCycle::Monthly->value,
            'amount' => $this->faker->randomFloat(2, 49, 499),
            'trial_ends_at' => now()->addDays(14),
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
        ];
    }
}
