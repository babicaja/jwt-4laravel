<?php

namespace JWT4L\Exceptions;

use Exception;
use Throwable;

class JWTSignatureNotValidException extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct("The hash signature is compromised.", 500, $previous);
    }

}