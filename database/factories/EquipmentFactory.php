<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\EquipmentBrand;
use App\Models\EquipmentCategory;
use App\Models\EquipmentModel;
use App\Support\Enums\EquipmentCondition;
use App\Support\Enums\EquipmentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;

    public function definition(): array
    {
        $category = EquipmentCategory::factory()->create();
        $brand = EquipmentBrand::factory()->create();
        $model = EquipmentModel::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);

        return [
            'customer_id' => Str::uuid(),
            'unit_id' => fake()->optional()->uuid(),
            'environment_id' => fake()->optional()->uuid(),
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'model_id' => $model->id,
            'identifier' => 'EQ-'.strtoupper(Str::random(8)),
            'asset_tag' => 'AT-'.strtoupper(Str::random(6)),
            'serial_number' => strtoupper(Str::random(12)),
            'equipment_type' => fake()->randomElement(['air_conditioner', 'chiller', 'fan_coil', 'split', 'window']),
            'manufacturer' => $brand->name,
            'model_name' => $model->name,
            'capacity' => fake()->randomFloat(2, 9000, 36000),
            'capacity_unit' => 'btu',
            'refrigerant' => fake()->randomElement(['R-22', 'R-410A', 'R-32', 'R-134a']),
            'voltage' => fake()->randomElement(['220V', '110V']),
            'power_kw' => fake()->randomFloat(3, 0.5, 5),
            'installed_at' => fake()->dateTimeBetween('-5 years', 'now'),
            'warranty_start' => fake()->dateTimeBetween('-3 years', 'now'),
            'warranty_end' => fake()->dateTimeBetween('now', '+2 years'),
            'purchase_date' => fake()->dateTimeBetween('-5 years', 'now'),
            'purchase_price_cents' => fake()->numberBetween(100000, 1500000),
            'status' => fake()->randomElement(array_column(EquipmentStatus::cases(), 'value')),
            'is_active' => true,
            'qr_code_value' => fake()->optional()->sha1(),
            'condition' => fake()->randomElement(array_column(EquipmentCondition::cases(), 'value')),
            'last_maintenance_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'next_maintenance_at' => fake()->dateTimeBetween('now', '+6 months'),
            'description' => fake()->text(300),
            'notes' => fake()->optional()->text(200),
            'external_id' => fake()->optional()->bothify('EXT-###-???'),
            'metadata' => [
                'installation_address' => fake()->address(),
                'technical_notes' => fake()->text(150),
            ],
            'created_by_user_id' => fake()->optional()->uuid(),
        ];
    }
}
