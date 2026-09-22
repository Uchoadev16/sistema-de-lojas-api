<?php

namespace App\Notifications\Auth;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInviteNotification extends Notification
{
    public function __construct(
        public ?User $user,
        public ?Tenant $tenant,
        public ?Role $role,
        public string $token,
        public ?string $message = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $tenantName = $this->tenant?->trade_name ?? $this->tenant?->legal_name ?? config('app.name');
        $roleName = $this->role?->name ?? 'Colaborador';
        $locale = $this->tenant?->locale ?? app()->getLocale();

        $inviteUrl = config('app.frontend_url')
            ? rtrim(config('app.frontend_url'), '/').'/invite/'.$this->token
            : url('/invite/'.$this->token);

        $mail = (new MailMessage)
            ->subject(__('notifications.user_invite.subject', ['tenant' => $tenantName], $locale))
            ->greeting(__('notifications.user_invite.greeting', ['name' => $this->user?->name ?? ''], $locale))
            ->line(__('notifications.user_invite.line_1', ['tenant' => $tenantName], $locale))
            ->line(__('notifications.user_invite.line_2', ['role' => $roleName], $locale));

        if ($this->message) {
            $mail->line('"'.e($this->message).'"');
        }

        $mail->action(__('notifications.user_invite.action', [], $locale), $inviteUrl)
            ->line(__('notifications.user_invite.line_3', [], $locale))
            ->salutation(__('notifications.user_invite.salutation', [], $locale));

        return $mail;
    }
}
