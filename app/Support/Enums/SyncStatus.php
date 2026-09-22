<?php

namespace App\Support\Enums;

enum SyncStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Synced = 'synced';
    case Conflict = 'conflict';
    case Failed = 'failed';
}
