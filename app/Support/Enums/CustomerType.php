<?php

namespace App\Support\Enums;

enum CustomerType: string
{
    case Individual = 'individual';
    case Company = 'company';
    case Other = 'other';
}
