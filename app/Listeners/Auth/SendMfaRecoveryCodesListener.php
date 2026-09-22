<?php

namespace App\Listeners\Auth;

use App\Events\Auth\MfaEnabled;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMfaRecoveryCodesListener implements ShouldQueue
{
    public function handle(MfaEnabled $event): void {}
}
