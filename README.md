# JWT for Laravel

>This is a Laravel package which provides all the means for a super easy JWT implementation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/babicaja/jwt-4laravel.svg?style=flat-square)](https://packagist.org/packages/babicaja/jwt-4laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/babicaja/jwt-4laravel.svg?style=flat-square)](https://packagist.org/packages/babicaja/jwt-4laravel)
[![Build Status](https://img.shields.io/travis/babicaja/jwt-4laravel.svg?style=flat-square)](https://travis-ci.org/babicaja/jwt-4laravel.svg)
[![Coverage](https://codecov.io/gh/babicaja/jwt-4laravel/branch/master/graph/badge.svg)](https://codecov.io/gh/babicaja/jwt-4laravel)
[![Licence](https://img.shields.io/github/license/babicaja/jwt-4laravel.svg?style=flat-square)](https://github.com/babicaja/jwt-4laravel)

- [Installation](#installation)
- [Getting started](#getting-started)
- [Configuration](#configuration)
- [Usage](#usage)
- [Checks](#checks)

## Installation

Make sure you include this package as part of your Laravel project. You can do so by running the composer command from below.

```bash
composer require babicaja/jwt-4laravel
```

## Getting started 

First register the service provider with the `artisan vendor:publish` command. This will ensure all the bindings are in place and it will copy the default configuration file to your app's config folder.

```bash
php artisan vendor:publish --provider JWT4L\Providers\JWTServiceProvider
``` 

Inspect the newly created `config/jwt.php` file. For now, you can leave it as it is (don't forget to change the `secret` key for production). Details about the configuration are covered in this [section](#configuration).

Now everything is in place and you can start using the JWT for Laravel's functionality. Easiest way to see it in actions is using the `Token` Facade provided by the package, and through `artisan tinker`. If you are able to see a similar output as the one below, you are all set.

```bash
php artisan tinker
Psy Shell v0.9.9 (PHP 7.3.6-1+ubuntu18.04.1+deb.sury.org+1 — cli) by Justin Hileman
>>> Token::create()
=> "eyJ0eXAiOiJKV1QiLCJhbGciOiJzaGEyNTYifQ==.eyJpYXQiOiIyMDE5LTA3LTAyVDE1OjAyOjQ5LjkzMTQwMloiLCJleHAiOiIyMDE5LTA3LTAyVDE1OjE3OjQ5LjkzMTQ2M1oifQ==.fa0f19c3a2a444d72bb58feb54227677e52c65e35f3db21b31673520ddb16c86"
```

## Configuration

Out of the box the configuration file comes with default values which you can use as they are. You should probably change the `secret` for any production code. You can set the values directly in the `config/jwt.php` file or preferably by setting the appropriate `.env` values.

>config/jwt.php
```php
<?php

return [
    'algorithm' => env('JWT_ALG', 'sha256'), //hash_hmac_algos()
    'expires' => env('JWT_EXP', 15), // minutes
    'secret' => env('JWT_KEY', 'secret'),
    'checks' => [
       \JWT4L\Checks\Structure::class,
       \JWT4L\Checks\Signature::class,
       \JWT4L\Checks\Expired::class
    ]
];
```

- Algorithm

    This value can be any of the algorithms from PHP's `hash_hmac_algos()`. The algorithm defined here is used for signing and comparing the JWT signature.

- Expires

    When creating a new Token, by default an `exp` claim is added to the `Payload` section. The amount of minutes defined here will set the expire date from the moment of creation.

- Secret

    This value is used in conjunction with the defined `algorithm` for signing and comparing the JWT signature.

- Checks

    You should add your custom [checks](#checks) here or choose which one of the defaults you want to use. It's strongly recommended to use the default ones, but you are free to do as you wish.

## Usage

There are a few ways to use the JWT for Laravel package:

- Token Facade

    Combines the functionality of the `\JWT4L\Token\Generator` and `\JWT4L\Token\Parser` Token Managers. This allows the user to create, validate and parse the JWT through one interface anywhere in the application.
    
    > Example of Token Facade usage
    ```php
    $token = Token::create() // create a JWT
    $payload = Token::payload($token) // retrieve the Payload section from the JWT
    ```  
- Token Managers

    There are two Token Managers. `\JWT4L\Token\Generator` is responsible for JWT creation and the `\JWT4L\Token\Parser` does the validation and parsing of JWTs. Both of the Token Managers are bound to Laravel's Service Container which allows you to inject them into constructors or method calls.
    
    > Example of Token Manager usage
    ```php
    <?php
    
    namespace App\Http\Controllers;  
    
    class TokenController extends Controller
    {
        public function generate(\JWT4L\Token\Generator $generator)
        {
            return response($generator->create());
        }
    }
    ```
- JWTGuard 

    The package provides a custom Guard called `JWTGuard`. Laravel's `Auth` will be automatically extended with this Guard but you need to manually configure it in the `config/auth.php`
    
    > config/auth.php
    ```php
        'guards' => [
            'web' => [
                'driver' => 'session',
                'provider' => 'users',
            ],
    
            'api' => [
                'driver' => 'token',
                'provider' => 'users',
                'hash' => false,
            ],
    
            'jwt' => [
                'driver' => 'jwt',
                'provider' => 'users',
            ],
        ],
    ```
    
    Now you can assign this guard to any route using the `auth:jwt` middleware.
    
    > Example of a route protected by the JWTGuard
    ```php
    Route::middleware('auth:jwt')->post('/user', function (Request $request) {
        return $request->user();
    });
    ```
    
    You can find a detailed usage examples [here](EXAMPLES.md)

## Checks

The package provides three JWT checks out of the box:
 
- `\JWT4L\Checks\Structure` verifies the expected `header.payload.signature` structure
- `\JWT4L\Checks\Signature` validates the hashed signature
- `\JWT4L\Checks\Expired` checks has the token expired

You can define your custom check by creating a class which implements the `\JWT4L\Checks\CheckContract` interface and include it in the `config/jwt.php`file.

> app/Checks/Friday.php
```php
<?php

namespace app\Checks;

use Carbon\Carbon;
use JWT4L\Checks\CheckContract;

class Friday implements CheckContract
{
    /**
     * Do necessary checks and throw a specific exception if conditions are not met.
     *
     * @param string $token
     * @return void
     * @throws mixed
     */
    public function validate(string $token)
    {
        if (!Carbon::now()->isFriday()) throw new \Exception("It's not Friday");
    }
}
```

> config/jwt.php
```php
<?php

return [
    'algorithm' => env('JWT_ALG', 'sha256'), //hash_hmac_algos()
    'expires' => env('JWT_EXP', 15), // minutes
    'secret' => env('JWT_KEY', 'secret'),
    'checks' => [
        \JWT4L\Checks\Structure::class,
        \JWT4L\Checks\Signature::class,
        \JWT4L\Checks\Expired::class,
        \App\Checks\Friday::class
    ]
];
```