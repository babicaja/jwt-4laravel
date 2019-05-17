<?php

namespace JWT4L\Providers;

use JWT4L\JwtGuard;
use JWT4L\Parser;
use JWT4L\Sections\Header;
use JWT4L\Sections\Payload;
use JWT4L\Sections\Signature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

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

            return new Signature(config('jwt.algorithm', 'sha256'), config('jwt.secret'));
        });

        $this->app->bind(Parser::class, function () {

            return new Parser(config('jwt.checks', []));
        });

        Auth::extend('jwt', function($app, $name, array $config){

            $parser = $this->app->make(Parser::class);
            return new JwtGuard(Auth::createUserProvider($config['provider']), $parser);
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
            __DIR__.'/../../config/jwt-4laravel.php' => config_path('jwt-4laravel.php'),
        ]);
    }
}
