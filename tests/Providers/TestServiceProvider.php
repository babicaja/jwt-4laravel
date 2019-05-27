<?php

namespace Tests\JWT4L\Providers;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Tests\JWT4L\Stubs\TestUserProvider;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Register the tests user provider.
     */
    public function register()
    {
       Auth::provider('test', function ($app) {
           return $app->make(TestUserProvider::class);
       });
    }

    /**
     * Bootstrap the routes.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
    }

}