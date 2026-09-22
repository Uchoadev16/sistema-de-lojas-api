<?php

namespace App\Support\Enums;

enum BudgetStatus: string
{
    case Draft = 'draft';
    case Sent = 'sent';
    case Viewed = 'viewed';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
    case Converted = 'converted';
}
