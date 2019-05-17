<?php

namespace App\Services\JWT\Checks;

use App\Services\JWT\Exceptions\JWTSignatureNotValidException;
use App\Services\JWT\Traits\AlgorithmCheck;
use App\Services\JWT\Traits\Detokenize;
use App\Services\JWT\Traits\Encoder;

class Signature implements CheckContract
{
    use AlgorithmCheck, Encoder, Detokenize;

    /**
     * @var \App\Services\JWT\Sections\Signature
     */
    private $signature;

    public function __construct(\App\Services\JWT\Sections\Signature $signature)
    {
        $this->signature = $signature;
    }

    /**
     * Do necessary checks and throw a specific exception if conditions are not met.
     *
     * @param string $token
     * @return void
     * @throws mixed
     */
    public function validate(string $token)
    {
        $calculatedHash = $this->signature->sign($this->headerFromToken($token), $this->payloadFromToken($token));
        $providedHash = $this->signatureFromToken($token);

        if (!hash_equals($calculatedHash, $providedHash)) throw new JWTSignatureNotValidException();
    }
}