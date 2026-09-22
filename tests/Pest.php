<?php

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserTenant;
use App\Services\Tenancy\TenantContext;
use App\Support\Enums\UserTenantStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

expect()->extend('toBeUuid', function () {
    return is_string($this->value) && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $this->value);
});

function ensureSystemRoles(): void
{
    $roles = [
        ['slug' => 'platform_admin', 'name' => 'Platform Admin', 'description' => 'Administrador da plataforma SaaS', 'scope' => 'global'],
        ['slug' => 'owner', 'name' => 'Owner', 'description' => 'Proprietário da conta', 'scope' => 'tenant'],
        ['slug' => 'admin', 'name' => 'Administrador', 'description' => 'Administrador da loja', 'scope' => 'tenant'],
        ['slug' => 'supervisor', 'name' => 'Supervisor', 'description' => 'Supervisor de equipes e operações', 'scope' => 'tenant'],
        ['slug' => 'technician', 'name' => 'Técnico', 'description' => 'Técnico de campo', 'scope' => 'tenant'],
        ['slug' => 'administrative', 'name' => 'Administrativo', 'description' => 'Equipe administrativa', 'scope' => 'tenant'],
        ['slug' => 'financial', 'name' => 'Financeiro', 'description' => 'Equipe financeira', 'scope' => 'tenant'],
        ['slug' => 'customer', 'name' => 'Cliente', 'description' => 'Usuário cliente', 'scope' => 'customer'],
    ];

    foreach ($roles as $r) {
        Role::firstOrCreate(
            ['tenant_id' => null, 'slug' => $r['slug']],
            [
                'name' => $r['name'],
                'description' => $r['description'],
                'scope' => $r['scope'],
                'is_system' => true,
                'is_active' => true,
            ]
        );
    }
}

function actingAsTenant(string|Role $role = 'owner', array $userAttributes = [], array $tenantAttributes = []): array
{
    ensureSystemRoles();

    $tenant = Tenant::factory()->create($tenantAttributes);

    $user = User::factory()->create($userAttributes);

    $roleModel = $role instanceof Role ? $role : Role::whereNull('tenant_id')->where('slug', $role)->firstOrFail();

    $userTenant = UserTenant::factory()->create([
        'user_id' => $user->id,
        'tenant_id' => $tenant->id,
        'role_id' => $roleModel->id,
        'status' => UserTenantStatus::Active->value,
        'is_default' => true,
    ]);

    Sanctum::actingAs($user, ['*']);
    app(TenantContext::class)->set($tenant);

    return [
        'user' => $user,
        'tenant' => $tenant,
        'userTenant' => $userTenant,
        'role' => $roleModel,
    ];
}

function seedTenantContext(Tenant $tenant): void
{
    app(TenantContext::class)->set($tenant);
}

function clearTenantContext(): void
{
    app(TenantContext::class)->clear();
}
