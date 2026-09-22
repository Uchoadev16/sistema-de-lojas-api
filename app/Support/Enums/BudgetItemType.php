<?php

namespace App\Support\Enums;

enum BudgetItemType: string
{
    case Service = 'service';
    case Material = 'material';
    case Labor = 'labor';
    case Travel = 'travel';
    case Other = 'other';
}
