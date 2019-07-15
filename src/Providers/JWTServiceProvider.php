<?php

namespace JWT4L\Providers;

use JWT4L\JwtGuard;
use JWT4L\Managers;
use JWT4L\Managers\Parser;
use JWT4L\Managers\Validator;
use JWT4L\Sections\Header;
use JWT4L\Sections\Payload;
use JWT4L\Sections\Signature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use JWT4L\Token;

class JWTServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Header::class, function () {

            return new Header(config('jwt.algorithm', 'sha256'));
        });

        $this->app->bind(Payload::class, function () {

            return new Payload(config('jwt.expires', 15));
        });

        $this->app->bind(Signature::class, function () {

            return new Signature(config('jwt.algorithm', 'sha256'), config('jwt.secret', 'secret'));
        });

        $this->app->bind(Validator::class, function () {

            return new Validator(config('jwt.checks', []));
        });

        $this->app->bind('jwt-token', function ($app) {

            return new Token(resolve(Managers\Generator::class), resolve(Validator::class), resolve(Parser::class));
        });

        Auth::extend('jwt', function($app, $name, array $config){

            return new JwtGuard(Auth::createUserProvider($config['provider']), resolve(Validator::class), resolve(Parser::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/jwt.php' => config_path('jwt.php'),
        ]);
    }
}
