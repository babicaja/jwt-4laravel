<?php

namespace App\Services\JWT\Exceptions;

use Exception;
use Throwable;

class JWTExpiredException extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct("Your token has expired", 700, $previous);
    }

}