<?php

namespace App\Actions\V1\Users;

use App\Models\Invite;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\Auth\UserInviteNotification;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class InviteUserAction
{
    public function execute(array $input, ?User $inviter = null, ?Tenant $tenantContext = null): Invite
    {
        $tenantId = $tenantContext?->id ?? app(TenantContext::class)->id();
        $inviter ??= request()->user();

        return DB::transaction(function () use ($input, $tenantId, $inviter): Invite {
            $role = Role::findOrFail($input['role_id']);
            $token = hash_hmac('sha256', Str::random(40), $input['email'].$tenantId);

            $invite = Invite::create([
                'tenant_id' => $tenantId,
                'invited_by_user_id' => $inviter?->id,
                'role_id' => $role->id,
                'email' => $input['email'],
                'name' => $input['name'] ?? null,
                'document' => $input['document'] ?? null,
                'phone' => $input['phone'] ?? null,
                'message' => $input['message'] ?? null,
                'status' => 'pending',
                'token' => $token,
                'expires_at' => now()->addDays(7),
                'sent_at' => now(),
            ]);

            return [
                'invite' => $invite,
                'role' => $role,
                'token' => $token,
            ];
        });

        try {
            Notification::route('mail', $result['invite']->email)
                ->notify(new UserInviteNotification(
                    null,
                    app(TenantContext::class)->tenant(),
                    $result['role'],
                    $result['token'],
                    $result['invite']->message,
                ));
        } catch (\Throwable) {
        }

        return $result['invite'];
    }
}
