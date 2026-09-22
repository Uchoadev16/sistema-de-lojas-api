<?php

namespace App\Support\Enums;

enum ScheduleStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Rescheduled = 'rescheduled';
    case NoShow = 'no_show';
}
