<?php

namespace Tests\JWT4L\Traits;

trait CapturesOutputBuffer
{
    /**
     * @param callable $callable
     * @param mixed ...$arguments
     * @return false|string
     */
    public function capture(callable $callable, ...$arguments)
    {
        ob_start();
        $callable($arguments);
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}