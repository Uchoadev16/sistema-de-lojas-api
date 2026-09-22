<?php

namespace Database\Factories;

use App\Models\EquipmentBrand;
use App\Models\EquipmentCategory;
use App\Models\EquipmentModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EquipmentModelFactory extends Factory
{
    protected $model = EquipmentModel::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'brand_id' => EquipmentBrand::factory(),
            'category_id' => EquipmentCategory::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->text(200),
            'image_url' => 'https://picsum.photos/seed/'.Str::random(10).'/400/300',
            'technical_specifications' => [
                'btu' => fake()->randomElement([9000, 12000, 18000, 24000, 30000, 36000]),
                'voltage' => fake()->randomElement(['220V', '110V']),
                'cycle' => fake()->randomElement(['Hot/Cold', 'Cold Only']),
                'energy_rating' => fake()->randomElement(['A', 'B', 'C', 'D']),
            ],
        ];
    }
}
