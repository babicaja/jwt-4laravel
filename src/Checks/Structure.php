<?php

namespace JWT4L\Checks;

use JWT4L\Exceptions\JWTNotValidException;

class Structure implements CheckContract
{
    /**
     * Do necessary checks and throw a specific exception if conditions are not met.
     *
     * @param string $token
     * @return void
     * @throws mixed
     */
    public function validate(string $token)
    {
        if (count(explode('.', $token)) !== 3) throw new JWTNotValidException();
    }
}