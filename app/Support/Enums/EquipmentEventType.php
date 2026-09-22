<?php

namespace App\Support\Enums;

enum EquipmentEventType: string
{
    case Created = 'created';
    case Installed = 'installed';
    case Maintenance = 'maintenance';
    case Repair = 'repair';
    case PartReplaced = 'part_replaced';
    case Failure = 'failure';
    case Transferred = 'transferred';
    case Updated = 'updated';
    case StatusChanged = 'status_changed';
    case Retired = 'retired';
    case Discarded = 'discarded';
}
