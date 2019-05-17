<?php

namespace JWT4L\Exceptions;

use Exception;
use Throwable;

class JWTPayloadNotValidException extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct("The JWT Payload is not valid", 300, $previous);
    }
}