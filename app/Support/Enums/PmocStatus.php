<?php

namespace App\Support\Enums;

enum PmocStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Paused = 'paused';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Expired = 'expired';
}
