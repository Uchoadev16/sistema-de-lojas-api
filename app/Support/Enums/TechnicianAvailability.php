<?php

namespace App\Support\Enums;

enum TechnicianAvailability: string
{
    case Available = 'available';
    case Busy = 'busy';
    case OnCall = 'on_call';
    case OffDuty = 'off_duty';
    case Traveling = 'traveling';
}
