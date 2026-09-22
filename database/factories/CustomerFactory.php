<?php

namespace Database\Factories;

use App\Support\Enums\CustomerStatus;
use App\Support\Enums\CustomerType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement([CustomerType::Company->value, CustomerType::Individual->value]);

        if ($type === CustomerType::Company->value) {
            $name = fake()->company();

            return [
                'customer_type' => $type,
                'name' => $name,
                'legal_name' => fake()->companySuffix().' '.$name,
                'trade_name' => $name,
                'tax_id' => fake()->unique()->cnpj(false),
                'state_registration' => fake()->optional()->numerify('###########'),
                'email' => fake()->unique()->companyEmail(),
                'phone' => fake()->landlineNumber(),
                'mobile' => fake()->cellphoneNumber(),
                'status' => fake()->randomElement(array_column(CustomerStatus::cases(), 'value')),
                'is_active' => true,
                'notes' => fake()->optional()->paragraph(),
                'preferences' => fake()->optional()->passthrough(['notifications' => ['email' => true, 'sms' => false]]),
                'metadata' => fake()->optional()->passthrough(['source' => fake()->randomElement(['website', 'indicacao', 'marketing'])]),
            ];
        }

        return [
            'customer_type' => $type,
            'name' => fake()->name(),
            'legal_name' => fake()->name(),
            'tax_id' => fake()->unique()->cpf(false),
            'state_registration' => fake()->optional()->numerify('###########'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->landlineNumber(),
            'mobile' => fake()->cellphoneNumber(),
            'status' => fake()->randomElement(array_column(CustomerStatus::cases(), 'value')),
            'is_active' => true,
            'notes' => fake()->optional()->paragraph(),
            'preferences' => fake()->optional()->passthrough(['notifications' => ['email' => true, 'sms' => false]]),
            'metadata' => fake()->optional()->passthrough(['source' => fake()->randomElement(['website', 'indicacao', 'marketing'])]),
        ];
    }

    public function lead(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CustomerStatus::Lead->value,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CustomerStatus::Active->value,
            'is_active' => true,
        ]);
    }
}
