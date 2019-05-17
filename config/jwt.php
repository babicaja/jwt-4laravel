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