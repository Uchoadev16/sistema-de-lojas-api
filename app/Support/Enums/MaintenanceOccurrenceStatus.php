<?php

namespace App\Support\Enums;

enum MaintenanceOccurrenceStatus: string
{
    case Pending = 'pending';
    case Scheduled = 'scheduled';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Overdue = 'overdue';
    case Cancelled = 'cancelled';
    case Skipped = 'skipped';
}
