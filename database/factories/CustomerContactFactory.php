<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'position' => fake()->optional()->jobTitle(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional()->landlineNumber(),
            'whatsapp' => fake()->optional()->cellphoneNumber(),
            'is_primary' => false,
            'receives_notifications' => true,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
        ]);
    }
}
