<?php

namespace App\Support\Enums;

enum ScheduleOrigin: string
{
    case Manual = 'manual';
    case WorkOrder = 'work_order';
    case MaintenancePlan = 'maintenance_plan';
    case Contract = 'contract';
    case Recurring = 'recurring';
}
