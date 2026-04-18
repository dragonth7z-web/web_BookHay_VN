<?php

namespace App\Enums;

enum NotificationType: string
{
    case Order     = 'order';
    case Promotion = 'promotion';
    case System    = 'system';
}
