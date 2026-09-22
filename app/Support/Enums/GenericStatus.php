<?php

namespace App\Support\Enums;

enum GenericStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Paused = 'paused';
    case Closed = 'closed';
}
