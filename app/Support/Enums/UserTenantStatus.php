<?php

namespace App\Support\Enums;

enum UserTenantStatus: string
{
    case Active = 'active';
    case Invited = 'invited';
    case Suspended = 'suspended';
    case Removed = 'removed';
}
