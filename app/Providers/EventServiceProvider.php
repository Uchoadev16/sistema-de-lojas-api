<?php

namespace App\Providers;

use App\Events\Auth\MfaDisabled;
use App\Events\Auth\MfaEnabled;
use App\Events\Auth\UserActivated;
use App\Events\Auth\UserDeactivated;
use App\Events\Auth\UserLoggedIn;
use App\Events\Auth\UserPasswordChanged;
use App\Events\Auth\UserRegistered;
use App\Events\Auth\UserSessionsRevoked;
use App\Events\Tenancy\TenantCreated;
use App\Events\Tenancy\TenantUpdated;
use App\Listeners\Auth\LogUserActivityListener;
use App\Listeners\Auth\SendMfaRecoveryCodesListener;
use App\Listeners\Auth\SendWelcomeEmailListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegistered::class => [
            SendWelcomeEmailListener::class,
        ],
        UserLoggedIn::class => [
            LogUserActivityListener::class,
        ],
        MfaEnabled::class => [
            SendMfaRecoveryCodesListener::class,
        ],
        MfaDisabled::class => [],
        UserPasswordChanged::class => [],
        UserSessionsRevoked::class => [],
        UserActivated::class => [],
        UserDeactivated::class => [],
        TenantCreated::class => [],
        TenantUpdated::class => [],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
