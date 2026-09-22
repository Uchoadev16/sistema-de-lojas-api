<?php

namespace App\Support\Enums;

enum SyncOperationType: string
{
    case Create = 'create';
    case Update = 'update';
    case Delete = 'delete';
    case Complete = 'complete';
    case StatusChange = 'status_change';
    case Upload = 'upload';
}
