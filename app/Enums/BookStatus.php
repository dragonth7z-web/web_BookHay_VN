<?php

namespace App\Enums;

enum BookStatus: string
{
    case InStock      = 'in_stock';
    case OutOfStock   = 'out_of_stock';
    case Discontinued = 'discontinued';
}
