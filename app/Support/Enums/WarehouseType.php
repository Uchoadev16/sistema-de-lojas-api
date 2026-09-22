<?php

namespace App\Support\Enums;

enum WarehouseType: string
{
    case Main = 'main';
    case Branch = 'branch';
    case Technician = 'technician';
    case Mobile = 'mobile';
    case Transit = 'transit';
    case Other = 'other';
}
