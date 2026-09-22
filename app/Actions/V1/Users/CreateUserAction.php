<?php

namespace App\Actions\V1\Users;

use App\Models\Role;
use App\Models\User;
use App\Models\UserTenant;
use App\Notifications\Auth\UserInviteNotification;
use App\Services\Tenancy\TenantContext;
use App\Support\Enums\UserStatus;
use App\Support\Enums\UserTenantStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateUserAction
{
    public function execute(array $input, ?User $currentUser = null): array
    {
        $tenantId = app(TenantContext::class)->id();
        $currentUser ??= request()->user();

        return DB::transaction(function () use ($input, $tenantId, $currentUser): array {
            $existing = User::where('email', $input['email'])->first();
            $user = $existing;

            if (! $existing) {
                $password = $input['password'] ?? (string) Str::password(12, true, true, true, false);
                $user = User::create([
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'password' => Hash::make($password),
                    'document' => $input['document'] ?? null,
                    'phone' => $input['phone'] ?? null,
                    'mobile' => $input['mobile'] ?? null,
                    'birth_date' => $input['birth_date'] ?? null,
                    'gender' => $input['gender'] ?? null,
                    'avatar_path' => $input['avatar_path'] ?? null,
                    'status' => UserStatus::Active->value,
                    'is_active' => true,
                    'locale' => $input['locale'] ?? app()->getLocale(),
                    'timezone' => $input['timezone'] ?? config('app.timezone'),
                ]);
            }

            $existingLink = $user->userTenants()->where('tenant_id', $tenantId)->first();
            if ($existingLink) {
                throw ValidationException::withMessages([
                    'email' => __('users.already_in_tenant'),
                ])->status(409);
            }

            $role = Role::findOrFail($input['role_id']);

            $inviteToken = null;
            $status = UserTenantStatus::Active->value;
            $acceptedAt = now();

            if (! empty($input['send_invite']) || $existing) {
                $status = UserTenantStatus::Invited->value;
                $acceptedAt = null;
                $inviteToken = hash_hmac('sha256', Str::random(40), $user->email.$tenantId);
            }

            $userTenant = UserTenant::create([
                'user_id' => $user->id,
                'tenant_id' => $tenantId,
                'role_id' => $role->id,
                'status' => $status,
                'is_active' => true,
                'is_default' => false,
                'is_owner' => (bool) ($input['is_owner'] ?? false),
                'accepted_at' => $acceptedAt,
                'invited_at' => now(),
                'invite_token' => $inviteToken,
                'invite_expires_at' => $inviteToken ? now()->addDays(7) : null,
                'can_approve_budget' => (bool) ($input['can_approve_budget'] ?? false),
                'max_budget_amount_cents' => $input['max_budget_amount_cents'] ?? null,
                'approval_limit_scope' => $input['approval_limit_scope'] ?? null,
                'external_id' => $input['external_id'] ?? null,
                'created_by_user_id' => $currentUser?->id,
            ]);

            return [
                'user' => $user,
                'userTenant' => $userTenant,
                'role' => $role,
                'inviteToken' => $inviteToken,
                'message' => $input['message'] ?? null,
                'sendInvite' => (bool) $inviteToken,
            ];
        });

        if ($result['sendInvite'] === true) {
            try {
                $result['user']->notify(new UserInviteNotification(
                    $result['user'],
                    app(TenantContext::class)->tenant(),
                    $result['role'],
                    $result['inviteToken'],
                    $result['message'],
                ));
            } catch (\Throwable) {
            }
        }

        return [
            'user' => $result['user'],
            'userTenant' => $result['userTenant'],
            'role' => $result['role'],
            'invite_sent' => (bool) $result['inviteToken'],
        ];
    }
}
