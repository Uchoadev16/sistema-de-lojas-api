<?php

namespace App\Support\Enums;

enum WorkOrderOrigin: string
{
    case Manual = 'manual';
    case MaintenancePlan = 'maintenance_plan';
    case Pmoc = 'pmoc';
    case Contract = 'contract';
    case QrCode = 'qr_code';
    case ServiceRequest = 'service_request';
    case Budget = 'budget';
}
