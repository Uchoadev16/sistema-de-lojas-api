<?php

namespace Database\Factories;

use App\Models\Technician;
use App\Support\Enums\TechnicianAvailability;
use App\Support\Enums\TechnicianStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TechnicianFactory extends Factory
{
    protected $model = Technician::class;

    public function definition(): array
    {
        return [
            'user_id' => fake()->optional()->uuid(),
            'name' => fake()->name(),
            'document' => fake()->unique()->cpf(false),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->landlineNumber(),
            'mobile' => fake()->cellphoneNumber(),
            'whatsapp' => fake()->cellphoneNumber(),
            'photo_url' => 'https://picsum.photos/seed/'.uniqid().'/200/200',
            'specialty' => fake()->randomElement(['Ar Condicionado Split', 'Ar Condicionado Central', 'Refrigeração Comercial', 'Ventilação', 'Automação']),
            'professional_registration' => fake()->optional()->bothify('CREA-########'),
            'service_region' => fake()->randomElement(['Zona Norte', 'Zona Sul', 'Zona Leste', 'Zona Oeste', 'Centro', 'Grande ABC']),
            'color' => fake()->randomElement(['default', 'blue', 'green', 'orange', 'red', 'purple']),
            'status' => fake()->randomElement(array_column(TechnicianStatus::cases(), 'value')),
            'availability_status' => fake()->randomElement(array_column(TechnicianAvailability::cases(), 'value')),
            'hire_date' => fake()->dateTimeBetween('-5 years', 'now'),
            'termination_date' => fake()->optional()->dateTimeBetween('-1 year', 'now'),
            'salary_cents' => fake()->numberBetween(200000, 800000),
            'notes' => fake()->optional()->text(200),
            'metadata' => [
                'emergency_contact' => fake()->cellphoneNumber(),
                'blood_type' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
                'shirt_size' => fake()->randomElement(['P', 'M', 'G', 'GG', 'XG']),
            ],
            'created_by_user_id' => fake()->optional()->uuid(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TechnicianStatus::Active->value,
            'termination_date' => null,
        ]);
    }
}
