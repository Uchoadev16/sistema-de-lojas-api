<?php

namespace App\Support\Enums;

enum DeviceStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Lost = 'lost';
    case Stolen = 'stolen';
    case Wiped = 'wiped';
}
