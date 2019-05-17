<?php

namespace JWT4L\Traits;

use JWT4L\Exceptions\JWTAuthorizationHeaderMissingException;

trait TokenFromRequest
{
    /**
     * Attempt to extract the token from the Authorization Bearer header.
     *
     * @throws JWTAuthorizationHeaderMissingException
     */
    public function tokenFromRequest()
    {
        if (!$token = request()->bearerToken()) throw new JWTAuthorizationHeaderMissingException();
        return $token;
    }
}