<?php

namespace Tests\JWT4L;

use JWT4L\Providers\JWTServiceProvider;
use Orchestra\Testbench\TestCase;
use Tests\JWT4L\Providers\TestServiceProvider;
use Tests\JWT4L\Traits\ConfiguresJWT;
use Tests\JWT4L\Traits\FakesTime;

abstract class BaseTest extends TestCase
{
    use ConfiguresJWT, FakesTime;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configure();
        $this->setTime();
    }

    protected function getPackageProviders($app)
    {
        return [
            JWTServiceProvider::class,
            TestServiceProvider::class
        ];
    }
}