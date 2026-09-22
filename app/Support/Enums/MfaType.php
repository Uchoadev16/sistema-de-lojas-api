<?php

namespace App\Support\Enums;

enum MfaType: string
{
    case Totp = 'totp';
    case Email = 'email';
    case Sms = 'sms';
    case Recovery = 'recovery';
}
