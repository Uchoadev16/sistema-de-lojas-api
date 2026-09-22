<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        $modules = ['users', 'roles', 'customers', 'equipment', 'work_orders', 'maintenance_plans', 'budgets', 'contracts', 'inventory', 'reports', 'finance', 'notifications'];
        $actions = ['view', 'create', 'update', 'delete', 'export', 'approve'];
        $module = $this->faker->randomElement($modules);
        $action = $this->faker->randomElement($actions);
        $slug = $module.'.'.$action;
        $name = ucfirst($action).' '.ucfirst(Str::replace('_', ' ', $module));

        return [
            'tenant_id' => null,
            'name' => $name,
            'slug' => $slug,
            'group' => $module,
            'module' => $module,
            'description' => $this->faker->sentence(),
            'scope' => 'tenant',
            'is_system' => true,
            'is_active' => true,
        ];
    }
}
