<?php

namespace App\Events\Auth;

use App\Models\User;
use App\Support\Enums\MfaType;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MfaEnabled
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public User $user,
        public MfaType $type,
    ) {}
}
