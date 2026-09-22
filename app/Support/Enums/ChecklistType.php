<?php

namespace App\Support\Enums;

enum ChecklistType: string
{
    case Installation = 'installation';
    case Maintenance = 'maintenance';
    case Inspection = 'inspection';
    case Cleaning = 'cleaning';
    case Pmoc = 'pmoc';
    case General = 'general';
    case Safety = 'safety';
    case Other = 'other';
}
