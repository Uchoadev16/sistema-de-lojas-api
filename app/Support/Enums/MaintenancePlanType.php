<?php

namespace App\Support\Enums;

enum MaintenancePlanType: string
{
    case Preventive = 'preventive';
    case Corrective = 'corrective';
    case Pmoc = 'pmoc';
    case Predictive = 'predictive';
    case Other = 'other';
}
