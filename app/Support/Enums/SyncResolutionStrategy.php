<?php

namespace App\Support\Enums;

enum SyncResolutionStrategy: string
{
    case ServerWins = 'server_wins';
    case ClientWins = 'client_wins';
    case Manual = 'manual';
    case Merge = 'merge';
}
