<?php

namespace App\Support\Enums;

enum WorkOrderStatus: string
{
    case Draft = 'draft';
    case Scheduled = 'scheduled';
    case Assigned = 'assigned';
    case Accepted = 'accepted';
    case EnRoute = 'en_route';
    case InProgress = 'in_progress';
    case Paused = 'paused';
    case WaitingApproval = 'waiting_approval';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Reopened = 'reopened';
}
