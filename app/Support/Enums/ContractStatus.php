<?php

namespace App\Support\Enums;

enum ContractStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Paused = 'paused';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
    case Renewed = 'renewed';
}
