<?php

namespace App\Support\Enums;

enum UnitStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Construction = 'construction';
    case Closed = 'closed';
}
