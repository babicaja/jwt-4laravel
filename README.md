#JWT for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/babicaja/jwt-4laravel.svg?style=flat-square)](https://packagist.org/packages/babicaja/jwt-4laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/babicaja/jwt-4laravel.svg?style=flat-square)](https://packagist.org/packages/babicaja/jwt-4laravel)
[![Build Status](https://img.shields.io/travis/babicaja/jwt-4laravel.svg?style=flat-square)](https://travis-ci.org/babicaja/jwt-4laravel.svg)
[![Coverage](https://codecov.io/gh/babicaja/jwt-4laravel/branch/master/graph/badge.svg)](https://codecov.io/gh/babicaja/jwt-4laravel)
[![Licence](https://img.shields.io/github/license/babicaja/jwt-4laravel.svg?style=flat-square)](https://github.com/babicaja/jwt-4laravel)

>This is a Laravel package which provides all the means for a super easy JWT implementation

- [Installation](#installation)
- [Getting started](#getting-started)
- [Configuration](#configuration)
- [Usage](#usage)
- [Components](#components)
- [Contributing](#contributing)
- [Licensing](#licensing)

##Installation

Make sure you include this package as part of your Laravel project. You can do so by running the composer command from below.

```bash
composer require babicaja/jwt-4laravel
```

##Getting started

To start using the JWT for Laravel package you'll need to do a couple of standard steps to ensure all the bindings are in place and that the configuration file is published. After that you can configure the package to suit your needs, and start using it through the Token facade or using the JWTGuard. Just follow the steps below, and you will be done in no time. 

First register the service provider with the `artisan vendor:publish` command. This will ensure all the bindings are in place and  it will copy the default configuration file to your app's config folder.

```bash
php artisan vendor:publish --provider JWT4L\Providers\JWTServiceProvider
``` 

Inspect the newly created `config/jwt.php` file. For now, you can leave it as it is (don't forget to change the `secret` key for production). Details about the configuration are covered in the [Configuration](#configuration) section.

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

Now everything is in place and you can stat using the JWT for Laravel's functionality. Easiest way to see it in actions is using the `Token` facade provided by the package, and through `artisan tinker`. If you are able to see a similar output as the one below, you are all setup.

```bash
php artisan tinker
Psy Shell v0.9.9 (PHP 7.3.6-1+ubuntu18.04.1+deb.sury.org+1 — cli) by Justin Hileman
>>> Token::create()
=> "eyJ0eXAiOiJKV1QiLCJhbGciOiJzaGEyNTYifQ==.eyJpYXQiOiIyMDE5LTA2LTE2VDIxOjM3OjI1Ljg5MTMwNloiLCJleHAiOiIyMDE5LTA2LTE2VDIxOjUyOjI1Ljg5MTM2MloifQ==.6d0344ec099e0e4b3304d5fa0436945d6ffb0d4545b6c7e759da54f890b52d48"
```

##Configuration

Out of the box the configuration file comes with values which you can use as they are. You should probably change the `secret` for any production code. Nevertheless, all of the configuration options will be explained so you can tailor the package for your own needs.

##Usage
TBD
##Components
TBD
##Contributing
TBD
##Licensing
TBD
