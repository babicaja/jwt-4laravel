<?php

return [
    'algorithm' => env('JWT_ALG', 'sha256'), //hash_hmac_algos()
    'expires' => env('JWT_EXP', 15), // minutes
    'secret' => env('JWT_KEY', 'secret'),
    'checks' => [
        \App\Services\JWT\Checks\Structure::class,
        \App\Services\JWT\Checks\Signature::class,
        \App\Services\JWT\Checks\Expired::class
    ]
];