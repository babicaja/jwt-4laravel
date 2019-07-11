<?php

namespace JWT4L\Exceptions;

use Exception;
use Throwable;

class JWTNotValid extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct("The provided token is not valid", 100, $previous);
    }
}