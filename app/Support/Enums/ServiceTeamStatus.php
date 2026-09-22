<?php

namespace App\Support\Enums;

enum ServiceTeamStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Disbanded = 'disbanded';
}
