<?php

namespace App\Support\Enums;

enum QrCodeEntityType: string
{
    case Equipment = 'equipment';
    case Unit = 'unit';
    case Environment = 'environment';
    case Other = 'other';
}
