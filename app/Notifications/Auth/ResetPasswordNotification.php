<?php

namespace App\Notifications\Auth;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class ResetPasswordNotification extends Notification
{
    public function __construct(
        public string $token,
        public User $user,
        public ?string $returnUrl = null,
        public ?string $locale = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $locale = $this->locale ?: $this->user->locale ?: app()->getLocale();

        $allowedHosts = config('climaops.allowed_reset_return_hosts', []);
        $useReturnUrl = false;
        if ($this->returnUrl !== null && $this->returnUrl !== '') {
            $host = parse_url($this->returnUrl, PHP_URL_HOST);
            if ($host !== null && in_array($host, $allowedHosts, true)) {
                $useReturnUrl = true;
            }
        }

        $resetUrl = $useReturnUrl
            ? rtrim($this->returnUrl, '/').'?token='.urlencode($this->token).'&email='.urlencode($notifiable->email)
            : URL::route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->email,
            ]);

        $expiresIn = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        return (new MailMessage)
            ->subject(__('notifications.reset_password.subject', [], $locale))
            ->greeting(__('notifications.reset_password.greeting', ['name' => $notifiable->name], $locale))
            ->line(__('notifications.reset_password.line_1', [], $locale))
            ->action(__('notifications.reset_password.action', [], $locale), $resetUrl)
            ->line(__('notifications.reset_password.line_2', ['minutes' => $expiresIn], $locale))
            ->line(__('notifications.reset_password.line_3', [], $locale))
            ->salutation(__('notifications.reset_password.salutation', [], $locale));
    }
}
