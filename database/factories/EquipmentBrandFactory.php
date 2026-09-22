<?php

namespace Database\Factories;

use App\Models\EquipmentBrand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EquipmentBrandFactory extends Factory
{
    protected $model = EquipmentBrand::class;

    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->text(200),
            'website' => fake()->url(),
            'logo_url' => 'https://picsum.photos/seed/'.Str::random(10).'/200/100',
            'is_system' => false,
        ];
    }

    public function system(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_system' => true,
        ]);
    }
}
