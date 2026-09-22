<?php

namespace App\Support\Enums;

enum TransactionType: string
{
    case Income = 'income';
    case Expense = 'expense';
    case Refund = 'refund';
    case Adjustment = 'adjustment';
}
