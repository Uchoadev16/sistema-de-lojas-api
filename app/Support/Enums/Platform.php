<?php

namespace App\Support\Enums;

enum Platform: string
{
    case Android = 'android';
    case Ios = 'ios';
    case Web = 'web';
    case Desktop = 'desktop';
}
