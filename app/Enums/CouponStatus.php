<?php

namespace App\Enums;

enum CouponStatus: string
{
    case Active  = 'active';
    case Paused  = 'paused';
    case Expired = 'expired';
}
