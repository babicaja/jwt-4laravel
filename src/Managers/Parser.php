<?php

namespace JWT4L\Managers;

use Illuminate\Contracts\Container\BindingResolutionException;
use JWT4L\Exceptions;
use JWT4L\Traits\Detokenize;
use JWT4L\Traits\TokenFromRequest;

class Parser
{
    use TokenFromRequest, Detokenize;

    /**
     * Extract the payload section from the token.
     *
     * @param string|null $token
     * @return mixed
     * @throws Exceptions\JWTHeaderNotValid
     * @throws Exceptions\JWTPayloadNotValid
     * @throws BindingResolutionException
     * @throws Exceptions\JWTAuthorizationHeaderMissing
     */
    public function payload(string $token = null)
    {
        return $this->payloadFromToken($this->token($token));
    }

    /**
     * Extract the header section from the token.
     *
     * @param string|null $token
     * @return mixed
     * @throws Exceptions\JWTHeaderNotValid
     * @throws Exceptions\JWTPayloadNotValid
     * @throws BindingResolutionException
     * @throws Exceptions\JWTAuthorizationHeaderMissing
     */
    public function header(string $token = null)
    {
        return $this->headerFromToken($this->token($token));
    }
}