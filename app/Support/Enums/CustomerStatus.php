<?php

namespace App\Support\Enums;

enum CustomerStatus: string
{
    case Lead = 'lead';
    case Active = 'active';
    case Inactive = 'inactive';
    case Blocked = 'blocked';
    case Churned = 'churned';
}
