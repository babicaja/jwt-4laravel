<?php

namespace JWT4L\Exceptions;

use Exception;

class JWTAlgorithmNotSupportedException extends Exception
{
    public function __construct(string $algorithm)
    {
        parent::__construct("The {$algorithm} algorithm is not supported", 400);
    }
}