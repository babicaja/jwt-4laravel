<?php

namespace JWT4L;

use ErrorException;
use JWT4L\Sections\Header;
use JWT4L\Sections\Payload;
use JWT4L\Token\Generator;
use JWT4L\Token\Parser;

/**
 * Class Token
 * @package JWT4L
 * @method string create()
 * @method Generator withHeader(array $claims, bool $replace = false)
 * @method Generator withPayload(array $claims, bool $replace = false)
 * @method Parser validate(string $token = null)
 * @method Payload payload(string $token = null)
 * @method Header header(string $token = null)
 */
class Token
{
    /**
     * @var array
     */
    private $tokenManagers;

    public function __construct(Generator $generator, Parser $parser)
    {
        $this->tokenManagers = [$generator, $parser];
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if (!count($this->tokenManagers)) throw new \Exception("The $name method is not supported.");

        try
        {
            return call_user_func_array([array_shift($this->tokenManagers), $name], $arguments);
        }
        catch (ErrorException $exception)
        {
            return $this->__call($name, $arguments);
        }
    }
}