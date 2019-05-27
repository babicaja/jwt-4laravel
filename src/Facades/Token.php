<?php

namespace JWT4L\Facades;

use Illuminate\Support\Facades\Facade;

class Token extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'jwt-token';
    }
}