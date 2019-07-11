<?php

namespace JWT4L\Checks;

use JWT4L\Exceptions\JWTSignatureNotValid;
use JWT4L\Traits\AlgorithmCheck;
use JWT4L\Traits\Detokenize;
use JWT4L\Traits\Encoder;

class Signature implements CheckContract
{
    use AlgorithmCheck, Encoder, Detokenize;

    /**
     * @var \JWT4L\Sections\Signature
     */
    private $signature;

    public function __construct(\JWT4L\Sections\Signature $signature)
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

        if (!hash_equals($calculatedHash, $providedHash)) throw new JWTSignatureNotValid();
    }
}