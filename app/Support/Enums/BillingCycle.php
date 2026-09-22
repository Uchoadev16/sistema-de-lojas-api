<?php

namespace App\Support\Enums;

enum BillingCycle: string
{
    case Monthly = 'monthly';
    case Quarterly = 'quarterly';
    case Semiannual = 'semiannual';
    case Annual = 'annual';
    case Biennial = 'biennial';
}
