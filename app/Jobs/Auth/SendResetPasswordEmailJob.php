<?php

namespace App\Jobs\Auth;

use App\Models\User;
use App\Notifications\Auth\ResetPasswordNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendResetPasswordEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $token,
        public ?string $returnUrl = null,
    ) {}

    public function handle(): void
    {
        $this->user->notify(new ResetPasswordNotification(
            $this->token,
            $this->user,
            $this->returnUrl,
        ));
    }
}
