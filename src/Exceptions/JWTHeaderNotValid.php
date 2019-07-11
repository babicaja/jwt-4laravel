<?php

namespace JWT4L\Exceptions;

use Exception;
use Throwable;

class JWTHeaderNotValid extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct("The JWT Header is not valid", 200, $previous);
    }
}