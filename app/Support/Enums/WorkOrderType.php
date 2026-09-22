<?php

namespace App\Support\Enums;

enum WorkOrderType: string
{
    case Preventive = 'preventive';
    case Corrective = 'corrective';
    case Installation = 'installation';
    case Inspection = 'inspection';
    case Cleaning = 'cleaning';
    case Emergency = 'emergency';
    case Diagnostic = 'diagnostic';
    case Other = 'other';
}
