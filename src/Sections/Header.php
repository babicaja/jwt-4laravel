<?php

namespace JWT4L\Sections;

use JWT4L\Exceptions\JWTAlgorithmNotSupported;

class Header extends Section
{
    /**
     * Set the default header claims.
     *
     * @param string $algorithm
     * @throws JWTAlgorithmNotSupported
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