<?php

namespace JWT4L\Exceptions;

use Exception;

class JWTNoExpiredClaim extends Exception
{
    public function __construct()
    {
        parent::__construct("The exp claim is not set in the JWT payload", 710);
    }
}