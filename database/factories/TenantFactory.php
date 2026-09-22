<?php

namespace Database\Factories;

use App\Support\Enums\TenantStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TenantFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'legal_name' => $name.' LTDA',
            'trade_name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(6),
            'document' => fake()->unique()->cnpj(false),
            'status' => TenantStatus::Active->value,
            'is_active' => true,
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->landlineNumber(),
            'mobile' => fake()->cellphoneNumber(),
            'address_street' => fake()->streetName(),
            'address_number' => (string) fake()->randomNumber(3),
            'address_neighborhood' => fake()->word(),
            'address_city' => fake()->city(),
            'address_state' => fake()->stateAbbr(),
            'address_postal_code' => fake()->postcode(),
            'timezone' => 'America/Sao_Paulo',
            'locale' => 'pt_BR',
            'currency' => 'BRL',
            'date_format' => 'DD/MM/YYYY',
            'trial_ends_at' => now()->addDays(14),
        ];
    }

    public function trial(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TenantStatus::Trial->value,
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TenantStatus::Suspended->value,
            'is_active' => false,
            'suspended_at' => now(),
        ]);
    }
}
