<?php

namespace JWT4L\Traits;

use JWT4L\Exceptions;
use JWT4L\Exceptions\JWTAuthorizationHeaderMissing;

trait TokenFromRequest
{
    /**
     * Attempt to extract the token from the Authorization Bearer header.
     *
     * @throws JWTAuthorizationHeaderMissing
     */
    public function tokenFromRequest()
    {
        if (!$token = request()->bearerToken()) throw new JWTAuthorizationHeaderMissing();
        return $token;
    }

    /**
     * Fetch the token from the Authorization header if the provided token is not set.
     *
     * @param string $token
     * @return string|null
     * @throws Exceptions\JWTAuthorizationHeaderMissing
     */
    public function token(string $token = null)
    {
        return $token ?: $this->tokenFromRequest();
    }
}