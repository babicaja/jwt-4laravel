<?php

namespace App\Services\JWT\Traits;

use App\Services\JWT\Exceptions\JWTAuthorizationHeaderMissingException;

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