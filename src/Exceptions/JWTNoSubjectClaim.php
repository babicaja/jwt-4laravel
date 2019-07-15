<?php

namespace JWT4L\Exceptions;

use Exception;

class JWTNoSubjectClaim extends Exception
{
    public function __construct()
    {
        parent::__construct("The sub claim is not set in the JWT payload", 720);
    }
}