<?php

namespace JWT4L;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Container\BindingResolutionException;
use JWT4L\Exceptions\JWTHeaderNotValid;
use JWT4L\Exceptions\JWTNoSubjectClaim;
use JWT4L\Exceptions\JWTPayloadNotValid;
use JWT4L\Managers\Parser as JWTParser;
use JWT4L\Managers\Validator as JWTValidator;

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
     * @var JWTValidator
     */
    private $jwtValidator;

    /**
     * @var JWTParser
     */
    private $jwtParser;

    /**
     * JwtGuard constructor.
     *
     * @param UserProvider $userProvider
     * @param JWTValidator $jwtValidator
     * @param JWTParser $jwtParser
     */
    public function __construct(UserProvider $userProvider, JWTValidator $jwtValidator, JWTParser $jwtParser)
    {
        $this->userProvider = $userProvider;
        $this->jwtValidator = $jwtValidator;
        $this->jwtParser = $jwtParser;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     * @throws BindingResolutionException
     * @throws Exceptions\JWTAuthorizationHeaderMissing
     * @throws Exceptions\JWTCheckNotValid
     * @throws JWTHeaderNotValid
     * @throws JWTNoSubjectClaim
     * @throws JWTPayloadNotValid
     */
    public function check()
    {
        return ! is_null($this->user());
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     * @throws BindingResolutionException
     * @throws Exceptions\JWTAuthorizationHeaderMissing
     * @throws Exceptions\JWTCheckNotValid
     * @throws JWTHeaderNotValid
     * @throws JWTNoSubjectClaim
     * @throws JWTPayloadNotValid
     */
    public function guest()
    {
        return !$this->check();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return Authenticatable|null
     * @throws BindingResolutionException
     * @throws Exceptions\JWTAuthorizationHeaderMissing
     * @throws Exceptions\JWTCheckNotValid
     * @throws JWTHeaderNotValid
     * @throws JWTNoSubjectClaim
     * @throws JWTPayloadNotValid
     */
    public function user()
    {
        $this->jwtValidator->validate();
        $sub = $this->jwtParser->payload()->sub;

//        if(!$sub) {
//            echo "there is no sub.";
//        } else {
//            echo "we have a sub";
//        }

        if(!$sub) throw new JWTNoSubjectClaim();
//        else echo "here";
        $user = $this->userProvider->retrieveById($sub);

        $this->setUser($user);

        return $user;
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     * @throws BindingResolutionException
     * @throws Exceptions\JWTAuthorizationHeaderMissing
     * @throws Exceptions\JWTCheckNotValid
     * @throws JWTHeaderNotValid
     * @throws JWTNoSubjectClaim
     * @throws JWTPayloadNotValid
     */
    public function id()
    {
        return $this->user()->getAuthIdentifier();
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
     * @param Authenticatable $user
     * @return void
     */
    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }
}