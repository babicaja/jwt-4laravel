<?php

namespace JWT4L\Exceptions;

use Exception;

class JWTMethodNotSupported extends Exception
{
    public function __construct(string $message = "")
    {
        parent::__construct($message, 10000);
    }
}