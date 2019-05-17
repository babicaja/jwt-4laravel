<?php

namespace App\Services\JWT;

use App\Services\JWT\Traits\Detokenize;
use App\Services\JWT\Traits\TokenFromRequest;

class Parser
{
    use TokenFromRequest, Detokenize;

    /**
     * @var array
     */
    private $checks;

    public function __construct(array $checks = [])
    {
        $this->checks = $checks;
    }

    /**
     * Validate the token with configured checks. If the token is not provided it
     * will attempt to extract it from the Authorization Bearer header.
     *
     * @param string|null $token
     * @return Parser
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function validate(string $token = null)
    {
        $token = $token ? : $this->tokenFromRequest();

        foreach ($this->checks as $check)
        {
            app()->make($check)->validate($token);
        }

        return $this;
    }

    /**
     * @param string|null $token
     * @return mixed
     * @throws Exceptions\JWTHeaderNotValidException
     * @throws Exceptions\JWTPayloadNotValidException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function payload(string $token = null)
    {
        $token = $token ? : $this->tokenFromRequest();
        return $this->payloadFromToken($token);
    }

    /**
     * @param string|null $token
     * @return mixed
     * @throws Exceptions\JWTHeaderNotValidException
     * @throws Exceptions\JWTPayloadNotValidException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function header(string $token = null)
    {
        $token = $token ? : $this->tokenFromRequest();
        return $this->headerFromToken($token);
    }
}