<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserLoggedIn;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogUserActivityListener implements ShouldQueue
{
    public function handle(UserLoggedIn $event): void {}
}
