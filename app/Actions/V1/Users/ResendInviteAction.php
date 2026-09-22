<?php

namespace App\Actions\V1\Users;

use App\Models\Invite;
use App\Notifications\Auth\UserInviteNotification;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Facades\Notification;

class ResendInviteAction
{
    public function execute(Invite $invite): Invite
    {
        if ($invite->status !== 'pending') {
            $invite->status = 'pending';
        }
        $invite->sent_at = now();
        $invite->expires_at = now()->addDays(7);
        $invite->reminder_count = (int) $invite->reminder_count + 1;
        $invite->last_reminder_at = now();
        $invite->save();

        $role = $invite->role;
        try {
            Notification::route('mail', $invite->email)
                ->notify(new UserInviteNotification(
                    null,
                    app(TenantContext::class)->tenant(),
                    $role,
                    $invite->token,
                    $invite->message,
                ));
        } catch (\Throwable) {
        }

        return $invite;
    }
}
