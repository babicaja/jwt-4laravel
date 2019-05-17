<?php

namespace JWT4L;

use JWT4L\Parser as JWTParser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

class JwtGuard implements Guard
{
    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * @var Authenticatable
     */
    private $user;
    /**
     * @var JWTParser
     */
    private $jwtParser;

    public function __construct(UserProvider $userProvider, JWTParser $jwtParser)
    {
        $this->userProvider = $userProvider;
        $this->jwtParser = $jwtParser;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     * @throws \JWT4L\Exceptions\JWTHeaderNotValidException
     * @throws \JWT4L\Exceptions\JWTPayloadNotValidException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function check()
    {
        return ! is_null($this->user());
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     * @throws \JWT4L\Exceptions\JWTHeaderNotValidException
     * @throws \JWT4L\Exceptions\JWTPayloadNotValidException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function guest()
    {
        return !$this->check();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws \JWT4L\Exceptions\JWTHeaderNotValidException
     * @throws \JWT4L\Exceptions\JWTPayloadNotValidException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function user()
    {
        $this->jwtParser->validate();

        $user = $this->userProvider->retrieveById($this->jwtParser->payload()->sub);

        $this->setUser($user);

        return $user;
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     * @throws \JWT4L\Exceptions\JWTHeaderNotValidException
     * @throws \JWT4L\Exceptions\JWTPayloadNotValidException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function id()
    {
        if ($this->user()) {
            return $this->user()->getAuthIdentifier();
        }
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        $this->user = $this->userProvider->retrieveByCredentials($credentials);

        return $this->user instanceof Authenticatable;

    }

    /**
     * Set the current user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @return void
     */
    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }
}