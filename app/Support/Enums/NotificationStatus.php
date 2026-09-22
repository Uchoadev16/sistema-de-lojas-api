<?php

namespace App\Support\Enums;

enum NotificationStatus: string
{
    case Pending = 'pending';
    case Queued = 'queued';
    case Sent = 'sent';
    case Read = 'read';
    case Failed = 'failed';
    case Cancelled = 'cancelled';
}
