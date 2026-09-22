<?php

namespace App\Support\Enums;

enum ServiceRequestStatus: string
{
    case Open = 'open';
    case InReview = 'in_review';
    case Scheduled = 'scheduled';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';
    case Cancelled = 'cancelled';
}
