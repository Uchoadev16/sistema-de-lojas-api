<?php

namespace Database\Factories;

use App\Support\Enums\EnvironmentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnvironmentFactory extends Factory
{
    public function definition(): array
    {
        $names = ['Sala de Reuniões', 'Escritório', 'Auditório', 'Corredor', 'Banheiro', 'Copa', 'Estacionamento', 'Depósito', 'Recepção', 'Sala Técnica'];
        $purposes = ['Escritório', 'Reunião', 'Armazenamento', 'Atendimento', 'Serviços Gerais', 'Técnico'];

        return [
            'name' => fake()->randomElement($names).' '.fake()->randomLetter(),
            'code' => strtoupper(fake()->bothify('AMB-###')),
            'floor' => fake()->optional()->randomElement(['Térreo', '1º Andar', '2º Andar', '3º Andar', 'Subsolo']),
            'area' => fake()->optional()->randomFloat(2, 10, 500),
            'purpose' => fake()->optional()->randomElement($purposes),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(array_column(EnvironmentStatus::cases(), 'value')),
            'is_active' => true,
            'metadata' => fake()->optional()->passthrough(['capacity' => fake()->numberBetween(1, 100)]),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EnvironmentStatus::Active->value,
            'is_active' => true,
        ]);
    }
}
