<?php

namespace JWT4L;

use ErrorException;
use Exception;
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
        try
        {
            $result = call_user_func_array([current($this->tokenManagers), $name], $arguments);
            reset($this->tokenManagers);
            return $result;
        }
        catch (ErrorException $exception)
        {
            if (!next($this->tokenManagers))
            {
                reset($this->tokenManagers);
                throw new Exception("The $name method is not supported.");
            }

            return $this->__call($name, $arguments);
        }
    }
}