<?php

namespace JWT4L;

use ErrorException;
use Exception;
use JWT4L\Exceptions\JWTMethodNotSupported;
use JWT4L\Managers\Validator;
use JWT4L\Sections\Header;
use JWT4L\Sections\Payload;
use JWT4L\Managers\Generator;
use JWT4L\Managers\Parser;

/**
 * Class Token
 * @package JWT4L
 * @method string create()
 * @method Generator withHeader(array $claims, bool $replace = false)
 * @method Generator withPayload(array $claims, bool $replace = false)
 * @method Validator validate(string $token = null)
 * @method Payload payload(string $token = null)
 * @method Header header(string $token = null)
 */
class Token
{
    /**
     * @var array
     */
    private $tokenManagers;

    public function __construct(Generator $generator, Validator $validator, Parser $parser)
    {
        $this->tokenManagers = [$generator, $validator, $parser];
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        reset($this->tokenManagers);

        do {
            try {
                return call_user_func_array([current($this->tokenManagers), $name], $arguments);
            } catch (ErrorException $exception){}
        } while (next($this->tokenManagers));

        /** @noinspection PhpUnreachableStatementInspection */
        throw new JWTMethodNotSupported("The {$name} method is not supported");
    }
}