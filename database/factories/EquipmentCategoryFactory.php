<?php

namespace Database\Factories;

use App\Models\EquipmentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EquipmentCategoryFactory extends Factory
{
    protected $model = EquipmentCategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'code' => strtoupper(Str::random(6)),
            'description' => fake()->text(200),
            'is_system' => false,
            'color' => fake()->randomElement(['blue', 'green', 'orange', 'red', 'purple']),
        ];
    }

    public function system(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_system' => true,
        ]);
    }
}
