<?php

namespace App\Actions\V1\Auth;

use App\Events\Auth\UserRegistered;
use App\Events\Tenancy\TenantCreated;
use App\Models\Role;
use App\Models\SaasPlan;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserTenant;
use App\Support\Enums\SaasSubscriptionStatus;
use App\Support\Enums\TenantStatus;
use App\Support\Enums\UserStatus;
use App\Support\Enums\UserTenantStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterTenantAction
{
    public function execute(array $input, ?string $ip = null, ?string $userAgent = null): array
    {
        return DB::transaction(function () use ($input, $ip, $userAgent): array {
            $planId = $input['saas_plan_id'] ?? null;
            if (! $planId) {
                $plan = SaasPlan::firstOrCreate(
                    ['slug' => 'trial'],
                    [
                        'name' => 'Trial Gratuito',
                        'description' => 'Plano trial de 14 dias',
                        'status' => 'active',
                        'is_active' => true,
                        'billing_cycle' => 'monthly',
                        'price_monthly' => 0,
                        'price_annual' => 0,
                        'trial_days' => 14,
                        'support_qrcode' => true,
                        'support_mobile' => true,
                        'support_reports' => true,
                        'support_api' => true,
                    ]
                );
                $planId = $plan->id;
            }

            $tenantInput = $input['tenant'] ?? [];
            $slug = $tenantInput['slug'] ?? null;
            if (! $slug) {
                $base = Str::slug($tenantInput['trade_name'] ?? $tenantInput['legal_name'] ?? 'tenant');
                $slug = $base.'-'.Str::random(6);
                while (Tenant::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.Str::random(6);
                }
            }

            $trialDays = SaasPlan::find($planId)?->trial_days ?? 14;

            $tenant = Tenant::create([
                'legal_name' => $tenantInput['legal_name'] ?? $tenantInput['trade_name'],
                'trade_name' => $tenantInput['trade_name'] ?? $tenantInput['legal_name'],
                'slug' => $slug,
                'document' => $tenantInput['document'] ?? null,
                'phone' => $tenantInput['phone'] ?? null,
                'mobile' => $tenantInput['mobile'] ?? null,
                'email' => $tenantInput['email'] ?? $input['email'],
                'address_street' => $tenantInput['address_street'] ?? null,
                'address_number' => $tenantInput['address_number'] ?? null,
                'address_complement' => $tenantInput['address_complement'] ?? null,
                'address_neighborhood' => $tenantInput['address_neighborhood'] ?? null,
                'address_city' => $tenantInput['address_city'] ?? null,
                'address_state' => $tenantInput['address_state'] ?? null,
                'address_postal_code' => $tenantInput['address_postal_code'] ?? null,
                'status' => TenantStatus::Trial->value,
                'is_active' => true,
                'saas_plan_id' => $planId,
                'trial_ends_at' => now()->addDays($trialDays),
                'timezone' => 'America/Sao_Paulo',
                'locale' => $input['locale'] ?? 'pt_BR',
                'currency' => 'BRL',
                'date_format' => 'DD/MM/YYYY',
                'metadata' => [
                    'utm_source' => $input['utm_source'] ?? null,
                    'utm_campaign' => $input['utm_campaign'] ?? null,
                    'registered_via' => 'web_app',
                    'ip' => $ip ?? request()?->ip(),
                    'user_agent' => $userAgent ?? request()?->userAgent(),
                ],
            ]);

            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'document' => $input['document'] ?? null,
                'phone' => $input['phone'] ?? $tenantInput['phone'] ?? null,
                'mobile' => $input['mobile'] ?? $tenantInput['mobile'] ?? null,
                'status' => UserStatus::Pending->value,
                'is_active' => true,
                'locale' => $input['locale'] ?? 'pt_BR',
                'timezone' => $input['timezone'] ?? 'America/Sao_Paulo',
            ]);

            $ownerRole = Role::firstOrCreate(
                ['tenant_id' => null, 'slug' => 'owner'],
                [
                    'name' => 'Owner',
                    'description' => 'Proprietário da conta',
                    'scope' => 'tenant',
                    'is_system' => true,
                    'is_active' => true,
                ]
            );

            $userTenant = UserTenant::create([
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'role_id' => $ownerRole->id,
                'status' => UserTenantStatus::Active->value,
                'is_active' => true,
                'is_default' => true,
                'is_owner' => true,
                'accepted_at' => now(),
            ]);

            $tenant->update(['created_by_user_id' => $user->id]);

            $tenant->subscriptions()->create([
                'saas_plan_id' => $planId,
                'status' => SaasSubscriptionStatus::Trialing->value,
                'is_active' => true,
                'billing_cycle' => 'monthly',
                'amount' => 0,
                'trial_ends_at' => $tenant->trial_ends_at,
                'current_period_start' => now(),
                'current_period_end' => $tenant->trial_ends_at,
            ]);

            try {
                $user->sendEmailVerificationNotification();
            } catch (\Throwable) {
            }

            event(new UserRegistered($user, $tenant, $userTenant));
            event(new TenantCreated($tenant, $user));

            return [
                'user' => $user,
                'tenant' => $tenant,
                'userTenant' => $userTenant,
                'role' => $ownerRole,
                'plan_id' => $planId,
            ];
        });
    }
}
