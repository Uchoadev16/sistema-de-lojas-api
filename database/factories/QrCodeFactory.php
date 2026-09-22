<?php

namespace Database\Factories;

use App\Models\QrCode;
use App\Support\Enums\QrCodeEntityType;
use App\Support\Enums\QrCodeStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class QrCodeFactory extends Factory
{
    protected $model = QrCode::class;

    public function definition(): array
    {
        return [
            'code' => 'QR-'.strtoupper(Str::random(20)),
            'entity_type' => fake()->randomElement(array_column(QrCodeEntityType::cases(), 'value')),
            'entity_id' => fake()->optional()->uuid(),
            'linked_at' => fake()->optional()->dateTimeBetween('-1 year', 'now'),
            'generated_by_user_id' => fake()->optional()->uuid(),
            'status' => fake()->randomElement(array_column(QrCodeStatus::cases(), 'value')),
            'printed' => fake()->boolean(30),
            'is_active' => true,
            'metadata' => [
                'generated_source' => fake()->randomElement(['web', 'mobile', 'batch']),
                'batch_number' => fake()->optional()->bothify('BATCH-###'),
            ],
        ];
    }

    public function linked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => QrCodeStatus::Linked->value,
            'linked_at' => now(),
            'entity_id' => Str::uuid(),
        ]);
    }

    public function unlinked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => QrCodeStatus::Unlinked->value,
            'linked_at' => null,
            'entity_id' => null,
        ]);
    }
}
