<?php

namespace Database\Factories;

use App\Models\ServiceTeam;
use App\Models\Technician;
use App\Support\Enums\ServiceTeamStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceTeamFactory extends Factory
{
    protected $model = ServiceTeam::class;

    public function definition(): array
    {
        $technician = Technician::factory()->active()->create();

        return [
            'name' => 'Equipe '.fake()->citySuffix().' '.strtoupper(Str::random(2)),
            'code' => 'TEAM-'.strtoupper(Str::random(5)),
            'description' => fake()->text(200),
            'status' => fake()->randomElement(array_column(ServiceTeamStatus::cases(), 'value')),
            'is_active' => true,
            'leader_technician_id' => $technician->id,
            'notes' => fake()->optional()->text(200),
            'metadata' => [
                'vehicle_plate' => fake()->optional()->bothify('???-####'),
                'coverage_area' => fake()->randomElement(['Capital', 'Interior', 'Litoral', 'Metropolitana']),
            ],
            'created_by_user_id' => fake()->optional()->uuid(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ServiceTeamStatus::Active->value,
            'is_active' => true,
        ]);
    }
}
