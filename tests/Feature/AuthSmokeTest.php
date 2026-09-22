<?php

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Rbac\PermissionChecker;
use App\Services\Tenancy\TenantContext;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;

it('realiza registro de tenant e usuário com sucesso', function (): void {
    $response = postJson('/api/v1/auth/register', [
        'name' => 'João Silva',
        'email' => 'joao@example.com',
        'password' => 'Senha123!',
        'password_confirmation' => 'Senha123!',
        'accept_terms' => true,
        'tenant' => [
            'legal_name' => 'Empresa Teste LTDA',
            'trade_name' => 'Empresa Teste',
            'document' => '12345678000190',
            'email' => 'contato@example.com',
            'phone' => '1133334444',
        ],
    ]);

    $response->assertCreated();
    $response->assertJsonStructure([
        'success',
        'data' => [
            'access_token',
            'token_type',
            'token_id',
            'user' => ['id', 'name', 'email'],
            'tenant' => ['id', 'legal_name', 'trade_name', 'slug'],
            'role' => ['id', 'name', 'slug'],
            'available_tenants',
        ],
    ]);

    $data = $response->json('data');
    expect($data['token_type'])->toBe('Bearer');
    expect($data['user']['email'])->toBe('joao@example.com');
    expect($data['tenant']['trade_name'])->toBe('Empresa Teste');
    expect($data['role']['slug'])->toBe('owner');
    expect($data['access_token'])->not->toBeEmpty();
});

it('realiza login e acessa endpoint me', function (): void {
    $register = postJson('/api/v1/auth/register', [
        'name' => 'Maria Souza',
        'email' => 'maria@example.com',
        'password' => 'Senha123!',
        'password_confirmation' => 'Senha123!',
        'accept_terms' => true,
        'tenant' => [
            'legal_name' => 'Outra Empresa LTDA',
            'trade_name' => 'Outra Empresa',
            'email' => 'empresa@example.com',
        ],
    ])->assertCreated();

    $token = $register->json('data.access_token');
    $tenantId = $register->json('data.tenant.id');

    $login = postJson('/api/v1/auth/login', [
        'email' => 'maria@example.com',
        'password' => 'Senha123!',
    ]);

    $login->assertOk();
    expect($login->json('data.access_token'))->not->toBeEmpty();

    $me = getJson('/api/v1/auth/me', [
        'Authorization' => 'Bearer ' . $token,
        'X-Tenant-ID' => $tenantId,
    ]);

    $me->assertOk();
    $me->assertJsonStructure([
        'success',
        'data' => [
            'user' => ['id', 'name', 'email'],
            'tenants',
            'tenant',
            'role',
            'permissions',
            'mfa_methods',
        ],
    ]);
    expect($me->json('data.user.email'))->toBe('maria@example.com');
});

it('lista permissões como platform admin', function (): void {
    ensureSystemRoles();

    $platformAdmin = User::factory()->create([
        'is_platform_admin' => true,
        'status' => \App\Support\Enums\UserStatus::Active->value,
        'is_active' => true,
    ]);

    Sanctum::actingAs($platformAdmin, ['*']);

    $fakeTenant = Tenant::factory()->create();
    app(TenantContext::class)->set($fakeTenant);

    $response = getJson('/api/v1/permissions', [
        'X-Tenant-ID' => $fakeTenant->id,
    ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data',
    ]);
});

it('endpoint me retorna estrutura correta', function (): void {
    $ctx = actingAsTenant('owner');
    $user = $ctx['user'];
    $tenant = $ctx['tenant'];

    $me = getJson('/api/v1/auth/me', [
        'X-Tenant-ID' => $tenant->id,
    ]);

    $me->assertOk();
    $me->assertJsonStructure([
        'success',
        'data' => [
            'user' => ['id', 'name', 'email'],
            'tenants',
            'tenant',
            'role',
            'permissions',
            'mfa_methods',
        ],
    ]);
    expect($me->json('data.user.email'))->toBe($user->email);
});
