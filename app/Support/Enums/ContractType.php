<?php

namespace App\Support\Enums;

enum ContractType: string
{
    case Preventive = 'preventive';
    case FullService = 'full_service';
    case OnDemand = 'on_demand';
    case Rental = 'rental';
    case Other = 'other';
}
