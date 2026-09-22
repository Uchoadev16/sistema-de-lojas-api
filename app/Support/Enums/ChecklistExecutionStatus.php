<?php

namespace App\Support\Enums;

enum ChecklistExecutionStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case PartiallyCompleted = 'partially_completed';
    case Cancelled = 'cancelled';
}
