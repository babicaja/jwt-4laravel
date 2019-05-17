<?php

namespace App\Services\JWT\Sections;

use App\Services\JWT\Exceptions\JWTAlgorithmNotSupportedException;

class Header extends Section
{
    /**
     * Set the default header claims.
     *
     * @param string $algorithm
     * @throws JWTAlgorithmNotSupportedException
     */
    public function __construct(string $algorithm)
    {
        $this->isSupported($algorithm);

        $this->claims = [
            "typ" => "JWT",
            "alg" => $algorithm
        ];
    }
}