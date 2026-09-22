<?php

namespace App\Support\Enums;

enum WorkOrderItemType: string
{
    case Service = 'service';
    case Labor = 'labor';
    case Travel = 'travel';
    case Other = 'other';
}
