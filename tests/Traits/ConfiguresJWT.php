<?php

namespace Tests\JWT4L\Traits;

use JWT4L\Checks\Expired;
use JWT4L\Checks\Signature;
use JWT4L\Checks\Structure;

trait ConfiguresJWT
{
    /**
     * Configure the JWT with the basic values and setup jwtGuard.
     */
    public function configure()
    {
        // basic configuration
        config(['jwt.algorithm' => 'sha256']);
        config(['jwt.secret' => 'test-secret']);
        config(['jwt.expires' => 15]);
        config(['jwt.checks' => [
            Structure::class,
            Signature::class,
            Expired::class
        ]]);

        // setup custom guard
        config(['auth.guards.jwt' => [
            'driver' => 'jwt',
            'provider' => 'users'
        ]]);

        // setup custom user provider
        config(['auth.providers.users' => [
            'driver' => 'test'
        ]]);
    }

    /**
     * Override any configuration value.
     *
     * @param array $config
     */
    public function overrideConfiguration(array $config)
    {
        config($config);
    }
}