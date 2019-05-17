<?php

namespace App\Services\JWT\Sections;

use Carbon\CarbonImmutable;

class Payload extends Section
{
    /**
     * Sets the default payload claims.
     *
     * @param int $minutes expiration in minutes after creation.
     */
    public function __construct(int $minutes)
    {
        $now = CarbonImmutable::now();

        $this->claims = [
            "iat" => $now,
            "exp" => $now->addMinutes($minutes),
        ];
    }
}