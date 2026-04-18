<?php

namespace App\Exceptions;

class CartNotFoundException extends CartOperationException
{
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}
