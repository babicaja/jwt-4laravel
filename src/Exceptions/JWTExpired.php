<?php

namespace JWT4L\Exceptions;

use Exception;
use Throwable;

class JWTExpired extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct("Your token has expired", 700, $previous);
    }

}