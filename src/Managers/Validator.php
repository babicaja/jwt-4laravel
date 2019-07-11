<?php

namespace JWT4L\Managers;

use Illuminate\Contracts\Container\BindingResolutionException;
use JWT4L\Checks\CheckContract;
use JWT4L\Exceptions\JWTAuthorizationHeaderMissing;
use JWT4L\Exceptions\JWTCheckNotValid;
use JWT4L\Traits\TokenFromRequest;

class Validator
{
    use TokenFromRequest;

    /**
     * @var array
     */
    private $checks;

    /**
     * Parser constructor.
     *
     * @param array $checks
     */
    public function __construct(array $checks = [])
    {
        $this->checks = $checks;
    }

    /**
     * Validate the token with configured checks. If the token is not provided it
     * will attempt to extract it from the Authorization Bearer header.
     *
     * @param string|null $token
     * @return boolean
     * @throws BindingResolutionException
     * @throws JWTAuthorizationHeaderMissing
     * @throws JWTCheckNotValid
     */
    public function validate(string $token = null)
    {
        foreach ($this->checks as $check)
        {
            $this->makeCheck($check)->validate($this->token($token));
        }

        return true;
    }

    /**
     * Check is the defined $check instance of CheckContract, and resolve it out of the container.
     *
     * @param $check
     * @return CheckContract
     * @throws BindingResolutionException
     * @throws JWTCheckNotValid
     */
    private function makeCheck($check)
    {
        $instance = app()->make($check);

        if (! $instance instanceof CheckContract) throw new JWTCheckNotValid($check);

        return $instance;
    }
}