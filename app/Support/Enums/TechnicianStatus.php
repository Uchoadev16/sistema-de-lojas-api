<?php

namespace App\Support\Enums;

enum TechnicianStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case OnLeave = 'on_leave';
    case Dismissed = 'dismissed';
}
