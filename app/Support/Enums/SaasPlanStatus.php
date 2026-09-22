<?php

namespace App\Support\Enums;

enum SaasPlanStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Archived = 'archived';
    case Discontinued = 'discontinued';
}
