<?php

namespace App\Support\Enums;

enum UserStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Inactive = 'inactive';
    case Blocked = 'blocked';
}
