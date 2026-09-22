<?php

namespace Database\Factories;

use App\Models\Invite;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InviteFactory extends Factory
{
    protected $model = Invite::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'invited_by_user_id' => User::factory(),
            'role_id' => Role::factory(),
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'status' => 'pending',
            'token' => Str::random(40),
            'expires_at' => now()->addDays(7),
            'sent_at' => now(),
        ];
    }
}
