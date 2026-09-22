<?php

namespace App\Support\Enums;

enum PermissionEffect: string
{
    case Allow = 'allow';
    case Deny = 'deny';
}
