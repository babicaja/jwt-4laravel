<?php

namespace Tests\JWT4L;

use JWT4L\Providers\JWTServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class PackageTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            JWTServiceProvider::class
        ];
    }
}