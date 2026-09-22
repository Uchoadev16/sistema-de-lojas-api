<?php

namespace Database\Factories;

use App\Support\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'status' => UserStatus::Active->value,
            'is_active' => true,
            'document' => fake()->unique()->cpf(false),
            'phone' => fake()->cellphoneNumber(),
            'mobile' => fake()->cellphoneNumber(),
            'birth_date' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'locale' => 'pt_BR',
            'timezone' => 'America/Sao_Paulo',
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function platformAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_platform_admin' => true,
        ]);
    }

    public function blocked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserStatus::Blocked->value,
            'is_active' => false,
        ]);
    }
}
