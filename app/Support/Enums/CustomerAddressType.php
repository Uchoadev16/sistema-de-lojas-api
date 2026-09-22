<?php

namespace App\Support\Enums;

enum CustomerAddressType: string
{
    case Billing = 'billing';
    case Commercial = 'commercial';
    case Service = 'service';
    case Shipping = 'shipping';
    case Other = 'other';
}
