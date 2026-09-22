<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $names = ['Técnico', 'Supervisor', 'Administrativo', 'Financeiro', 'Vendedor'];
        $name = $this->faker->unique()->randomElement($names);
        $slug = Str::slug($name);

        return [
            'tenant_id' => Tenant::factory(),
            'name' => $name,
            'slug' => $slug,
            'description' => $this->faker->sentence(),
            'scope' => 'tenant',
            'is_system' => false,
            'is_active' => true,
            'sort_order' => rand(0, 10),
        ];
    }

    public function system(?string $name = null, ?string $slug = null): static
    {
        return $this->state(fn (array $attrs) => [
            'tenant_id' => null,
            'name' => $name ?? $attrs['name'],
            'slug' => $slug ?? $attrs['slug'],
            'is_system' => true,
            'scope' => 'tenant',
        ]);
    }
}
