<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmailListener implements ShouldQueue
{
    public function handle(UserRegistered $event): void {}
}
