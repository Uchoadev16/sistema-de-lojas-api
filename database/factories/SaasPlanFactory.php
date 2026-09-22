<?php

namespace Database\Factories;

use App\Models\SaasPlan;
use App\Support\Enums\BillingCycle;
use App\Support\Enums\SaasPlanStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SaasPlanFactory extends Factory
{
    protected $model = SaasPlan::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'status' => SaasPlanStatus::Active->value,
            'is_active' => true,
            'billing_cycle' => BillingCycle::Monthly->value,
            'price_monthly' => fake()->randomFloat(2, 49, 499),
            'price_annual' => fake()->randomFloat(2, 499, 4990),
            'trial_days' => 14,
            'max_users' => fake()->randomElement([3, 10, 30, null]),
            'max_customers' => fake()->randomElement([50, 500, 5000, null]),
            'max_equipment' => fake()->randomElement([50, 500, 10000, null]),
            'max_technicians' => fake()->randomElement([2, 5, 20, null]),
            'support_pmoc' => fake()->boolean(70),
            'support_qrcode' => true,
            'support_mobile' => true,
            'support_billing' => fake()->boolean(60),
            'support_inventory' => fake()->boolean(60),
            'support_reports' => true,
            'support_portal_cliente' => fake()->boolean(50),
            'support_api' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    public function trialFree(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Trial Gratuito',
            'slug' => 'trial',
            'price_monthly' => 0,
            'price_annual' => 0,
            'trial_days' => 14,
        ]);
    }
}
