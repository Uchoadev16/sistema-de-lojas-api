<?php

namespace App\Actions\V1\Users;

use App\Models\User;
use App\Services\Rbac\PermissionChecker;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Facades\DB;

class UpdateUserAction
{
    public function execute(User $targetUser, array $input): array
    {
        $tenantId = app(TenantContext::class)->id();

        return DB::transaction(function () use ($targetUser, $input, $tenantId): array {
            $targetUser->fill([
                'name' => $input['name'] ?? $targetUser->name,
                'email' => $input['email'] ?? $targetUser->email,
                'document' => array_key_exists('document', $input) ? $input['document'] : $targetUser->document,
                'phone' => array_key_exists('phone', $input) ? $input['phone'] : $targetUser->phone,
                'mobile' => array_key_exists('mobile', $input) ? $input['mobile'] : $targetUser->mobile,
                'birth_date' => array_key_exists('birth_date', $input) ? $input['birth_date'] : $targetUser->birth_date,
                'gender' => array_key_exists('gender', $input) ? $input['gender'] : $targetUser->gender,
                'avatar_path' => array_key_exists('avatar_path', $input) ? $input['avatar_path'] : $targetUser->avatar_path,
                'is_active' => array_key_exists('is_active', $input) ? (bool) $input['is_active'] : $targetUser->is_active,
                'receive_email_notifications' => array_key_exists('receive_email_notifications', $input) ? (bool) $input['receive_email_notifications'] : $targetUser->receive_email_notifications,
                'receive_sms_notifications' => array_key_exists('receive_sms_notifications', $input) ? (bool) $input['receive_sms_notifications'] : $targetUser->receive_sms_notifications,
                'receive_whatsapp_notifications' => array_key_exists('receive_whatsapp_notifications', $input) ? (bool) $input['receive_whatsapp_notifications'] : $targetUser->receive_whatsapp_notifications,
                'receive_push_notifications' => array_key_exists('receive_push_notifications', $input) ? (bool) $input['receive_push_notifications'] : $targetUser->receive_push_notifications,
                'locale' => array_key_exists('locale', $input) ? $input['locale'] : $targetUser->locale,
                'timezone' => array_key_exists('timezone', $input) ? $input['timezone'] : $targetUser->timezone,
                'preferences' => array_key_exists('preferences', $input) ? $input['preferences'] : $targetUser->preferences,
            ]);
            $targetUser->save();

            $userTenant = $targetUser->userTenants()
                ->where('tenant_id', $tenantId)
                ->first();

            if ($userTenant) {
                if (isset($input['role_id'])) {
                    $userTenant->role_id = $input['role_id'];
                }
                if (isset($input['can_approve_budget'])) {
                    $userTenant->can_approve_budget = (bool) $input['can_approve_budget'];
                }
                if (isset($input['max_budget_amount_cents'])) {
                    $userTenant->max_budget_amount_cents = $input['max_budget_amount_cents'];
                }
                if (isset($input['approval_limit_scope'])) {
                    $userTenant->approval_limit_scope = $input['approval_limit_scope'];
                }
                if (isset($input['external_id'])) {
                    $userTenant->external_id = $input['external_id'];
                }
                $userTenant->save();
                $userTenant->load('role');
            }

            app(PermissionChecker::class)->flushCacheForUser($targetUser);

            return [
                'user' => $targetUser->load('userTenants.role'),
                'userTenant' => $userTenant,
            ];
        });
    }
}
