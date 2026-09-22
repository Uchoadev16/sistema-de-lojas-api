<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Support\Enums\UserTenantStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserTenantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tenant_id' => Tenant::factory(),
            'role_id' => Role::factory(),
            'status' => UserTenantStatus::Active->value,
            'is_active' => true,
            'is_default' => true,
            'is_owner' => false,
            'accepted_at' => now(),
        ];
    }

    public function owner(): static
    {
        return $this->state(fn (array $a) => ['is_owner' => true]);
    }

    public function invited(): static
    {
        return $this->state(fn (array $a) => [
            'status' => UserTenantStatus::Invited->value,
            'accepted_at' => null,
            'invited_at' => now(),
            'invite_token' => Str::random(40),
            'invite_expires_at' => now()->addDays(7),
        ]);
    }
}
