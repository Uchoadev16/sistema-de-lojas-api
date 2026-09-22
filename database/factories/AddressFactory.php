<?php

namespace Database\Factories;

use App\Support\Enums\AddressType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'address_type' => fake()->randomElement(array_column(AddressType::cases(), 'value')),
            'postal_code' => fake()->postcode(),
            'street' => fake()->streetName(),
            'number' => (string) fake()->randomNumber(4),
            'complement' => fake()->optional()->buildingNumber(),
            'neighborhood' => fake()->optional()->citySuffix(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'country' => 'Brasil',
            'latitude' => fake()->optional()->latitude(-34, 5),
            'longitude' => fake()->optional()->longitude(-74, -34),
            'metadata' => fake()->optional()->passthrough(['ibge_code' => fake()->randomNumber(7)]),
        ];
    }

    public function commercial(): static
    {
        return $this->state(fn (array $attributes) => [
            'address_type' => AddressType::Commercial->value,
        ]);
    }

    public function service(): static
    {
        return $this->state(fn (array $attributes) => [
            'address_type' => AddressType::Service->value,
        ]);
    }
}
