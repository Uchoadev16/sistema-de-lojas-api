<?php

namespace Database\Factories;

use App\Support\Enums\UnitStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Unidade '.fake()->city(),
            'code' => strtoupper(fake()->bothify('UN-####')),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(array_column(UnitStatus::cases(), 'value')),
            'is_active' => true,
            'notes' => fake()->optional()->sentence(),
            'area_m2' => fake()->optional()->randomFloat(2, 50, 5000),
            'floors' => fake()->numberBetween(1, 20),
            'metadata' => fake()->optional()->passthrough(['type' => fake()->randomElement(['comercial', 'residencial', 'industrial'])]),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => UnitStatus::Active->value,
            'is_active' => true,
        ]);
    }
}
