<?php

namespace App\Support\Enums;

enum SaasSubscriptionStatus: string
{
    case Trialing = 'trialing';
    case Active = 'active';
    case PastDue = 'past_due';
    case Cancelled = 'cancelled';
    case Inactive = 'inactive';
    case Paused = 'paused';
}
