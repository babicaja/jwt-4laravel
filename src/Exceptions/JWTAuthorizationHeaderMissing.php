<?php

namespace JWT4L\Exceptions;

use Exception;
use Throwable;

class JWTAuthorizationHeaderMissing extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct("Authorization Bearer token not found", 800, $previous);
    }
}