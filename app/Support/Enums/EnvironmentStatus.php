<?php

namespace App\Support\Enums;

enum EnvironmentStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Maintenance = 'maintenance';
    case Closed = 'closed';
}
