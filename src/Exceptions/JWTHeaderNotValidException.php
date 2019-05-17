<?php

namespace App\Services\JWT\Exceptions;

use Exception;
use Throwable;

class JWTHeaderNotValidException extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct("The JWT Header is not valid", 200, $previous);
    }
}