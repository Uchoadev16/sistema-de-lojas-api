<?php

namespace App\Support\Enums;

enum EquipmentStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Maintenance = 'maintenance';
    case Retired = 'retired';
    case Discarded = 'discarded';
}
