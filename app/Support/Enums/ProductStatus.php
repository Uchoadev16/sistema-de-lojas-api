<?php

namespace App\Support\Enums;

enum ProductStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Discontinued = 'discontinued';
    case OutOfStock = 'out_of_stock';
}
