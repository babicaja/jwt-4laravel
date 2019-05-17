<?php

namespace App\Services\JWT\Sections;

use App\Services\JWT\Exceptions;
use App\Services\JWT\Traits\AlgorithmCheck;
use App\Services\JWT\Traits\Encoder;

class Signature
{
    use AlgorithmCheck, Encoder;

    /**
     * @var string
     */
    private $secret;

    /**
     * Signature constructor.
     *
     * @param string $algorithm
     * @param string $secret
     * @throws Exceptions\JWTAlgorithmNotSupportedException
     */
    public function __construct(string $algorithm, string $secret)
    {
        $this->isSupported($algorithm);

        $this->secret = $secret;
    }

    /**
     * Sign the claims.
     *
     * @param Header $header
     * @param Payload $payload
     * @return string
     */
    public function sign(Header $header, Payload $payload)
    {
        $data = implode('.', [
           $header->make(),
           $payload->make()
        ]);

        return $this->hash($this->algorithm, $data, $this->secret);
    }
}