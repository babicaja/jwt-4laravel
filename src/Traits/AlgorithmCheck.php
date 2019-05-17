<?php

namespace JWT4L\Traits;

use JWT4L\Exceptions\JWTAlgorithmNotSupportedException;

trait AlgorithmCheck
{
    /**
     * @var string
     */
    protected $algorithm;

    /**
     * Check to see if the algorithm from the configuration is supported.
     *
     * @param string $algorithm
     * @return void
     * @throws JWTAlgorithmNotSupportedException
     */
    public function isSupported(string $algorithm)
    {
        if (!in_array($algorithm, hash_hmac_algos())) throw new JWTAlgorithmNotSupportedException($algorithm);

        $this->algorithm = $algorithm;
    }
}