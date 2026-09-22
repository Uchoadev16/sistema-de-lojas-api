<?php

namespace App\Support\Enums;

enum EquipmentCondition: string
{
    case New = 'new';
    case Excellent = 'excellent';
    case Good = 'good';
    case Fair = 'fair';
    case Poor = 'poor';
    case Critical = 'critical';
}
