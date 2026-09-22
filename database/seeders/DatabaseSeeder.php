<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\SaasPlan;
use App\Models\User;
use App\Support\Enums\BillingCycle;
use App\Support\Enums\SaasPlanStatus;
use App\Support\Enums\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    private array $modules = [
        'users' => 'Usuários',
        'roles' => 'Papéis',
        'permissions' => 'Permissões',
        'tenants' => 'Lojas',
        'customers' => 'Clientes',
        'contacts' => 'Contatos',
        'tags' => 'Tags',
        'units' => 'Unidades',
        'environments' => 'Ambientes',
        'equipment_brands' => 'Marcas de Equipamentos',
        'equipment_models' => 'Modelos de Equipamentos',
        'equipment' => 'Equipamentos',
        'qr_codes' => 'QR Codes',
        'technicians' => 'Técnicos',
        'service_teams' => 'Equipes de Serviço',
        'maintenance_plans' => 'Planos de Manutenção',
        'pmocs' => 'PMOCs',
        'checklists' => 'Checklists',
        'checklist_templates' => 'Templates de Checklist',
        'work_orders' => 'Ordens de Serviço',
        'schedules' => 'Agendamentos',
        'contracts' => 'Contratos',
        'budgets' => 'Orçamentos',
        'products' => 'Produtos',
        'warehouses' => 'Armazéns',
        'inventory' => 'Estoque',
        'stock_movements' => 'Movimentações de Estoque',
        'documents' => 'Documentos',
        'notifications' => 'Notificações',
        'finance_transactions' => 'Transações Financeiras',
        'reports' => 'Relatórios',
        'audit_logs' => 'Logs de Auditoria',
        'settings' => 'Configurações',
        'saas_plans' => 'Planos SaaS',
        'saas_subscriptions' => 'Assinaturas SaaS',
        'sync' => 'Sincronização',
        'signatures' => 'Assinaturas',
        'evidences' => 'Evidências',
    ];

    private array $actions = [
        'view' => 'Visualizar',
        'view_any' => 'Visualizar Todos',
        'create' => 'Criar',
        'update' => 'Atualizar',
        'delete' => 'Excluir',
        'delete_any' => 'Excluir Qualquer',
        'export' => 'Exportar',
        'import' => 'Importar',
        'approve' => 'Aprovar',
        'reject' => 'Rejeitar',
        'cancel' => 'Cancelar',
        'complete' => 'Concluir',
        'assign' => 'Atribuir',
        'reassign' => 'Reatribuir',
        'audit' => 'Auditar',
        'invite' => 'Convidar',
        'reset_password' => 'Resetar Senha',
        'reset_status' => 'Resetar Status',
        'switch' => 'Trocar',
        'manage' => 'Gerenciar',
        'print' => 'Imprimir',
        'download' => 'Baixar',
        'share' => 'Compartilhar',
        'sign' => 'Assinar',
        'execute' => 'Executar',
        'billing' => 'Faturamento',
    ];

    private array $rolePermissionMap = [
        'platform_admin' => ['*.*'],
        'owner' => ['*.*'],
        'admin' => [
            'users.*', 'roles.*', 'permissions.view', 'permissions.view_any',
            'tenants.view', 'tenants.update',
            'customers.*', 'contacts.*', 'tags.*',
            'units.*', 'environments.*',
            'equipment_brands.*', 'equipment_models.*', 'equipment.*', 'qr_codes.*',
            'technicians.*', 'service_teams.*',
            'maintenance_plans.*', 'pmocs.*', 'checklists.*', 'checklist_templates.*',
            'work_orders.*', 'schedules.*',
            'contracts.*', 'budgets.*',
            'products.*', 'warehouses.*', 'inventory.*', 'stock_movements.*',
            'documents.*', 'notifications.view', 'notifications.view_any', 'notifications.create', 'notifications.update',
            'finance_transactions.*',
            'reports.*', 'audit_logs.view', 'audit_logs.view_any', 'audit_logs.export',
            'settings.view', 'settings.update',
            'saas_plans.view', 'saas_plans.view_any',
            'saas_subscriptions.view', 'saas_subscriptions.view_any',
            'sync.*', 'signatures.*', 'evidences.*',
        ],
        'supervisor' => [
            'users.view', 'users.view_any', 'users.create', 'users.update',
            'users.invite', 'users.reset_password',
            'roles.view', 'roles.view_any',
            'permissions.view', 'permissions.view_any',
            'tenants.view',
            'customers.*', 'contacts.*', 'tags.*',
            'units.*', 'environments.*',
            'equipment_brands.view', 'equipment_brands.view_any',
            'equipment_models.view', 'equipment_models.view_any',
            'equipment.*', 'qr_codes.*',
            'technicians.view', 'technicians.view_any', 'technicians.create', 'technicians.update',
            'service_teams.*',
            'maintenance_plans.*', 'pmocs.*', 'checklists.*', 'checklist_templates.*',
            'work_orders.*', 'schedules.*',
            'contracts.view', 'contracts.view_any', 'contracts.create', 'contracts.update',
            'budgets.*',
            'products.view', 'products.view_any',
            'warehouses.view', 'warehouses.view_any',
            'inventory.view', 'inventory.view_any',
            'stock_movements.view', 'stock_movements.view_any', 'stock_movements.create',
            'documents.*',
            'notifications.view', 'notifications.view_any',
            'reports.*',
            'audit_logs.view', 'audit_logs.view_any',
            'settings.view',
            'sync.view', 'sync.view_any', 'sync.execute',
            'signatures.*', 'evidences.*',
        ],
        'technician' => [
            'users.view',
            'tenants.view',
            'customers.view', 'customers.view_any',
            'contacts.view', 'contacts.view_any',
            'tags.view', 'tags.view_any',
            'units.view', 'units.view_any',
            'environments.view', 'environments.view_any',
            'equipment_brands.view', 'equipment_brands.view_any',
            'equipment_models.view', 'equipment_models.view_any',
            'equipment.view', 'equipment.view_any',
            'qr_codes.view', 'qr_codes.view_any',
            'technicians.view',
            'service_teams.view', 'service_teams.view_any',
            'maintenance_plans.view', 'maintenance_plans.view_any',
            'pmocs.view', 'pmocs.view_any', 'pmocs.execute',
            'checklists.view', 'checklists.view_any', 'checklists.execute',
            'checklist_templates.view', 'checklist_templates.view_any',
            'work_orders.view', 'work_orders.view_any', 'work_orders.update',
            'work_orders.complete', 'work_orders.execute',
            'schedules.view', 'schedules.view_any',
            'contracts.view', 'contracts.view_any',
            'budgets.view', 'budgets.view_any',
            'products.view', 'products.view_any',
            'inventory.view', 'inventory.view_any',
            'stock_movements.view', 'stock_movements.view_any',
            'documents.view', 'documents.view_any', 'documents.download',
            'notifications.view', 'notifications.view_any',
            'reports.view', 'reports.view_any', 'reports.export',
            'sync.view', 'sync.view_any', 'sync.execute',
            'signatures.*', 'evidences.*',
        ],
        'administrative' => [
            'users.view', 'users.view_any',
            'roles.view', 'roles.view_any',
            'permissions.view', 'permissions.view_any',
            'tenants.view',
            'customers.*', 'contacts.*', 'tags.*',
            'units.view', 'units.view_any',
            'environments.view', 'environments.view_any',
            'equipment_brands.view', 'equipment_brands.view_any',
            'equipment_models.view', 'equipment_models.view_any',
            'equipment.view', 'equipment.view_any',
            'qr_codes.view', 'qr_codes.view_any',
            'technicians.view', 'technicians.view_any',
            'service_teams.view', 'service_teams.view_any',
            'maintenance_plans.view', 'maintenance_plans.view_any',
            'pmocs.view', 'pmocs.view_any',
            'checklists.view', 'checklists.view_any',
            'checklist_templates.view', 'checklist_templates.view_any',
            'work_orders.view', 'work_orders.view_any', 'work_orders.create', 'work_orders.update',
            'schedules.*',
            'contracts.*', 'budgets.*',
            'products.*', 'warehouses.*', 'inventory.*', 'stock_movements.*',
            'documents.*',
            'notifications.view', 'notifications.view_any',
            'finance_transactions.view', 'finance_transactions.view_any',
            'finance_transactions.create', 'finance_transactions.update',
            'reports.*',
            'settings.view',
            'sync.view', 'sync.view_any',
        ],
        'financial' => [
            'users.view', 'users.view_any',
            'tenants.view',
            'customers.view', 'customers.view_any',
            'contracts.*', 'budgets.*',
            'products.view', 'products.view_any',
            'inventory.view', 'inventory.view_any',
            'stock_movements.view', 'stock_movements.view_any', 'stock_movements.export',
            'documents.view', 'documents.view_any', 'documents.download',
            'finance_transactions.*',
            'reports.*',
            'saas_subscriptions.view', 'saas_subscriptions.view_any',
            'saas_plans.view', 'saas_plans.view_any',
            'settings.view',
        ],
        'customer' => [
            'tenants.view',
            'customers.view',
            'units.view', 'units.view_any',
            'environments.view', 'environments.view_any',
            'equipment.view', 'equipment.view_any',
            'work_orders.view', 'work_orders.view_any',
            'schedules.view', 'schedules.view_any',
            'contracts.view', 'contracts.view_any', 'contracts.download',
            'budgets.view', 'budgets.view_any',
            'documents.view', 'documents.view_any', 'documents.download',
            'pmocs.view', 'pmocs.view_any', 'pmocs.download',
            'checklists.view', 'checklists.view_any',
            'notifications.view', 'notifications.view_any',
            'reports.view', 'reports.export',
            'signatures.view', 'signatures.sign',
            'evidences.view', 'evidences.view_any',
        ],
    ];

    public function run(): void
    {
        DB::transaction(function (): void {
            $this->command->info('🌱 Iniciando seed do banco de dados ClimaOps...');

            $this->seedSaasPlans();
            $this->seedRoles();
            $permissions = $this->seedPermissions();
            $this->syncRolePermissions($permissions);
            $this->seedPlatformAdmin();

            $this->command->info('✅ Seed concluído com sucesso!');
            $this->command->info('   • Planos SaaS: 2');
            $this->command->info('   • Roles: 8');
            $this->command->info('   • Permissões: '.count($permissions));
        });
    }

    private function seedSaasPlans(): void
    {
        $this->command->line('  → Criando planos SaaS...');

        SaasPlan::firstOrCreate(
            ['slug' => 'trial'],
            [
                'name' => 'Trial Gratuito',
                'description' => 'Plano trial de 14 dias com todas as funcionalidades',
                'status' => SaasPlanStatus::Active->value,
                'is_active' => true,
                'billing_cycle' => BillingCycle::Monthly->value,
                'price_monthly' => 0,
                'price_annual' => 0,
                'trial_days' => 14,
                'max_users' => 5,
                'max_tenants_per_user' => 1,
                'max_customers' => 100,
                'max_equipment' => 100,
                'max_technicians' => 5,
                'storage_mb' => 500,
                'support_pmoc' => true,
                'support_qrcode' => true,
                'support_mobile' => true,
                'support_billing' => true,
                'support_inventory' => true,
                'support_reports' => true,
                'support_portal_cliente' => true,
                'support_api' => true,
                'features' => [
                    'multi_tenant' => false,
                    'custom_branding' => false,
                    'api_access' => true,
                    'advanced_reports' => true,
                    'pmoc' => true,
                    'inventory' => true,
                    'finance' => true,
                    'mobile_app' => true,
                ],
                'limits' => [
                    'work_orders_per_month' => 500,
                    'schedules_per_month' => 500,
                    'notifications_per_month' => 5000,
                ],
                'sort_order' => 0,
            ]
        );

        SaasPlan::firstOrCreate(
            ['slug' => 'professional'],
            [
                'name' => 'Profissional',
                'description' => 'Plano profissional para empresas de climatização',
                'status' => SaasPlanStatus::Active->value,
                'is_active' => true,
                'billing_cycle' => BillingCycle::Monthly->value,
                'price_monthly' => 299.00,
                'price_annual' => 2990.00,
                'trial_days' => 14,
                'max_users' => 20,
                'max_tenants_per_user' => 1,
                'max_customers' => 5000,
                'max_equipment' => 2000,
                'max_technicians' => 50,
                'storage_mb' => 10000,
                'support_pmoc' => true,
                'support_qrcode' => true,
                'support_mobile' => true,
                'support_billing' => true,
                'support_inventory' => true,
                'support_reports' => true,
                'support_portal_cliente' => true,
                'support_api' => true,
                'features' => [
                    'multi_tenant' => false,
                    'custom_branding' => true,
                    'api_access' => true,
                    'advanced_reports' => true,
                    'pmoc' => true,
                    'inventory' => true,
                    'finance' => true,
                    'mobile_app' => true,
                ],
                'limits' => [
                    'work_orders_per_month' => null,
                    'schedules_per_month' => null,
                    'notifications_per_month' => null,
                ],
                'sort_order' => 1,
            ]
        );
    }

    private function seedRoles(): void
    {
        $this->command->line('  → Criando roles padrão...');

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

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['tenant_id' => null, 'slug' => $role['slug']],
                [
                    'name' => $role['name'],
                    'description' => $role['description'],
                    'scope' => $role['scope'],
                    'is_system' => true,
                    'is_active' => true,
                ]
            );
        }
    }

    private function seedPermissions(): array
    {
        $this->command->line('  → Criando permissões modulares...');

        $permissions = [];
        $permissionData = [];
        $now = now();

        $skipActions = [
            'saas_plans' => ['view', 'view_any', 'export'],
            'saas_subscriptions' => ['view', 'view_any', 'export', 'billing'],
            'audit_logs' => ['view', 'view_any', 'export', 'download'],
            'sync' => ['view', 'view_any', 'execute'],
            'signatures' => ['view', 'view_any', 'create', 'sign', 'download'],
            'evidences' => ['view', 'view_any', 'create', 'update', 'delete', 'download'],
        ];

        $basicModules = [
            'users' => ['view', 'view_any', 'create', 'update', 'delete', 'delete_any', 'export', 'import', 'invite', 'reset_password', 'reset_status', 'activate', 'deactivate'],
            'roles' => ['view', 'view_any', 'create', 'update', 'delete', 'assign', 'manage'],
            'permissions' => ['view', 'view_any'],
            'tenants' => ['view', 'update', 'switch', 'manage'],
        ];

        foreach ($this->modules as $moduleSlug => $moduleName) {
            if (isset($skipActions[$moduleSlug])) {
                $actionList = $skipActions[$moduleSlug];
            } elseif (isset($basicModules[$moduleSlug])) {
                $actionList = $basicModules[$moduleSlug];
            } else {
                $actionList = [
                    'view', 'view_any', 'create', 'update', 'delete', 'delete_any',
                    'export', 'import', 'approve', 'reject', 'cancel', 'complete',
                    'assign', 'reassign', 'audit', 'print', 'download', 'share', 'execute',
                ];
            }

            foreach ($actionList as $action) {
                $slug = $moduleSlug.'.'.$action;
                $actionName = $this->actions[$action] ?? ucfirst($action);
                $permissionData[] = [
                    'id' => Str::uuid()->toString(),
                    'tenant_id' => null,
                    'slug' => $slug,
                    'module' => $moduleSlug,
                    'group' => $this->getPermissionGroup($moduleSlug),
                    'name' => $actionName.' '.$moduleName,
                    'description' => $actionName.' - '.$moduleName,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $permissions[] = $slug;
            }
        }

        foreach (array_chunk($permissionData, 200) as $chunk) {
            Permission::query()->insertOrIgnore($chunk);
        }

        return $permissions;
    }

    private function syncRolePermissions(array $allPermissions): void
    {
        $this->command->line('  → Sincronizando permissões das roles...');

        $permissionMap = Permission::whereNull('tenant_id')
            ->pluck('id', 'slug')
            ->all();

        $roleMap = Role::whereNull('tenant_id')
            ->where('is_system', true)
            ->pluck('id', 'slug')
            ->all();

        $rolePermissionData = [];
        $now = now();

        foreach ($this->rolePermissionMap as $roleSlug => $patterns) {
            $roleId = $roleMap[$roleSlug] ?? null;
            if (! $roleId) {
                continue;
            }

            $grantedPermissions = [];
            foreach ($patterns as $pattern) {
                if ($pattern === '*.*') {
                    foreach ($allPermissions as $permSlug) {
                        $grantedPermissions[$permSlug] = true;
                    }
                    break;
                }
                [$mod, $act] = explode('.', $pattern);
                if ($act === '*') {
                    foreach ($allPermissions as $permSlug) {
                        if (str_starts_with($permSlug, $mod.'.')) {
                            $grantedPermissions[$permSlug] = true;
                        }
                    }
                } else {
                    $grantedPermissions[$pattern] = true;
                }
            }

            foreach (array_keys($grantedPermissions) as $permSlug) {
                $permId = $permissionMap[$permSlug] ?? null;
                if ($permId) {
                    $rolePermissionData[] = [
                        'role_id' => $roleId,
                        'permission_id' => $permId,
                        'created_at' => $now,
                    ];
                }
            }
        }

        foreach (array_chunk($rolePermissionData, 300) as $chunk) {
            DB::table('role_permissions')->insertOrIgnore($chunk);
        }
    }

    private function seedPlatformAdmin(): void
    {
        $this->command->line('  → Criando usuário Platform Admin...');

        $email = config('seeder.admin_email');
        $password = config('seeder.admin_password');

        if (! config('seeder.force_in_production') && app()->environment('production')) {
            $this->command->warn('    ⚠️  Pulando criação de admin padrão — ambiente produção. Habilite SEEDER_FORCE_IN_PRODUCTION=true se necessário.');

            return;
        }

        if (User::where('email', $email)->exists()) {
            $this->command->line("    ℹ️  Usuário admin já existe ({$email})");

            return;
        }

        $user = User::create([
            'name' => 'Platform Admin',
            'email' => $email,
            'password' => Hash::make($password),
            'document' => null,
            'status' => UserStatus::Active->value,
            'is_active' => true,
            'is_platform_admin' => true,
            'locale' => 'pt_BR',
            'timezone' => 'America/Sao_Paulo',
            'email_verified_at' => now(),
        ]);

        $this->command->info("    ✅ Usuário admin criado: {$email}");
        $this->command->comment('       (Senha definida por config/seeder.admin_password)');
    }

    private function getPermissionGroup(string $module): string
    {
        $groups = [
            'identity' => ['users', 'roles', 'permissions', 'tenants'],
            'customers' => ['customers', 'contacts', 'tags'],
            'assets' => ['units', 'environments', 'equipment_brands', 'equipment_models', 'equipment', 'qr_codes'],
            'staff' => ['technicians', 'service_teams'],
            'operations' => ['maintenance_plans', 'pmocs', 'checklists', 'checklist_templates', 'work_orders', 'schedules'],
            'commercial' => ['contracts', 'budgets'],
            'inventory' => ['products', 'warehouses', 'inventory', 'stock_movements'],
            'support' => ['documents', 'notifications', 'signatures', 'evidences'],
            'finance' => ['finance_transactions'],
            'intelligence' => ['reports', 'audit_logs'],
            'platform' => ['settings', 'saas_plans', 'saas_subscriptions', 'sync'],
        ];

        foreach ($groups as $group => $modules) {
            if (in_array($module, $modules, true)) {
                return $group;
            }
        }

        return 'other';
    }
}
