<?php

namespace App\Services\JWT\Checks;

interface CheckContract
{
    /**
     * Do necessary checks and throw a specific exception if conditions are not met.
     *
     * @param string $token
     * @return void
     * @throws mixed
     */
    public function validate(string $token);
}