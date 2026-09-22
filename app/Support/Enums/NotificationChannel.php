<?php

namespace App\Support\Enums;

enum NotificationChannel: string
{
    case InApp = 'in_app';
    case Push = 'push';
    case Email = 'email';
    case WhatsApp = 'whatsapp';
    case Sms = 'sms';
}
