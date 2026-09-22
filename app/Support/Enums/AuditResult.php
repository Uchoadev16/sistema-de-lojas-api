<?php

namespace App\Support\Enums;

enum AuditResult: string
{
    case Success = 'success';
    case Failed = 'failed';
    case Denied = 'denied';
    case Error = 'error';
}
