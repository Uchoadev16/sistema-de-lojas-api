<?php

namespace App\Support\Enums;

enum TransactionStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Overdue = 'overdue';
    case Cancelled = 'cancelled';
    case PartiallyPaid = 'partially_paid';
}
