<?php

namespace App\Support\Enums;

enum Priority: string
{
    case Low = 'low';
    case Normal = 'normal';
    case High = 'high';
    case Urgent = 'urgent';
    case Critical = 'critical';
}
