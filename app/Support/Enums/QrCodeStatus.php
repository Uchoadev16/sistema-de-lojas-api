<?php

namespace App\Support\Enums;

enum QrCodeStatus: string
{
    case Linked = 'linked';
    case Unlinked = 'unlinked';
    case Revoked = 'revoked';
}
