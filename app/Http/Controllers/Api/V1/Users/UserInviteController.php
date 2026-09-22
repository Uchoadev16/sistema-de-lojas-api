<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Actions\V1\Users\InviteUserAction;
use App\Actions\V1\Users\ResendInviteAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Users\InviteUserRequest;
use App\Models\Invite;
use App\Models\User;
use App\Services\Tenancy\TenantContext;

class UserInviteController extends Controller
{
    public function store(InviteUserRequest $request, InviteUserAction $action)
    {
        $invite = $action->execute($request->validated(), $request->user());

        return $this->success([
            'invite' => [
                'id' => $invite->id,
                'email' => $invite->email,
                'name' => $invite->name,
                'role_id' => $invite->role_id,
                'status' => $invite->status,
                'expires_at' => $invite->expires_at?->toIso8601String(),
                'sent_at' => $invite->sent_at?->toIso8601String(),
            ],
        ], 201);
    }

    public function resend(User $user, ResendInviteAction $action)
    {
        $tenantId = app(TenantContext::class)->id();
        $userTenant = $user->userTenants()
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        if (! $userTenant->invite_token) {
            abort(400, 'This user has no pending invite.');
        }

        $invite = Invite::firstOrCreate(
            [
                'tenant_id' => $tenantId,
                'email' => $user->email,
                'status' => 'pending',
            ],
            [
                'invited_by_user_id' => request()->user()?->id,
                'role_id' => $userTenant->role_id,
                'name' => $user->name,
                'token' => $userTenant->invite_token,
                'expires_at' => now()->addDays(7),
            ]
        );

        $invite = $action->execute($invite);

        return $this->success([
            'invite' => [
                'id' => $invite->id,
                'email' => $invite->email,
                'name' => $invite->name,
                'status' => $invite->status,
                'expires_at' => $invite->expires_at?->toIso8601String(),
                'sent_at' => now()->toIso8601String(),
                'reminder_count' => $invite->reminder_count,
            ],
        ]);
    }
}
