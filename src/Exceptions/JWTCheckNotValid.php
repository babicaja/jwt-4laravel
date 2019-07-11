<?php

namespace JWT4L\Exceptions;

use Exception;

class JWTCheckNotValid extends Exception
{
    public function __construct(string $check)
    {
        parent::__construct("The {$check} must be an instance of JWT4L\\Checks\\CheckContract", 900);
    }
}