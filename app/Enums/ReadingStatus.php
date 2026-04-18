<?php

namespace App\Enums;

enum ReadingStatus: string
{
    case WantToRead = 'want_to_read';
    case Reading    = 'reading';
    case Finished   = 'finished';
}
