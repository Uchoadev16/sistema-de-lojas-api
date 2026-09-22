<?php

namespace App\Events\Auth;

use App\Models\Tenant;
use App\Models\User;
use App\Models\UserTenant;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRegistered
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public User $user,
        public Tenant $tenant,
        public UserTenant $userTenant,
    ) {}
}
